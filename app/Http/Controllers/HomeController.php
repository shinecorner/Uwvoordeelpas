<?php
namespace App\Http\Controllers;

use Alert;
use App;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Review;
use App\Models\Page;
use App\Models\Reservation;
use App\Models\Affiliate;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\SearchHistory;
use App\Models\Preference;
use App\Models\AffiliateCategory;
use App\Helpers\AffiliateHelper;
use App\Models\CompanyReservation;
use App\Models\MailTemplate;
use App\Models\Barcode;
use App\Models\News;
use App\Helpers\SmsHelper;
use App\Helpers\BrowerHelper;
use Sentinel; 
use Reminder;
use Redirect;
use Mail;
use DB;
use URL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Csv\Reader;
use Session;
use Setting;

class HomeController extends Controller 
{

    public function __construct() 
    {  
        
       $browser = new BrowerHelper(); 
       Session::set('browser',$browser->detect()->getInfo());
       $affiliateHelper = new AffiliateHelper();
      
        $this->user = Sentinel::getUser();
        
        $this->news  = News::select(
            'news.slug', 
            'news.title', 
            'news.created_at'
        )
            ->join('companies', 'news.company_id', '=', 'companies.id')
        ;

        if (
            Sentinel::check() 
            && trim($this->user->city) != '' 
            && $this->user->city != NULL 
            && $this->user->city != 'null'
        ) {
            if (is_array(json_decode($this->user->city))) {
                foreach (json_decode($this->user->city) as $option) {
                    $this->news = $this->news
                        ->orWhereRaw('companies.regio REGEXP "[[:<:]]'.$option.'[[:>:]]"')
                        ->orWhere('companies.regio', '=', $option)
                    ;
                }
            }
        }

        $this->news = $this->news->orderBy(
            'news.created_at', 'asc'
        )
            ->where('is_published', 1)
            ->limit(10)
            ->get()
        ;

        # Affiliates 
        $this->affiliates = Affiliate::select(
            '*'
        )
            ->where('no_show', 0)
            ->orderBy('clicks', 'desc')
            ->limit(18)
            ->get()
        ;
        
        foreach ($this->affiliates as $i => $affiliatesFetch) {
            $this->affiliates[$i]['comissions'] = $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations);
        }
        
    }

    public function deals(Request $request)
    {
        $deals = App\Models\ReservationOption::all();

        // Preferences cities
        $cities = Preference::where('category_id', 9)
            ->where('no_frontpage', 0)
            ->with('media')
        ;

        if ($request->cookie('user_off_regio') != null) {
            $cities = $cities->orderByRaw('id = "'. $request->cookie('user_off_regio').'" desc');
        } else {
            $cities = $cities->orderByRaw('name desc');
        }

        $cities = $cities->get();


        return view('pages/deals', [
            'user' => $this->user,
            'cities' => $cities,
            'deals' => $deals,
        ]);
    }

    public function index(Request $request) 
    {
        $deals = App\Models\ReservationOption::all();

        // Preferences cities
        $cities = Preference::where('category_id', 9)
            ->where('no_frontpage', 0)
            ->with('media')
        ;

        if ($request->cookie('user_off_regio') != null) {
            $cities = $cities->orderByRaw('id = "'. $request->cookie('user_off_regio').'" desc');
        } else {
            $cities = $cities->orderByRaw('name desc');
        }

        $cities = $cities->get();
        // Companies
        $companies = Company::select(           
            'companies.id',
            'companies.name',
            'companies.slug',
            'companies.description',
            'companies.discount',
            'companies.days',
            'companies.kitchens',
            'companies.city'
        );

        if (
            Sentinel::check()
            && $this->user->city != 'null'
            && $this->user->city != NULL
            && $this->user->city != '[""]'
        ) {
            $userCities = json_decode($this->user->city);

            if (is_array($userCities)) {
                foreach ($userCities as $userCity) {
                    $companies = $companies->orderByRaw('companies.regio REGEXP "[[:<:]]'.$userCity.'[[:>:]]" desc, companies.clicks asc');
                }
            } else {
                $companies = $companies->orderBy('companies.clicks', 'desc');
            }
        } else {
            $companies = $companies->orderBy('companies.clicks', 'desc');
        }

        if ($request->has('no_filter') == FALSE) {
            // Filter Preferences
            if ($request->input('filter') == 1 && $request->has('preference')) {
                $optionCount = count($request->input('preference'));
                foreach ($request->input('preference') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 1);
                    }

                    if (count($request->input('preference')) >= 2) {
                        $companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->preferences != 'null'
                    && $this->user->preferences != NULL
                    && $this->user->preferences != '[""]'
                ) {
                    $userPreferences = json_decode($this->user->preferences);
                    foreach (json_decode($this->user->preferences) as $option) {
                        if (count($userPreferences) >= 2) {
                            $companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Kitchens
            if ($request->input('filter') == 1 && $request->has('kitchen')) {
                foreach ($request->input('kitchen') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 2);
                    }

                    if (count($request->input('kitchen')) >= 2) {
                        $companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        $companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->kitchens != 'null'
                    && $this->user->kitchens != NULL
                    && $this->user->kitchens != '[""]'
                ) {
                    $userKitchens = json_decode($this->user->kitchens);
                    foreach ($userKitchens as $option) {
                        if (count($userKitchens) >= 2) {
                            $companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Allergies
            if ($request->input('filter') == 1 && $request->has('allergies')) {
                foreach ($request->input('allergies') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 3);
                    }

                    if (count($request->input('allergies')) >= 2) {
                        $companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check() 
                    && $this->user->allergies != 'null' 
                    && $this->user->allergies != NULL 
                    && $this->user->allergies != '[""]'
                ) {
                    $userAllergies = json_decode($this->user->allergies);
                    foreach ($userAllergies as $option) {
                        if (count($userAllergies) >= 2) {
                            $companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else  {
                            $companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Price
            if ($request->input('filter') == 1 && $request->has('price')) {
                foreach ($request->input('price') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 4);
                    }

                    if (count($request->input('price')) >= 2) {
                        $companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        $companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check() 
                    && $this->user->price != 'null' 
                    && $this->user->price != NULL 
                    && $this->user->price != '[""]'
                ) {
                    $userPrices = json_decode($this->user->price);
                    foreach ($userPrices as $option) {
                        if (count($userPrices) >= 2) {
                            $companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Discount
             if ($request->input('filter') == 1 && $request->has('discount')) {
                $companies = $companies->where(function ($query) use($request) {
                    foreach ($request->input('discount') as $key => $option) {
                        if (!$request->has('page')) {
                            $preferenceClick = new Preference();
                            $preferenceClick->addClick($option, 5);
                        }

                        if ($key == 0) {
                            $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        } else {
                            $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        }
                    }
                });
            } else {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check() 
                    && $this->user->discount != 'null'
                    && $this->user->discount != NULL
                    && $this->user->discount != '[""]'
                ) {
                    $userDiscounts = json_decode($this->user->discount);

                    $companies = $companies->where(function ($query) use($userDiscounts) {
                        foreach ($userDiscounts as $key => $option) {
                            if ($key == 0) {
                                $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            } else {
                                $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            }
                        }
                    });
                }
            }
        }

        if ($request->has('time') && $request->has('date')) {
            $companies = $companies
                ->join('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('company_reservations.date', '=', $request->input('date'));
        } 

        $companies = $companies
            ->where('no_show', 0)
            ->with('media')
        ;
        
        $countCompanies = $companies->count();
        $companies = $companies->paginate($request->input('limit', 15));
        
        foreach($companies as $company) {
            if($company->ReservationOptions()){
                foreach($company->ReservationOptions()->get() as $deal) {
                    $companyIds[] = $company->id;
                }
            }            
        }
        $reservationDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d', strtotime('+1 days'));
        
        if (isset($companyIds)) {
            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds, 
                    'date' => $reservationDate,
                    'selectPersons' => NULL
                )
            );

            $tomorrowArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds, 
                    'date' => $tomorrowDate,
                    'selectPersons' => NULL
                )
            );
        }

        if (!$request->has('no_filter') && count($companies) == 0) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.<br /> <br /><small>Klik <a href=\''.URL::to('account').'\'> hier</a> om uw criteria aan te passen.</small>')->html()->persistent('Sluiten');

            return Redirect::to('/?no_filter=1'.($request->has('mobilefilter') ? '&mobilefilter=1' : ''));
        }   

        $queryString = $request->query();
        unset($queryString['limit']);

        return view('pages/home', [
            'user' => $this->user,
            'cities' => $cities,
            'countCompanies' => $countCompanies,
            'companies' => $companies,
            'limit' => $request->input('limit', 15),
            'queryString' => $queryString,
            'news' => $this->news,
            'affiliates' => $this->affiliates,
            'reservationDate' => $reservationDate,
            'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
            'paginationQueryString' => $request->query()
        ]);
    } 

    public function review($id) 
    {
        $data = Review::select(
            'reviews.*',
            'companies.name as companySlug',
            'companies.name as companyName'
        )
            ->where('reviews.id', $id)
            ->leftJoin('companies', 'companies.id', '=', 'reviews.company_id')
            ->first()
        ;

        return view('pages/restaurant/reviews/view', [
            'review' => $data,
            'reviewModel' => new Review()
        ]);
    }

    public function searchRedirect(Request $request) 
    {
        switch ($request->input('page')) {
            case 'faq':
                $redirectTo = 'faq';
                break;
            
            case 'restaurant':
                $redirectTo = 'search';
                break;
            
            case 'saldo':
                $redirectTo = 'tegoed-sparen/search';
                break;
        }

        return Redirect::to($redirectTo.'?q='.$request->input('q'));
    }

    public function preferences(Request $request) 
    {
        $dataSearch = array(
            'filter' => 1,
            'q' => $request->input('q'),
            'date' => $request->input('date'),
            'sltime' => $request->input('sltime'),
            'persons' => $request->input('persons'),
            'preference' => $request->input('preference'),
            'kitchen' => $request->input('kitchen'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'allergies' => $request->input('allergies')
        );

        $data = array(
            'filter' => 1,
            'q' => $request->input('q'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'persons' => $request->input('persons'),
            'preference' => $request->input('preference'),
            'kitchen' => $request->input('kitchen'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'allergies' => $request->input('allergies')
        );

        if ($request->has('type') && $request->input('type') == 'home' && Sentinel::check()) {
            $user = Sentinel::getUser();
            $user->kitchens = json_encode($request->input('kitchen'));
            $user->discount = json_encode($request->input('discount'));
            $user->preferences = json_encode($request->input('preference'));
            $user->allergies = json_encode($request->input('allergies'));
            $user->save();
        }

        if ($request->has('q') || $request->has('date')) {
            return Redirect::to('search?'.http_build_query($dataSearch));
        } else {
           return Redirect::to('/?'.http_build_query($data)); 
        }
    }

    public function setLang(Request $request, $locale) 
    {
        $request->session()->put('locale', $locale);

        App::setLocale($locale);

        return Redirect::to($request->has('redirect') ? $request->input('redirect') : '/');
    }

    public function times(Request $request) 
    {
        $getTimes = CompanyReservation::getAllTimes();
        sort($getTimes);

        $disabled = array();
        foreach($getTimes as $time)
        {
            $timeCarbon = Carbon::create(date('Y', strtotime($request->input('date'))), date('m', strtotime($request->input('date'))), date('d', strtotime($request->input('date'))), date('H', strtotime($time)), date('i', strtotime($time)), 0);
            
            if(!$timeCarbon->isPast())
            {
                $disabled[] = $time;
            }
        }
        
        return view('pages/times', [
            'times' => $getTimes,
            'disabled' => $disabled
        ]);
    }

    public function faq(Request $request, $id = null, $slug = null)
    {
        if ($request->has('q')) {
            $searchHistory = new SearchHistory(); 
            $searchHistory->addTerm($request->input('q'), '/faq');  // Add to Search History
        }
        
        if ($request->has('slug') && $request->has('step')) {
            $company = Company::where('slug', $request->input('slug'))->first();

            if ($company) {
                if (Sentinel::inRole('admin') == FALSE && $company->signature_url == NULL) {
                    alert()->error('', 'U heeft nog geen handtekening opgegeven en u bent nog niet akkoord gegaan met de Algemene Voorwaarden.')->persistent('Sluiten');
                    return Redirect::to('admin/companies/update/'.$company->id.'/'.$company->slug.'?step=1');
                }
            }
        }

        $category = FaqCategory::select(
            'id',
            'name',
            'category_id'
        )
            ->where('id', '=', $id)
            ->where('slug', '=', $slug)
            ->first()
        ;

        if (count($category) == 1) {
            $categories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNull('category_id')
                ->get()
            ;

            $subcategories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNotNull('category_id')
                ->where('category_id', '=', $category->category_id)
                ->orWhere('category_id', '=', $category->id)
                ->get()
            ;

            $questions = Faq::select(
                'id',
                'title',
                'answer'
            )
                ->orderBy('clicks', 'desc')
            ;

            if ($request->has('q')) {
                $questions = $questions->where('title', 'LIKE', '%'.$request->input('q').'%');
            }

            $questions = $questions->where(function ($query) use ($id, $category) {
                if (isset($category->category_id)) {
                    $query->where('subcategory', '=', $id);
                } else {
                    $query
                        ->where('category', '=', $category->id)
                        ->orWhere('subcategory', '=', $category->id)
                    ;
                }
            })
                ->paginate(15)
            ;

            return view('pages/faq', [
                'questions' => $questions,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'slug' => $slug,
                'categoryId' => $category->category_id == null ? $category->id : $category->category_id,
            ]);
        } else {
            $categories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNull('category_id')
                ->get()
            ;

            $questions = Faq::select(
                'id',
                'title',
                'answer'
            )
                ->orderBy('clicks', 'desc')
            ;

            if ($request->has('q')) {
                $questions = $questions->where('title', 'LIKE', '%'.$request->input('q').'%');
            }

            $questions = $questions->paginate(15);

            return view('pages/faq', [
                'questions' => $questions,
                'categories' => $categories,
                'categoryId' => null
            ]);
        }
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug);

        if (Sentinel::check() == FALSE || Sentinel::check() && Sentinel::inRole('admin') == FALSE) {
            $page = $page->where('is_hidden', 0);
        }

        $page = $page->first();

        if ($page) {
            return view('pages/page', ['page' => $page]);
        } else {
            App::abort(404);
        }
    }

    public function search(Request $request)
    {   
        // Add to Search History
        $searchHistory = new SearchHistory();
        $searchHistory->addTerm($request->input('q'), '/search');

        $companiesLimit = $request->input('limit', 15);

        $companies = Company::select(           
            'companies.id',
            'companies.name',
            'companies.slug',
            'companies.description',
            'companies.discount',
            'companies.days',
            'companies.kitchens',
            'companies.city'
        );

        if (
            Sentinel::check()
            && $this->user->city != 'null'
            && $this->user->city != NULL
            && $this->user->city != '[""]'
        ) {
            $userCities = json_decode($this->user->city);

            if (is_array($userCities)) {
                foreach ($userCities as $userCity) {
                    $companies = $companies->orderByRaw('companies.regio REGEXP "[[:<:]]'.$userCity.'[[:>:]]" desc');
                }
            } else {
                $companies = $companies->orderBy('companies.created_at', 'asc');
            }
        } else {
            $companies = $companies->orderBy('companies.created_at', 'asc');
        }

        if ($request->input('filter') == 1 && $request->has('preference')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('preference') as $key => $option) {
                    $preferenceClick = new Preference();
                    $preferenceClick->addClick($option, 1);

                    if ($key == 0) {
                        $query->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }

        if ($request->has('kitchen'))  {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('kitchen') as $key => $option) {
                    if ($key == 0) {
                        $query->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }
        
        if ($request->has('allergies')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('allergies') as $key => $option) {
                    if ($key == 0) {
                        $query->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }


        if ($request->has('discount')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('discount') as $key => $option) {
                    if ($key == 0) {
                        $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                    } else {
                        $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                    }
                }
            });
        }

        if ($request->input('filter') == 1 && $request->has('price')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('price') as $key => $option) {
                    if ($key == 0) {
                        $query->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }

        if ($request->has('q')) {         
            $termDivider = str_replace(' ', '|', $request->input('q'));

            $companies->where(function ($query) use($request, $termDivider) {
                $query
                    ->where('companies.name', 'LIKE', '%'.$request->input('q').'%')
                    ->orWhere('address', 'RLIKE', $termDivider)
                    ->orWhere('zipcode', 'RLIKE', $termDivider)
                    ->orWhere('city', 'RLIKE', $termDivider)
                    ->orWhere('preferences', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('allergies', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('discount', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('sustainability', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('price', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('facilities', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                ;
            });
        }

        if ($request->has('regio')) {     
            $preferences = new Preference();
            $regio = $preferences->getRegio();
            $companies = $companies
                ->where('companies.regio', 'REGEXP', '"[[:<:]]'.$regio['regioNumber'][$request->input('regio')].'[[:>:]]"')
                ->orWhere('companies.regio', '=', $regio['regioNumber'][$request->input('regio')])
            ;
        }

        $newCompanyId = array();
        $companyId = array();

        if ($request->has('sltime')) {
            $companies = $companies
                ->join('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
                ->where('company_reservations.available_persons', 'REGEXP', '"([^"]*)'.$request->input('sltime').'([^"]*)"')
                ->groupBy('companies.id')
            ;
        } 

        $time = date('H:i', strtotime($request->input('sltime')));
        
        $reservationDate = ($request->has('date') ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'));
        $tomorrowDate = date('Y-m-d', strtotime('+1 days'));
     
        if ($request->has('sltime') && $request->has('date')) {
            if (isset($reservationTimesArray[$time])) {
                foreach ($reservationTimesArray[$time] as $key => $reservation) {
                    $newCompanyId[] = $key;
                }
            }

            if (count($newCompanyId) >= 1) {
                 $companies = $companies->whereIn('companies.id', $newCompanyId);
            }
        }

        $companies = $companies->where('no_show', 0 )->with('media');

        $countCompanies = $companies->count();
        $companies = $companies->paginate($companiesLimit);
        $queryString = $request->query();

        foreach($companies as $key => $company)  {
            $companyId[] = $company->id;
        }

        // Recommended
        if ($request->has('sltime')) {
            $recommended = Company::select(           
                'companies.id',
                'companies.name',
                'companies.slug',
                'companies.description',
                'companies.discount',
                'companies.days',
                'companies.kitchens',
                'companies.city'
            )
                ->leftJoin('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('companies.no_show', '=', 0)
                ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
            ;

             if ($request->has('q')) {         
                $termDivider = str_replace(' ', '|', $request->input('q'));

                $recommended->where(function ($query) use($request, $termDivider) {
                    $query
                        ->where('companies.name', 'LIKE', '%'.$request->input('q').'%')
                        ->orWhere('address', 'RLIKE', $termDivider)
                        ->orWhere('zipcode', 'RLIKE', $termDivider)
                        ->orWhere('city', 'RLIKE', $termDivider)
                        ->orWhere('preferences', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('allergies', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('discount', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('sustainability', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('price', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('facilities', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ;
                });
            }

            if ($request->has('sltime')) {
                $recommended = $recommended
                    ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
                ;
            } 

            $recommended = $recommended
                ->limit(10)
                ->groupBy('companies.id')
                ->whereNotIn('companies.id', $companyId)
                ->with('media')
                ->get();
            ;

            foreach($recommended as $key => $company)  {
                $companyId[] = $company->id;
            }
        } 

        $reservationTimesArray = CompanyReservation::getReservationTimesArray(
            array(
                'company_id' => $companyId, 
                'date' => $reservationDate,
               'selectPersons' => ($request->has('persons') ? $request->input('persons') : null)
            )
        );
        $tomorrowArray = CompanyReservation::getReservationTimesArray(
            array(
                'company_id' => $companyId, 
                'date' => $tomorrowDate,
                'selectPersons' => ($request->has('persons') ? $request->input('persons') : null)
            )
        );
        
        if (
            count($companies) == 0 
            OR date('Y-m-d') == date('Y-m-d', strtotime($request->input('date')))
            && date('H:i') >= date('H:i', strtotime($request->input('sltime')))
        ) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.')->persistent('Sluiten');

            return Redirect::to('/');
        }   

        return view('pages/search', [
            'companies' => $companies,
            'countCompanies' => $countCompanies,
            'recommended' => isset($recommended) ? $recommended : array(),
            'news' => $this->news,
            'times' => CompanyReservation::getAllTimes(),
            'limit' => $companiesLimit,
            'queryString' => $queryString,
            'reservationDate' => $reservationDate,
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
            'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
            'paginationQueryString' => $request->query()
        ]); 
    }

    public function createIcs(Request $request)
    {
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename='.'event-uwvoordeelpas.ics');
        
        return view('template/ics', array(
            'title' => $request->input('title'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'startdate' => $request->input('startdate'),
            'enddate' => $request->input('enddate'),
            'todaydate' => $request->input('todaydate')
        ));
    }

    public function contact(Request $request)
    {
        return view('pages/contact', array(
        ));
    }

    public function testPage(Request $request)
    {
        $mailtemplate = new MailTemplate();
		$mailtemplate->sendMail(array(
                  'email' => 'bhalodia.sandip@gmail.com',
                  'reservation_id' => 56,
                  'template_id' => 'new-reservation-company',
                  'company_id' => 157,
                  'manual' => 0,
                  'replacements' => array(
                  '%name%' => 'Kanjji Bhagat',
                  '%cname%' => 'Virlo hansraj',
                  '%saldo%' => 50,
                  '%phone%' => '951077784',
                  '%email%' => 'kanji.hardayal@gmail.com',
                  '%date%' => date('d-m-Y', strtotime('2017-10-24')),
                  '%time%' => date('H:i', strtotime('11:00')),
                  '%persons%' => 8,
                  '%comment%' => 'Kai comment nathi karvi',
                  '%discount%' => '',
                  '%discount_comment%' =>  '',
                  '%days%' => '',
                  '%allergies%' => '',
                  '%preferences%' => '',
                  )
                  ));
	 exit('success');
        return view('pages/test', array(
        ));
    }

    public function contactAction(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'content' => 'required|min:10',
            'subject' => 'required|min:5',
            'CaptchaCode' => 'required|valid_captcha'
        ]);

        Alert::success('', 'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.')->persistent('Sluiten');

        $data = array(
            'request' => $request
        );

        Mail::send('emails.contact_site', $data, function ($message) use ($request) {
            $message
                ->to('sseymor@roc-dev.com')
                ->subject($request->input('subject'))
                ->from($request->input('email'))
            ;
        });

        return Redirect::to('contact');
    }

    public function redirectTo(Request $request)
    {   
        if ($request->has('p')) {
            switch ($request->input('p')) {
                case 2:
                    Setting::set('discount.views2', Setting::get('discount.views2') + 1);
                    break;

                case 3:
                    Setting::set('discount.views3', Setting::get('discount.views3') + 1);
                    break;
                
                default:
                    Setting::set('discount.views', Setting::get('discount.views') + 1);
                    break;
            }
        }

        return Redirect::to($request->has('to') ? $request->input('to') : '/');
    }

    public function sourceRedirect(Request $request)
    {   
        $websiteSettings = json_decode(json_encode(Setting::get('website')), true);

        if (isset($websiteSettings['source'])) {
            $sources = explode(PHP_EOL, $websiteSettings['source']);

            if (is_array($sources) && in_array($request->input('source'), $sources)) {
                if ($request->has('source')) {
                    return Redirect::to('/')->withCookie(cookie('source', $request->input('source'), 44640));
                }
            } else {
                return Redirect::to('/');
            }
        }
    }
    
    public static function getPersons($option_id){
        
        $data = DB::table('reservations')->select(DB::raw('SUM(persons) as total_persons'))->where("option_id",$option_id)->get();
         
         return $data;
         
    }

}
