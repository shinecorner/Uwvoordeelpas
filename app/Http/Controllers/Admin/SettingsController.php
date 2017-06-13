<?php
namespace App\Http\Controllers\Admin;
use Mail;
use App;
use Alert;
use App\Http\Controllers\Controller;
use App\Models\CompanyService;
use App\Models\Company;
use App\Models\Invoice;
use Config;
use Sentinel;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Exception\NotReadableException;
use Redirect;
use Setting;

class SettingsController extends Controller 
{

    public function __construct(Request $request)
    {
       	$this->slugController = 'settings';
       	$this->section = 'Website instellingen';
    }

    public function index(Request $request)
    {   
        $websiteSettings = json_decode(json_encode(Setting::get('website')), true);
        $discountSettings = json_decode(json_encode(Setting::get('discount')), true);
        $cronjobSettings = json_decode(json_encode(Setting::get('cronjobs')), true);
        $apiSettings = json_decode(json_encode(Setting::get('settings')), true);
        $kitchensSettings = json_decode(json_encode(Setting::get('filters')), true);
        $invoicesSettings = json_decode(json_encode(Setting::get('default')), true);

        $kitchens = Config::get('preferences.kitchens');
        $cities = array_values(Config::get('preferences.cities'));

        sort($cities);

        foreach ($cities as $key => $city) {
            $citiesArray[str_slug($city)] = $city;
        }

        return view('admin/'.$this->slugController.'/index', [
            'cities' => $citiesArray,
            'kitchens' => $kitchens,
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'Overzicht', 
            'kitchensSettings' => $kitchensSettings,
            'cronjobSettings' => $cronjobSettings,
            'apiSettings' => $apiSettings,
            'discountSettings' => $discountSettings,
            'invoicesSettings' => $invoicesSettings,
            'websiteSettings' => $websiteSettings
        ]);
    }

    public function indexAction(Request $request)
    {   
        $requests = $request->all();
        unset($requests['_token']);

        $settingsArray = array(
            'affilinet_name',
            'affilinet_pw',
            'daisycon_name',
            'daisycon_pw',
            'tradetracker_name',
            'tradetracker_pw',
            'tradedoubler_name',
            'tradedoubler_pw',
            'zanox_name',
            'zanox_pw',
            'hotspot_pw',
            'callcenter_reminder',
            'callcenter_reminder_status'
        );

        foreach ($requests as $key => $value) {
            if (in_array($key, $settingsArray)) {
                Setting::set('settings.'.$key, $value);
            }
        }

        Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }

    public function cronjobsAction(Request $request)
    {   
        $requests = $request->all();
        unset($requests['_token']);

        Setting::forget('cronjobs');

        $settingsArray = array(
            'affilinet_name',
            'affilinet_pw',
            'daisycon_name',
            'daisycon_pw',
            'tradetracker_name',
            'tradetracker_pw',
            'tradedoubler_name',
            'tradedoubler_pw',
            'zanox_name',
            'zanox_pw',
            'hotspot_pw',
            'callcenter_reminder',
            'callcenter_reminder_status',
        );

        foreach ($requests as $key => $value) {
            Setting::set('cronjobs.'.$key, 1);
        }

        Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }

