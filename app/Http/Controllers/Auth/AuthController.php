<?php

namespace App\Http\Controllers\Auth;

use Alert;
use App;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\BarcodeRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\TemporaryAuth;
use App\Models\MailTemplate;
use App\User;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Sentinel;
use Reminder;
use Activation;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use URL;
use DB;
use Mail;
use Redirect;
use Socialite;
use Carbon;

class AuthController extends Controller
{

    public function auth() 
    {
        alert()->error('', 'Uw account is nog niet geactiveerd, of u bent nog niet ingelogd.')->persistent('Sluiten');

        return Redirect::to('/');
    }

    public function authRemove(Request $request)
    {
        $authCheck = TemporaryAuth::where('code', '=', $request->input('code'))->first();
        
        if ($authCheck) {
            $date = Carbon\Carbon::create(
                date('Y', strtotime($authCheck->created_at)), 
                date('m', strtotime($authCheck->created_at)), 
                date('d', strtotime($authCheck->created_at)), 
                date('H', strtotime($authCheck->created_at)), 
                date('i', strtotime($authCheck->created_at))
            );
            
            $expireDate = $date->addHours(2);

            if ($expireDate->isPast() == FALSE) {
                $authCheck->terms_active = 1;
                $authCheck->save();

                return Redirect::to($request->input('redirectTo'));
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function authSet(Request $request, $authCode) 
    {
        $authCheck = TemporaryAuth::where('code', '=', $authCode)->first();

        if ($authCheck) {
            $date = Carbon\Carbon::create(
                date('Y', strtotime($authCheck->created_at)), 
                date('m', strtotime($authCheck->created_at)), 
                date('d', strtotime($authCheck->created_at)), 
                date('H', strtotime($authCheck->created_at)), 
                date('i', strtotime($authCheck->created_at))
            );

            $user = Sentinel::findById($authCheck->user_id);

            $authCheckCount = TemporaryAuth::where('user_id', '=', $authCheck->user_id)
                ->where('terms_active', '=', 1)
                ->count()
            ;

            if ($user) {
                if ($request->has('confirm')) {
                    $expireDate = $date->addDays(7);

                    if ($expireDate->isPast() == FALSE) {
                        Sentinel::login($user);

                        return redirect()->action(
                            'Auth\AuthController@authRemove', 
                            array(
                                'code' => $authCode,
                                'redirectTo' => $authCheck->redirect_to
                            )
                        );
                    }
                } else {
                    if ($authCheckCount == 0) {
                        return view('account/auth/alert', array(
                            'name' => $user->name
                        ));
                    } else {
                        $expireDate = $date->addDays(7);

                        if ($expireDate->isPast() == FALSE) {
                            Sentinel::login($user);

                            return redirect()->action(
                                'Auth\AuthController@authRemove', 
                                array(
                                    'code' => $authCode,
                                    'redirectTo' => $authCheck->redirect_to
                                )
                            );
                        }
                    }
                }
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function logout() 
    {
        Sentinel::logout();
        return Redirect::to('/');
    }

    public function forgotPassword() 
    {
        return view('account/forgot-password');
    }

    public function activate($code) 
    {
        $userId = Activation::where(
            'code', $code
        )
            ->first()
            ->user_id
        ;

        $user = Sentinel::findById($userId);

        if (Activation::complete($user, $code)) {       
            Alert::success(
                'Uw account is succesvol geactiveerd.'
            )
                ->persistent('Sluiten')
            ;   
        }

        return Redirect::to('/');
    }

    public function sendMailAgain($code)
    {
        $activation = Activation::where(
            'code', $code
        )
            ->where('completed', 0)
            ->first()
        ;
        
        if ($activation) {
            $user = Sentinel::findById($activation->user_id);

            Mail::send('emails.activation', ['data' => $user, 'code' => $code], function($message) use ($user) {
                $message->to($user->email)->subject('Account activeren');
            });

            Alert::success(
                'Er is een activatie mail naar uw e-mailadres gestuurd.'
            )
                ->persistent('Sluiten')
            ;   
        } else {
            Alert::error(
                'Uw account is al geactiveerd, of uw activatiecode werkt niet.'
            )
                ->persistent('Sluiten')
            ;   
        }

        return Redirect::to('/');
    }

    public function activateEmail($code)
    {
        $user = Sentinel::getUserRepository()->where(
            'new_email_code', $code
        )
            ->first()
        ;

        if ($user) {       
            $user->email = $user->new_email;
            $user->new_email = '';
            $user->new_email_code = '';
            $user->save();
        }

        return Redirect::to('account');
    }

    public function activatePassword($code)
    {
        $newPassword = str_random(10);

        $reminder = Reminder::where(
            'code', $code
        )
            ->where('completed', 0)
            ->first()
        ;

        if ($reminder) {       
             return view('account/new-password');
        } else {
            App::abort(404);
        }
    }

    public function activatePasswordAction(ResetPasswordRequest $request, $code)
    {
        $this->validate($request, []);

        $reminder = Reminder::where('code', $code)->first();

        if ($reminder) {  
            $user = Sentinel::findById($reminder->user_id);

            Sentinel::update($user, array('password' => $request->input('password')));
            Sentinel::login($user);

            Alert::success('Uw wachtwoord is succesvol gewijzigd.')->persistent('Sluiten');   

            return Redirect::to('/');
        } else {
            App::abort(404);
        }
    }
    public function login() 
    {
        $loginView = array(
            'view' => view('account/login')->render(),
            'success' => true
        );

        return json_encode($loginView);
    }

    public function loginAction(LoginRequest $request)
    {
        $this->validate($request, []);
     
        $credentials = array(
            'email' => $request->input('email'),
            'password' => $request->input('password')
        );

        try {
            if ($request->input('remember') == 1) {
                $auth = Sentinel::authenticateAndRemember($credentials);
            } else {
                 $auth = Sentinel::authenticate($credentials);
            }

            if ($auth == TRUE) {
                return Response::json(array('success' => 1));
            }  else {
                return Response::json(array(
                    'name' => 'Dit e-mailadres en het opgegeven wachtwoord komen niet overeen met elkaar.'
                ));
            }
        } catch (ThrottlingException $e) {
            return Response::json(array(
                'throttling' => 1
            ));
        } catch (NotActivatedException $e) {
            return Response::json(array(
                'activation' => 1
            ));
        } catch (TokenMismatchException $e) {
            return Response::json(array(
                'tokemismatch' => 1
            ));
        }
    }

    public function register() 
    {
        return view('account/register');
    }

    public function registerAction(RegisterRequest $request) 
    {
        $this->validate($request, []);

        $data = Sentinel::registerAndActivate(array(
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ));

        $data->name = $request->input('name');
        $data->gender = $request->input('gender');
        $data->phone = $request->input('phone');
        $data->expire_code = str_random(64);
        $data->expired_at = date('Y-m-d H:i', strtotime('+2 hours')).':00';
        $data->terms_active = 1;
        $data->source = app('request')->cookie('source');
        $data->save();

        $temporaryAuth = new TemporaryAuth();
        $createAuthRegister = $temporaryAuth->createCode($data->id, 'account#preferences');

        $mailtemplate = new MailTemplate();
        $mailtemplate->sendMailSite(array(
            'email' => $request->input('email'),
            'template_id' => 'register',
            'replacements' => array(
                '%name%' => $request->input('name'),
                '%email%' => $request->input('email'),
                '%date%' => date('d-m-Y'),
                '%time%' => date('H:i'),
                '%randompassword%' => '',
                '%randomPassword%' => '',
                '%url%' => url('auth/set/'.$createAuthRegister)
            )
        ));
            
        $user = Sentinel::findById($data->id);

        Sentinel::login($user);
            
        return Response::json(array(
            'success' => 1, 
            'state' => 2
        ));
    }

    public function forgotPasswordAction(ForgotPasswordRequest $request) 
    {
        $this->validate($request, []);

        $credentials = array(
            'email' => $request->input('email')
        );

        $user = Sentinel::findByCredentials($credentials);
        Reminder::create($user);

        $code = Reminder::where(
            'user_id', $user->id
        )
            ->orderBy('created_at', 'desc')
            ->first()
            ->code
        ;

        $mailtemplate = new MailTemplate();
        $mailtemplate->sendMailSite(array(
            'email' => $user->email,
            'template_id' => 'forgot_password',
            'replacements' => array(
                '%name%' => $user->name,
                '%email%' => $user->email,
                '%url%' => URL::to('activate-password/'.$code)
            )
        ));

        return Response::json(array(
            'success' => 1
        ));
    }

    public function socialLogin(Request $request, $provider) 
    {
        $providers = array(
            'facebook', 
            'google'
        );

        if (in_array($provider, $providers)) {
            if($request->input('redirect')){
                $request->session()->flash('redirectTo',  $request->input('redirect'));
            }
            else{
                $request->session()->flash('redirectTo',  '/');
            }
            
            return Socialite::driver($provider)->redirect();
        } else {
            return Redirect::to('/');
        }
    }  

    public function socialLoginInfo(Request $request, $provider) 
    {
        $request->session()->put('state', $request->input('state'));

        try {
            $user = Socialite::driver($provider)->user();

            $userCheckQuery = Sentinel::getUserRepository()
                ->where('email', $user->getEmail())
                ->first()
            ;

            if (count($userCheckQuery) == 1) {
                if (trim($userCheckQuery->facebook_id) >= '' || trim($userCheckQuery->google_id) >= '') {
                    Sentinel::loginAndRemember($userCheckQuery);
                    return Redirect::to(($request->session()->has('redirectTo') ? $request->session()->get('redirectTo') : 'tegoed-sparen'));
                }
            } else {
                // new Facebook / Google account
                $randomPassword = str_random(20);
                $credentials = array(
                   'email' => $user->getEmail(),
                   'password' => $randomPassword
                );

                $data = Sentinel::registerAndActivate($credentials);

                switch($provider) {
                    case 'facebook':
                        $data->facebook_id = $user->getId();
                        $data->source = 'facebook';
                        break;
                        
                    case 'google':
                        $data->google_id = $user->getId();
                        $data->source = 'google';
                        break;
                }

                $data->name = $user->getName();
                $data->expire_code = str_random(64);
                $data->expired_at = date('Y-m-d H:i', strtotime('+2 hours')).':00';
                $data->save();

                $temporaryAuth = new TemporaryAuth();
                $createAuthRegister = $temporaryAuth->createCode($data->id, 'tegoed-sparen');

                $mailtemplate = new MailTemplate();
                $mailtemplate->sendMailSite(array(
                    'email' => $data->email,
                    'template_id' => 'register',
                    'replacements' => array(
                        '%name%' => $data->name,
                        '%email%' => $data->email,
                        '%randomPassword%' => $randomPassword,
                        '%randompassword%' => $randomPassword,
                        '%date%' => date('d-m-Y'),
                        '%time%' => date('H:i'),
                        '%url%' => url('auth/set/'.$createAuthRegister)
                    )
                ));

                Sentinel::authenticateAndRemember($credentials);

                return Redirect::to($request->session()->has('redirectTo') ? $request->session()->get('redirectTo') : 'tegoed-sparen');
            }
        } catch (Exception $e) {
          
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            alert()->error('', 'Er ging iets mis tijdens het inloggen. Waarschijnlijk is uw sessie verlopen, probeer het alstublieft opnieuw.')->persistent('Sluiten');

            return Redirect::to('/');
        }
    }
}