    public function invoicesAction(Request $request)
    {   
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('default.services_noshow', $request->input('services_noshow'));
            Setting::set('default.services_name', $request->input('name'));
            Setting::set('default.services_price', $request->input('price'));
            Setting::set('default.services_tax', $request->input('tax'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function websiteAction(Request $request)
    {   
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('website.facebook', $request->input('facebook'));
            Setting::set('website.source', $request->input('source'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function eetnuAction(Request $request)
    {   
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('filters.kitchens', json_encode($request->input('kitchens')));

            if (trim($request->input('cities')) != '') {
                Setting::set('filters.cities', json_encode(array_values($request->input('cities'))));
            } else {
                Setting::set('filters.cities', '');
            }

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function discountAction(Request $request)
    {   
        if ($request->isMethod('post')) {
            $requests = $request->all();

            $files = array(
                'discount_image',
                'discount_image2',
                'discount_image3',
                'discount_image4',
            );

            if ($request->has('remove_image2')) {
                Setting::forget('discount.discount_image2');
            }

            if ($request->has('remove_image2')) {
                Setting::forget('discount.discount_image3');
            }

            foreach ($files as $id => $file) {
                if ($request->hasFile($file)) {
                    try {
                        ImageManagerStatic::make($request->file($file))->save(public_path('images/voordeelpas.'.$request->file($file)->getClientOriginalExtension()));
                    } catch (NotReadableException $e) {
                    }

                    Setting::set('discount.'.$file, 'images/voordeelpas.'.$request->file($file)->getClientOriginalExtension());
                }
            }

            Setting::set('discount.discount_width', $request->input('discount_width'));
            Setting::set('discount.discount_width2', $request->input('discount_width2'));
            Setting::set('discount.discount_width3', $request->input('discount_width3'));
            Setting::set('discount.discount_width4', $request->input('discount_width4'));

            Setting::set('discount.discount_height', $request->input('discount_height'));
            Setting::set('discount.discount_height2', $request->input('discount_height2'));
            Setting::set('discount.discount_height3', $request->input('discount_height3'));
            Setting::set('discount.discount_height4', $request->input('discount_height4'));

            Setting::set('discount.discount_old', $request->input('discount_old'));
            Setting::set('discount.discount_new', $request->input('discount_new'));
            Setting::set('discount.discount_old3', $request->input('discount_old3'));
            Setting::set('discount.discount_new3', $request->input('discount_new3'));

            Setting::set('discount.discount_url', $request->input('discount_url'));
            Setting::set('discount.discount_url2', $request->input('discount_url2'));
            Setting::set('discount.discount_url3', $request->input('discount_url3'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function run(Request $request, $slug)
    {   
        switch ($slug) {
            case 'affilinet':
                Setting::set('cronjobs.affilinet_affiliate', 1);
                break;
            
            case 'tradetracker':
                Setting::set('cronjobs.tradetracker_affiliate', 1);
                break;

            case 'zanox':
                Setting::set('cronjobs.zanox_affiliate', 1);
                break;

            case 'daisycon':
                Setting::set('cronjobs.daisycon_affiliate', 1);
                break;

            case 'tradedoubler':
                Setting::set('cronjobs.tradedoubler_transaction', 1);
                break;

            case 'hotspot':
                Setting::set('cronjobs.wifi_guest', 1);
                break;
        }

        Alert::success('De gekozen api wordt nu uitgevoerd.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }
	
	public function newsletter(Request $request)
	{
		 if ($request->isMethod('post')) {
			 
		$data['html']='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

					
				<meta http-equiv="X-UA-Compatible" content="IE=edge"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
				<title>Email Template</title>
				<style>

   @import 
url("https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700");


   .ReadMsgBody { width: 100%; background-color: #f5f5f5; }
   .ExternalClass { width: 100%; background-color: #f5f5f5; }
   .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass
 font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
   html { width: 100%; }
   body { background-color: #f5f5f5; -webkit-text-size-adjust: none; 
-ms-text-size-adjust: none; margin: 0; padding: 0; }
   table { border-spacing: 0; border-collapse: collapse; }
   table td { border-collapse: collapse; }
   .yshortcuts a { border-bottom: none !important; }
   img { display: block !important; }
   a { text-decoration: none; color: #26c6da; }
   table[class="table800"]{width: 800px;}

   /* Media Queries */
   @media only screen and (max-width: 640px) {
    body { width: auto !important; }
    table[class="table600"] { width: 450px !important; }
    table[class="table800"] { width: 100% !important; }
    table[class="table-container"] { width: 90% !important; }
    table[class="container2-2"] { width: 47% !important; text-align: 
left !important; }
    table[class="full-width"] { width: 100% !important; text-align: 
center !important; }
    table[class="full-width-left"] { width: 90% !important; text-align: 
left !important; }
    table[class="full-width-right"] { width: 90%  !important; 
text-align: right !important; }
    img[class="img-full"] { width: 100% !important; height: auto 
!important; }
   }

   @media only screen and (max-width: 479px) {
    body { width: auto !important; }
    table[class="table600"] { width: 290px !important; }
    table[class="table800"] { width: 100% !important; }
    table[class="table-container"] { width: 82% !important; }
    table[class="container2-2"] { width: 100% !important; text-align: 
left !important; }
    table[class="full-width"] { width: 100% !important; text-align: 
center !important; }
    table[class="full-width-left"] { width: 82% !important; text-align: 
left !important; }
    table[class="full-width-right"] { width: 82%  !important; 
text-align: right !important; }
    img[class="img-full"] { width: 100% !important; }
   }

  </style>

					
				</head><body style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<!--<table data-module="module-01" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-01.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="top-nav 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="top-nav bg" bgcolor="#131619" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">
      <tbody><tr>
       <td height="15"></td>
      </tr>
      <tr>
       <td>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="left">
         <tbody><tr>
          <td width="26" align="center"><img mc:edit="top-nav icon 1" src="images/top-icon-1.png" alt=""></td>
          <td width="10" align="left"></td>
          <td data-color="top-nav text" data-size="top-nav text" data-link-color="top-nav text" data-link-size="top-nav text link" mc:edit="top-nav text link" align="left"><a href="#" style="color: #b7b7b7; font-family: "Poppins", sans-serif; font-size:12px; text-decoration:none;">+001-6868-8787</a></td>
         </tr>
        </tbody></table>

        <table height="10" width="30" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>		

        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="210" cellspacing="0" cellpadding="0" align="left">
         <tbody><tr>
          <td width="26" align="center"><img mc:edit="top-nav icon 2" src="images/top-icon-2.png" alt=""></td>
          <td width="10" align="left"></td>
          <td data-color="top-nav text" data-size="top-nav text" data-link-color="top-nav text" data-link-size="top-nav text link" mc:edit="top-nav text link" align="left"><a href="#" style="color: #b7b7b7; font-family: "Poppins", sans-serif; font-size:12px; text-decoration:none;">Your-Name@mail.com</a></td>
         </tr>
        </tbody></table>

        <table height="10" width="30" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>		

        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="left">								
         <tbody><tr>
          <td width="26" align="center"><img mc:edit="top-nav icon 3" src="images/top-icon-3.png" alt=""></td>
          <td width="10" align="left"></td>
          <td data-color="top-nav text" data-size="top-nav text" data-link-color="top-nav text" data-link-size="top-nav text link" mc:edit="top-nav text link" align="left"><a href="#" style="color: #b7b7b7; font-family: "Poppins", sans-serif; font-size:12px; text-decoration:none;">Open in browser</a></td>
         </tr>
        </tbody></table>
       </td>
      </tr>	
      <tr>
       <td height="15"></td>
      </tr>		
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>-->
  <table data-module="module-02" data-thumb="" mc:repeatable="layout" mc:hideable="" mc:variant="top-nav 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody>
   <tr>
    <td data-bgcolor="top-nav bg" bgcolor="#131619" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">
      <tbody>
	  <tr>
       <td height="15"></td>
      </tr>
      <tr>
       <td>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="left">
			<tbody>
			 <tr>
			  <td width="26" align="center"><img mc:edit="top-nav icon 1" src="images/vplogo.png" style="margin-top:5px;" alt=""></td>
			 </tr>
			</tbody>
		</table>

        <table height="10" width="30" align="left">
         <tbody>
		 <tr>
          <td></td>
         </tr>
        </tbody>
		</table>		

        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="right">								
			<tbody>
			 <tr>
			  <td data-color="top-nav text" data-size="top-nav text" data-link-color="top-nav text" data-link-size="top-nav text link" mc:edit="top-nav text link" align="left"><a href="#" style="color: #fff; font-family: "Poppins", sans-serif; font-size:16px; margin-top:12px;float:left;text-decoration:none;">Salad $5.00</a></td>
			 </tr>
			</tbody>
		</table>
       </td>
      </tr>	
      <tr>
       <td height="15"></td>
      </tr>		
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>
  <table data-module="module-04" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-04.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="about-us content 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="about-us bg" class="" bgcolor="#fbfbfb" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">	
      <tbody><tr>
       <td>
        <table>
         <tbody><tr>
          <td style="font-size: 40px; line-height: 40px;" height="40"><br></td>
         </tr>	
         <tr>
          <td width="5" bgcolor="#c5a86a"><br></td>
          <td width="10"><br></td>
          <td>				  	
           <table border="0" cellspacing="0" cellpadding="0">						
            <tbody>
			<tr>
             <td data-color="about-us title" data-size="about-us title" mc:edit="about-us title 1" style="font-family: "Poppins", sans-serif; font-size:20px; font-weight:300; color:#131619;" align="left">ABOUT OUR RESTAURANT</td>
            </tr> 
            <tr>
             <td data-color="about-us subtitle" data-size="about-us subtitle" mc:edit="about-us subtitle 1" style="font-family: "Poppins", sans-serif; font-size:12px; font-weight:500; color:#c5a86a;" align="left">Suspendisse dictum diam nulla</td>
            </tr>						
           </tbody></table>
          </td>
         </tr>		
         <tr>
          <td style="font-size: 30px; line-height: 30px;" height="30"><br></td>
         </tr>
        </tbody></table>
       </td>
      </tr>		  
      <tr>
       <td>						  
        <table class="full-width" width="290" align="right">
         <tbody><tr>
          <td><img mc:edit="about-us img 1" class="img-full" src="images/aboutus-img.png" alt=""></td>
         </tr>
        </tbody></table>
        <table height="20" width="20" align="right">
         <tbody><tr>
          <td><br></td>
         </tr>
        </tbody></table>	
        <table class="full-width" width="280" align="left">
         <tbody><tr>
          <td data-color="about-us text" data-size="about-us text" mc:edit="about-us text 1" style="font-family: "Poppins", sans-serif; font-size:12px; font-weight:400; color:#808080;" class="" align="left">Lorem
 ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lobortis 
dictum elit eu placerat. Nullam vel pellentesque tortor, nec ornare 
enim. Praesent vehicula dapibus diam non porttitor. Etiam vel elit ante.
 Phasellus eleifend sollicitudin odio a varius. Donec sit amet lacinia 
velit, ut dignissim diam. Aliquam erat volutpat.<br><br>
           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lobortis dictum elit eu placerat.</td>
         </tr>
         <tr>
          <td align="right"><img mc:edit="about-us signature 1" src="images/aboutus-signature.png" alt=""></td>
         </tr>
         <tr>
          <td data-color="about-us name" data-size="about-us name" mc:edit="about-us name 1" style="font-family: "Poppins", sans-serif; font-size:12px; font-weight:500; color:#c5a86a;" align="right">Restaurant Owner &amp; CEO</td>
         </tr>
        </tbody></table>						

       </td>
      </tr>
      <tr>
       <td style="font-size: 50px; line-height: 50px;" height="50"><br></td>
      </tr>				
     </tbody></table>				
    </td>
   </tr>
  </tbody></table><table data-module="module-05" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-05.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="offer bg 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td align="center">
     <img mc:edit="offer bg 1" class="img-full image_target" src="images/offer-bg-top.jpg" alt="">
    </td>
   </tr>
  </tbody></table><table data-module="module-06" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-06.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="offer title 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="offer-title bg" bgcolor="#c5a86a" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">	
      <tbody><tr>
       <td style="font-size:40px;line-height:40px;" height="40"></td>
      </tr>	
      <tr>
       <td>				  	
        <table align="center">						
         <tbody><tr>
          <td data-color="offer title" data-size="offer title" mc:edit="offer title 1" style="font-family: "Poppins", sans-serif; font-size:25px; font-weight:300; color:#131619;">GREAT OFFER THIS WEEK</td>
         </tr> 								
        </tbody></table>
       </td>
      </tr>		
      <tr>
       <td style="font-size:30px;line-height:30px;" height="30"></td>
      </tr>
     </tbody></table>				
    </td>
   </tr>
  </tbody></table><table data-module="module-07" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-07.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="offer content 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="offer-content bg" bgcolor="#121619" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">	

      <tbody><tr>
       <td height="40"></td>
      </tr>
      <tr>
       <td>
        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
         <tbody><tr>
          <td>
           <table class="full-width" border="0" width="300" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td align="left"><img mc:edit="offer img 1" class="img-full" src="images/offer-img-1.png" alt=""></td>
            </tr>							
           </tbody></table>			
           <table width="20" align="left">
            <tbody><tr>
             <td></td>
            </tr>
           </tbody></table>
           <!-- text -->				
           <table class="full-width-left" border="0" width="260" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer topic" data-size="offer topic" mc:edit="offer topic 1" style="font-family: "Poppins", sans-serif; font-size:20px; font-weight:700; color:#121619;">BUY 1 GET 1 FREE</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td><img src="images/offer-icon-clock.png" alt=""></td>
                <td data-color="offer date" data-size="offer date" mc:edit="offer date 1" style="font-family: "Poppins", sans-serif; font-size:10px; font-weight:500; color:#e0190f;">1 Day Only  14 January 2017</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="15"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-name" data-size="offer food-name" mc:edit="offer food-name 1" style="font-family: "Poppins", sans-serif; font-size: 16px; font-weight: 600; color:#121619;">Beef steaks on the grill</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-detail" data-size="offer food-detail" mc:edit="offer food-detail 1" style="font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 400; color: #808080;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lobortis dictum elit eu placerat.</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table align="left">
               <tbody><tr>
                <td><img src="images/offer-icon-pricetag.png" alt=""></td>
                <td data-color="offer food-price" data-size="offer food-price" mc:edit="offer food-price 1" style="font-family: "Poppins", sans-serif; font-size: 20px; font-weight: 700; color: #e0190f;">$25.00</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
           </tbody></table>
           <table>
            <tbody><tr>
             <td width="20"></td>
            </tr>
           </tbody></table>
          </td>
         </tr>	
        </tbody></table>
       </td>
      </tr>	

      <tr>
       <td height="40"></td>
      </tr>

      <tr>
       <td>
        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
         <tbody><tr>
          <td>
           <table class="full-width" border="0" width="300" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td align="left"><img mc:edit="offer img 2" class="img-full" src="images/offer-img-2.png" alt=""></td>
            </tr>							
           </tbody></table>			
           <table width="20" align="left">
            <tbody><tr>
             <td></td>
            </tr>
           </tbody></table>
           <!-- text -->								
           <table class="full-width-left" border="0" width="260" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer topic" data-size="offer topic" mc:edit="offer topic 2" style="font-family: "Poppins", sans-serif; font-size:20px; font-weight:700; color:#121619;">GET 50% OFF</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td><img src="images/offer-icon-clock.png" alt=""></td>
                <td data-color="offer date" data-size="offer date" mc:edit="offer date 2" style="font-family: "Poppins", sans-serif; font-size:10px; font-weight:500; color:#e0190f;">14 - 18 January 2017</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-name" data-size="offer food-name" mc:edit="offer food-name 2" style="font-family: "Poppins", sans-serif; font-size: 16px; font-weight: 600; color:#121619;">Hot Italian pizza</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-detail" data-size="offer food-detail" mc:edit="offer food-detail 2" style="font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 400; color: #808080;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lobortis dictum elit eu placerat.</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table align="left">
               <tbody><tr>
                <td><img src="images/offer-icon-pricetag.png" alt=""></td>
                <td data-color="offer food-price" data-size="offer food-price" mc:edit="offer food-price 2" style="font-family: "Poppins", sans-serif; font-size: 20px; font-weight: 700; color: #e0190f;"><span style="color:#808080;font-weight: 600;font-size: 14px;  text-decoration: line-through;">$50.00</span> $23.45</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
           </tbody></table>
           <table>
            <tbody><tr>
             <td width="20"></td>
            </tr>
           </tbody></table>
          </td>
         </tr>	
        </tbody></table>
       </td>
      </tr>	

      <tr>
       <td height="40"></td>
      </tr>

      <tr>
       <td>
        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
         <tbody><tr>
          <td>
           <table class="full-width" border="0" width="300" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td align="left"><img mc:edit="offer img 3" class="img-full" src="images/offer-img-3.png" alt=""></td>
            </tr>							
           </tbody></table>			
           <table width="20" align="left">
            <tbody><tr>
             <td></td>
            </tr>
           </tbody></table>
           <!-- text -->				
           <table class="full-width-left" border="0" width="260" cellspacing="0" cellpadding="0" align="left">
            <tbody><tr>
             <td height="10"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer topic" data-size="offer topic" mc:edit="offer topic 3" style="font-family: "Poppins", sans-serif; font-size:20px; font-weight:700; color:#121619;">SPECIAL FOR STUDENT</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td><img src="images/offer-icon-clock.png" alt=""></td>
                <td data-color="offer date" data-size="offer date" mc:edit="offer date 3" style="font-family: "Poppins", sans-serif; font-size:10px; font-weight:500; color:#e0190f;">14 - 18 January 2017</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="15"></td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-name" data-size="offer food-name" mc:edit="offer food-name 3" style="font-family: "Poppins", sans-serif; font-size: 16px; font-weight: 600; color:#121619;">Raspberry ice cream</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td>
              <table>
               <tbody><tr>
                <td data-color="offer food-detail" data-size="offer food-detail" mc:edit="offer food-detail 3" style="font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 400; color: #808080;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lobortis dictum elit eu placerat.</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="15"></td>
            </tr>
            <tr>
             <td>
              <table align="left">
               <tbody><tr>
                <td><img src="images/offer-icon-pricetag.png" alt=""></td>
                <td data-color="offer food-price" data-size="offer food-price" mc:edit="offer food-price 3" style="font-family: "Poppins", sans-serif; font-size: 20px; font-weight: 700; color: #e0190f;"><span style="color:#808080;font-weight: 600;font-size: 14px;  text-decoration: line-through;">$50.00</span> $23.45</td>
               </tr>
              </tbody></table>
             </td>
            </tr>
            <tr>
             <td height="10"></td>
            </tr>
           </tbody></table>
           <table>
            <tbody><tr>
             <td width="20"></td>
            </tr>
           </tbody></table>
          </td>
         </tr>	
        </tbody></table>
       </td>
      </tr>	

      <tr>
       <td height="50"></td>
      </tr>										

     </tbody></table>				
    </td>
   </tr>
  </tbody></table><table data-module="module-08" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-08.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="recommends content 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="recommends bg" bgcolor="#fbfbfb" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">
      <tbody><tr>
       <td>
        <table>
         <tbody><tr>
          <td style="font-size:40px;line-height:40px;" height="40"></td>
         </tr>	
         <tr>
          <td width="5" bgcolor="#c5a86a"></td>
          <td width="10"></td>
          <td>				  	
           <table border="0" cellspacing="0" cellpadding="0">						
            <tbody><tr>
             <td data-color="recommends title" data-size="recommends topic" mc:edit="recommends title 1" style="font-family: "Poppins", sans-serif; font-size:20px; font-weight:300; color:#131619;" align="left">CHEF RECOMMENDS</td>
            </tr> 
            <tr>
             <td data-color="recommends subtitle 1" data-size="recommends subtitle 1" mc:edit="recommends subtitle 1" style="font-family: "Poppins", sans-serif; font-size:12px; font-weight:500; color:#c5a86a;" align="left">Choose special dishes for you that reccomend by our chefs</td>
            </tr>						
           </tbody></table>
          </td>
         </tr>		
         <tr>
          <td style="font-size: 30px; line-height: 30px;" height="30"></td>
         </tr>
        </tbody></table>
       </td>
      </tr>
      <tr>          
       <td>
        <table class="full-width" width="290" align="left">
         <tbody><tr>
          <td><img mc:edit="recommends img 1" class="img-full" src="images/recommends-img-1.png" alt=""></td>
         </tr>
         <tr>
          <td height="20"></td>
         </tr>
         <tr>
          <td align="left">           			
           <table align="left">
            <tbody><tr>
             <td data-color="recommends food-name" data-size="recommends food-name" mc:edit="recommends food-name 1" style="font-family: "Poppins", sans-serif; font-size: 14px; font-weight: 500; color: #121619;" align="left">Three mexican pork</td>
            </tr>
           </tbody></table>           				  
           <table align="right">
            <tbody><tr>
             <td><img src="images/recommends-icon-pricetag.png" alt=""></td>
             <td width="5"></td>
             <td data-color="recommends food-price" data-size="recommends food-price" mc:edit="recommends food-price 1" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:700; color:#e0190f;">$25.00</td>
            </tr>
           </tbody></table>           			
          </td>
         </tr>
        </tbody></table>
        <table height="20" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>
        <table class="full-width" width="290" align="right">
         <tbody><tr>
          <td><img mc:edit="recommends img 2" class="img-full" src="images/recommends-img-2.png" alt=""></td>
         </tr>
         <tr>
          <td height="20"></td>
         </tr>
         <tr>
          <td align="left">           			
           <table align="left">
            <tbody><tr>
             <td data-color="recommends food-name" data-size="recommends food-name" mc:edit="recommends food-name 2" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:500; color:#121619;" align="left">Baked salmon garnished</td>
            </tr>
           </tbody></table>           				  
           <table align="right">
            <tbody><tr>
             <td><img src="images/recommends-icon-pricetag.png" alt=""></td>
             <td width="5"></td>
             <td data-color="recommends food-price" data-size="recommends food-price" mc:edit="recommends food-price 2" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:700; color:#e0190f;">$25.00</td>
            </tr>
           </tbody></table>           			
          </td>
         </tr>
        </tbody></table>	
       </td>          	
      </tr>
      <tr>
       <td height="20"></td>
      </tr>
      <tr>          
       <td>
        <table class="full-width" width="290" align="left">
         <tbody><tr>
          <td><img mc:edit="recommends img 3" class="img-full" src="images/recommends-img-3.png" alt=""></td>
         </tr>
         <tr>
          <td height="20"></td>
         </tr>
         <tr>
          <td align="left">           			
           <table align="left">
            <tbody><tr>
             <td data-color="recommends food-name" data-size="recommends food-name" mc:edit="recommends food-name 3" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:500; color:#121619;" align="left">Hot pasta</td>
            </tr>
           </tbody></table>           				  
           <table align="right">
            <tbody><tr>
             <td><img src="images/recommends-icon-pricetag.png" alt=""></td>
             <td width="5"></td>
             <td data-color="recommends food-price" data-size="recommends food-price" mc:edit="recommends food-price 3" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:700; color:#e0190f;">$25.00</td>
            </tr>
           </tbody></table>           			
          </td>
         </tr>
        </tbody></table>
        <table height="20" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>
        <table class="full-width" width="290" align="right">
         <tbody><tr>
          <td><img mc:edit="recommends img 4" class="img-full" src="images/recommends-img-4.png" alt=""></td>
         </tr>
         <tr>
          <td height="20"></td>
         </tr>
         <tr>
          <td align="left">           			
           <table align="left">
            <tbody><tr>
             <td data-color="recommends food-name" data-size="recommends food-name" mc:edit="recommends food-name 4" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:500; color:#121619;" align="left">Receitas de petit gateau</td>
            </tr>
           </tbody></table>           				  
           <table align="right">
            <tbody><tr>
             <td><img src="images/recommends-icon-pricetag.png" alt=""></td>
             <td width="5"></td>
             <td data-color="recommends food-price" data-size="recommends food-price" mc:edit="recommends food-price 4" style="font-family: "Poppins", sans-serif; font-size:14px; font-weight:700; color:#e0190f;">$25.00</td>
            </tr>
           </tbody></table>           			
          </td>
         </tr>
        </tbody></table>	
       </td>          	
      </tr>

      <tr>
       <td style="font-size: 50px; line-height: 50px;" height="50"></td>
      </tr>

     </tbody></table>				
    </td>
   </tr>
  </tbody></table>
  
 
  <table data-module="module-14" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-14.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="follow-us 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="follow-us bg" bgcolor="#131619" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">	
      <tbody><tr>
       <td style="font-size:30px;line-height:30px;" height="30"></td>
      </tr>	
      <tr>		
       <td data-color="follow-us title" data-size="follow-us title" mc:edit="follow-us title 1" style="font-family: "Poppins", sans-serif; font-size: 25px; font-weight: 300; color: #c5a86a;" align="center">FOLLOW US</td>
      </tr> 
      <tr>
       <td style="font-size: 15px;line-height: 15px;" height="15"></td>
      </tr>
      <tr>
       <td>										
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="center">
         <tbody><tr>
          <td>
           <table align="center">
            <tbody><tr>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us facebook 1" src="images/follow-icon-facebook.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>
             </td>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us google 1" src="images/follow-icon-googleplus.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>										
             </td>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us instagram 1" src="images/follow-icon-instagram.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>
             </td>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us twitter 1" src="images/follow-icon-twitter.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>
             </td>	
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us vimeo 1" src="images/follow-icon-vimeo.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>
             </td>	
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
               <tbody><tr>
                <td><a href="#" target="_blank"><img mc:edit="follow-us youtube 1" src="images/follow-icon-youtube.png" style="padding-right: 10px;"></a></td>
               </tr>
              </tbody></table>
             </td>		
            </tr>						      	
           </tbody></table>
          </td>
         </tr>
        </tbody></table>
       </td>
      </tr>
      <tr>
       <td style="font-size: 30px; line-height: 30px;" height="30"></td>
      </tr>
     </tbody></table>				
    </td>
   </tr>
  </tbody></table><table data-module="module-15" data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/02/24/YE0wCAN9cxXzgeSMOdHU4GJR/stampready/thumbnails/thumbnail-15.jpg" mc:repeatable="layout" mc:hideable="" mc:variant="bottom 1" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td data-bgcolor="bottom bg" bgcolor="#fbfbfb" align="center">
     <table class="table-container" border="0" width="600" cellspacing="0" cellpadding="0">
      <tbody><tr>
       <td height="10"></td>
      </tr>
      <tr>
       <td>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="280" cellspacing="0" cellpadding="0" align="left">						
         <tbody><tr>
          <td data-color="bottom copyright" data-size="bottom copyright" mc:edit="bottom copyright 1"><a href="#" style="color: #131619; font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 400; text-decoration: none;">© 2017 Health. All rights reserved.</a></td>
         </tr>
        </tbody></table>
        <table width="30" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="100" cellspacing="0" cellpadding="0" align="right">
         <tbody><tr>
          <td>
           <table align="center">
            <tbody><tr>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="right">
               <tbody><tr>
                <td data-color="bottom unsubscribe 1" data-size="bottom unsubscribe 1" mc:edit="bottom unsubscribe 1"><a href="http://www.stampready.net/dashboard/editor/sr_unsubscribe" style="color: #e0190f; font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 400; text-decoration: none;">Unsubscribe</a></td>
               </tr>
              </tbody></table>
             </td>
            </tr>
           </tbody></table>
          </td>
         </tr>
        </tbody></table>
       </td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
     </tbody></table>
    </td>
   </tr>
  </tbody></table><div id="edit_link" class="hidden" style="display: none; left: 400px; top: 1645px;">

						<!-- Close Link -->
						<div class="close_link" style="display: none;"></div>

						<!-- Edit Link Value -->
						<input id="edit_link_value" class="createlink" placeholder="Your URL" style="display: none;" type="text">

						<!-- Change Image Wrapper-->
						<div id="change_image_wrapper">

							<!-- Change Image Tooltip -->
							<div id="change_image">

								<!-- Change Image Button -->
								<p id="change_image_button">Change &nbsp; <span class="pixel_result">800 x 200</span></p>

							</div>

							<!-- Change Image Link Button -->
							<input value="" id="change_image_link" type="button">

							<!-- Remove Image -->
							<input value="" id="remove_image" type="button">

						</div>

						<!-- Tooltip Bottom Arrow-->
						<div id="tip"></div>

					</div></body></html>';	 
			 
			 
			 
		$useremail=$_POST['emailto'];
        Mail::send(['text.view'], $data, function ($message) {
       $message->from("martijn@uwvoordeelpas.nl", $name = null);
       $message->sender($useremail, $name = "martijn@uwvoordeelpas.nl");
       $message->to($useremail, $name = "uwvoordeelpas.nl");
       $message->replyTo("martijn@uwvoordeelpas.nl", "uwvoordeelpas.nl");
       $message->subject("Newsletter");
       // Get the underlying SwiftMailer message instance...
        $message->getSwiftMessage();
       });
	}

}}