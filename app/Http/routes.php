<?php
use App\Models\MailTemplate;
/**
 * General
 */
Route::group(array('middleware' => 'userInfo'), function () {
    Route::get('/', 'HomeController@index');
    Route::get('/deals', 'HomeController@deals');
    Route::get('test-page', 'HomeController@testPage');
    Route::get('aansluiten/callmeback', 'Admin\CompaniesCallcenterController@callMeBack');
    Route::get('contact', 'HomeController@contact');
    Route::get('review/{id}', 'HomeController@review');
    Route::get('create-ics', 'HomeController@createIcs');
    Route::get('open-menu', 'HomeController@index');
    Route::get('search', 'HomeController@search');
    Route::get('times', 'HomeController@times');
    Route::get('faq/{id?}/{slug?}', 'HomeController@faq');
    Route::get('veel-gestelde-vragen', 'HomeController@faq');
    Route::get('setlang/{lang}', 'HomeController@setLang');
    Route::get('redirect_to', 'HomeController@redirectTo');
    Route::get('captcha-handler', 'LaravelCaptcha\Controllers\CaptchaHandlerController@index');
    Route::get('a', 'HomeController@sourceRedirect');

    ## Post routes - Preferences ##
    Route::post('aansluiten/callmeback', 'Admin\CompaniesCallcenterController@callMeBackAction');
    Route::post('preferences', 'HomeController@preferences');
    Route::post('contact', 'HomeController@contactAction');
    Route::post('search-redirect', 'HomeController@searchRedirect');
});

Route::get('foor', function() {
   
    Artisan::call('daisycon:affiliate', [
    ]);
});

Route::group(array('prefix' => 'compare', 'middleware' => 'userInfo'), function () {
    Route::get('/', 'CompareController@index');
    Route::get('car', 'CompareController@car');
    Route::get('energy', 'CompareController@energy');
    Route::get('contents ', 'CompareController@contents');
    Route::get('building', 'CompareController@building');
    Route::get('law', 'CompareController@law');
    Route::get('travel', 'CompareController@travel');
    Route::get('care', 'CompareController@care');   

});
/**
 * Voordeelpas
 */
Route::group(array('prefix' => 'voordeelpas', 'middleware' => 'userInfo'), function () {
    Route::get('/', 'DiscountController@buy');
    Route::get('buy', 'DiscountController@buy');
    Route::get('buy/direct', 'DiscountController@buyDirect')->middleware(['auth']);

    ## Post routes - Buy ##
    Route::post('buy', 'DiscountController@buyAction')->middleware(['auth']);
});

/**
 * News
 */
Route::group(array('prefix' => 'news', 'middleware' => 'userInfo'), function () {
    Route::any('/', 'NewsController@index');
    Route::any('{slug}', 'NewsController@view')->where('slug', '[\-_A-Za-z0-9]+');
});

/**
 * Payments
 */
Route::group(array('prefix' => 'payment', 'middleware' => array('userInfo')), function () {
    Route::get('directory', 'PaymentController@updateDirectory');
    Route::get('mollie', 'PaymentController@testMollie');
    Route::get('success/invoice', 'PaymentController@validatePaymentInvoice');
    Route::get('success', 'PaymentController@validatePayment');
    Route::get('charge', 'PaymentController@charge')->middleware(['auth']);
    Route::get('pay', 'PaymentController@charge')->middleware(['auth']);

    Route::get('pay-invoice/pay/{invoicenumber}', 'PaymentController@invoiceToPayment')->middleware(['auth']);
    ## Post routes - Payment ##
    Route::post('pay', 'PaymentController@initiateIdealPayment');
    Route::get('rest-pay', 'PaymentController@initiateIdealPayment');
    Route::post('pay-invoice/pay', 'PaymentController@directInvoiceToPayment')->middleware(['auth']);
});

/**
 * Ajax
 */
Route::group(array('prefix' => 'ajax', 'middleware' => 'userInfo'), function() {
    Route::get('users/regio', 'AjaxController@usersSetRegio');
    Route::get('users', 'AjaxController@users')->middleware(['admin']);
    Route::get('newsletter/guests', 'AjaxController@newsletterGuests')->middleware(['admin']);
    Route::get('notifications', 'AjaxController@notifications');
    Route::get('faq', 'AjaxController@faqSearch');
    Route::get('available/time', 'AjaxController@availableTime');
    Route::get('available/dates', 'AjaxController@availableDates');
    Route::get('available/reservation', 'AjaxController@availableReservation');
    Route::get('appointments/companies', 'AjaxController@appointmentCompanies');
    Route::get('faqs', 'AjaxController@faq');
    Route::get('faq/subcategories', 'AjaxController@faqSubCategories');
    Route::get('affiliates', 'AjaxController@affiliates');
    Route::get('cashback/info', 'AjaxController@cashbackInfo');
    Route::get('cashback/subcategories', 'AjaxController@cashbackSubcategories');
    Route::get('guests/{company}', 'AjaxController@guests')->middleware(['adminowner', 'auth']);
    Route::get('companies/documents', 'AjaxController@adminCompaniesContract')->middleware(['adminowner', 'auth']);
    Route::get('companies/nearby', 'AjaxController@nearbyCompanies');
    Route::get('companies/nearby/company', 'AjaxController@nearbyCompany');
    Route::get('companies/invoices', 'AjaxController@adminCompaniesInvoices');
    Route::get('companies/users', 'AjaxController@usersCompanies');
    Route::get('companies/barcodes', 'AjaxController@barcodesCompanies')->middleware(['admin']);
    Route::get('companies', 'AjaxController@adminCompanies')->middleware(['admin']);
    Route::get('companies/owners', 'AjaxController@adminCompaniesOwners')->middleware(['admin']);
    Route::get('companies/waiters', 'AjaxController@adminCompaniesWaiters')->middleware(['admin']);
    Route::get('companies/callers', 'AjaxController@adminCompaniesCallers')->middleware(['admin']);
    Route::get('querystring/reservations', 'AjaxController@adminReservationQuery')->middleware(['waiter']);
    Route::get('querystring/guests', 'AjaxController@adminGuestsQuery')->middleware(['adminowner', 'auth']);
    Route::get('services', 'AjaxController@adminCompaniesServices')->middleware(['admin']);

    ## Post routes - Cookies ##
    Route::post('cashback/popup', 'AjaxController@cashbackPopup')->middleware(['auth']);
    Route::post('mailtemplates', 'AjaxController@mailtemplates');
    Route::post('newsletter/remove/guest', 'AjaxController@removeNewsletterGuest');
    Route::post('cookies', 'AjaxController@cookies');
    Route::post('affiliates/transfer', 'AjaxController@transferAction')->middleware(['admin']);
    Route::post('reservations/changetablenr', 'AjaxController@changeTableNumber')->middleware(['admin']);
});

/**
 * Cashback
 */
Route::group(array('prefix' => 'tegoed-sparen',  'middleware' => 'userInfo'), function() {
    Route::get('/', 'CashbackController@index');
    Route::get('company/{slug}', 'CashbackController@company');
    Route::get('category/{id}/{slug}', 'CashbackController@category');
    Route::get('favorite/{id}/{slug}', 'CashbackController@favorite')->middleware(['auth']);
    Route::get('delete-favorite/{id}/{slug}', 'CashbackController@deleteFavorite')->middleware(['auth']);
    Route::get('search', 'CashbackController@search');
});

/**
 * Reservations
 */
Route::group(array('middleware' => 'userInfo'), function() {
    Route::get('restaurant/{slug}', 'RestaurantController@index');
    Route::get('landingpage/{slug}', 'RestaurantController@landingpage');
    
    ## Post routes - Contact ##
    Route::post('contact/{slug}', 'RestaurantController@contact');

    ## Post routes - Restaurant ##
    Route::put('restaurant/reservation/{slug}', 'ReservationController@reservationAction');
    Route::post('restaurant/reservation/{slug}', 'ReservationController@reservationAction');
    Route::post('restaurant/{slug}', 'ReservationController@reservationStepOne');
    Route::post('restaurant/reviews/{slug}', 'RestaurantController@reviewsAction')->middleware(['auth']);
});

/**
 * Social login provider
 */
Route::group(array('prefix' => 'social'), function() {
    Route::get('login/{provider?}', 'Auth\AuthController@socialLogin');
    Route::get('info/{provider?}', 'Auth\AuthController@socialLoginInfo');
});

/**
 * Guests
 */
Route::group(array('middleware' => 'userInfo'), function() {
    Route::get('auth', 'Auth\AuthController@auth');
    Route::get('auth/set/{authCode}', 'Auth\AuthController@authSet');
    Route::get('register', 'Auth\AuthController@register');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::get('login', 'Auth\AuthController@login');
    Route::get('forgot-password', 'Auth\AuthController@forgotPassword');
    Route::get('activate/{code}', 'Auth\AuthController@activate');
    Route::get('activate-password/{code}', 'Auth\AuthController@activatePassword');
    Route::get('send-again/{code}', 'Auth\AuthController@sendMailAgain');

    ## Post routes - Guests ##
    Route::get('auth/remove', 'Auth\AuthController@authRemove');
    Route::post('activate-password/{code}', 'Auth\AuthController@activatePasswordAction');
    Route::post('forgot-password', 'Auth\AuthController@forgotPasswordAction');
    Route::post('register', 'Auth\AuthController@registerAction');
    Route::post('login', 'Auth\AuthController@loginAction');
});

/**
 * Reservations and widget
 */
Route::group(array('middleware' => 'userInfo'), function() {
    Route::get('restaurant/reservation/{slug}', 'ReservationController@reservationStepTwo');
    Route::get('future-deal/{slug}', 'RestaurantController@futureDeal');
    Route::post('future-deal/{slug}', 'RestaurantController@processFutureDeal');
    Route::get('widget/calendar/restaurant/{slug}', 'RestaurantController@widgetCalendar');
});

/**
 *  Account
 */
Route::group(array('middleware' => array('auth', 'userInfo')), function () {
    Route::group(array('prefix' => 'account'), function () {
        Route::get('/', 'AccountController@settings');
        Route::get('barcodes', 'AccountController@barcodes');
        Route::get('activate-email/{code}', 'AccountController@activateEmail');

        Route::get('reviews', 'AccountController@reviews');
        Route::get('reviews/edit/{id}', 'AccountController@reviewsUpdate');

        Route::get('reservations', 'AccountController@reservations');
        Route::get('reservations/{companySlug}/user/{userId}', 'AccountController@reservationsByCompany');
        Route::get('reservations/saldo/{userId?}', 'AccountController@saldo');
        Route::get('future-deals', 'AccountController@futuredeals');

        ## Post routes - Account ##
        Route::post('delete', 'AccountController@deleteAccount');
        Route::post('/', 'AccountController@settingsAction');
        Route::post('barcodes', 'AccountController@barcodeAction');
        Route::post('reservations', 'AccountController@reservationsAction');
        Route::get('reserve-futuredeal/{deal_id}', 'AccountController@reserveFutureDeal');
        Route::post('reserve-futuredeal/{deal_id}', 'AccountController@processReserveFutureDeal');
        
        Route::post('reviews', 'AccountController@reviewsDeleteAction');        
        Route::post('reviews/edit/{id}', 'AccountController@reviewsUpdateAction');

        # Favorite #
        Route::group(array('prefix' => 'favorite'), function () {
            Route::get('companies', 'FavoriteCompaniesController@index');
            Route::get('companies/add/{id}/{slug}', 'FavoriteCompaniesController@add');
            Route::get('companies/remove/{id}/{slug}', 'FavoriteCompaniesController@remove');
        });
    });

    # Reservations #
    Route::group(array('prefix' => 'reservation'), function () {
        Route::get('edit/{id}', 'ReservationController@reservationEdit');

        ## Post routes - Reservation ##
        Route::post('edit/{id}', 'ReservationController@reservationEditAction');
    });
});

/**
 *  Callcenter / Admin
 */
Route::group(array('prefix' => 'admin', 'middleware' => array('callcenter', 'auth', 'userInfo')), function () {
    # Appointments #
    Route::group(array('prefix' => 'appointments'), function () {
        Route::get('/', 'Admin\AppointmentController@index');
        Route::get('create/{slug?}', 'Admin\AppointmentController@create');
        Route::get('update/{id}', 'Admin\AppointmentController@update');

        Route::post('update/{id}', 'Admin\AppointmentController@updateAction');
        Route::post('create/{slug?}', 'Admin\AppointmentController@createAction');
        Route::post('/', 'Admin\AppointmentController@indexAction');
    });

    # Reservations #
    Route::group(array('prefix' => 'reservations'), function () {
        Route::get('saldo', 'Admin\ReservationsController@listSaldo');
    });

    # Callcenter Companies #
    Route::group(array('prefix' => 'companies/callcenter'), function () {
        Route::get('/', 'Admin\CompaniesCallcenterController@index');
        Route::get('create', 'Admin\CompaniesCallcenterController@create');
        Route::get('update/{id}/{slug}', 'Admin\CompaniesCallcenterController@update');
        Route::get('favorite/{id}', 'Admin\CompaniesCallcenterController@favorite');
        Route::get('export', 'Admin\CompaniesCallcenterController@export');
        Route::get('import', 'Admin\CompaniesCallcenterController@import');
        Route::get('contract/{id}/{slug}', 'Admin\CompaniesCallcenterController@contract');

        Route::post('import', 'Admin\CompaniesCallcenterController@importAction');
        Route::post('update/{id}/{slug}', 'Admin\CompaniesCallcenterController@updateAction');
        Route::post('create', 'Admin\CompaniesCallcenterController@createAction');
        Route::post('delete', 'Admin\CompaniesCallcenterController@deleteAction');
    });
});

/**
 *  Admin
 */
Route::group(array('prefix' => 'admin', 'middleware' => array('admin', 'auth', 'userInfo')), function () {
    # Ban #
    Route::group(array('prefix' => 'statistics'), function () {
        Route::get('reservations', 'Admin\StatisticsController@reservations');
        Route::get('search', 'Admin\StatisticsController@search');
    });

    Route::group(array('prefix' => 'bans'), function () {
        Route::get('/', 'Admin\UsersBanController@index');
        Route::get('create/{id?}', 'Admin\UsersBanController@create');
        Route::get('update/{id}', 'Admin\UsersBanController@update');

        Route::post('update/{id}', 'Admin\UsersBanController@updateAction');
        Route::post('create/{id?}', 'Admin\UsersBanController@createAction');
        Route::post('/', 'Admin\UsersBanController@indexAction');
        Route::post('delete', 'Admin\UsersBanController@deleteAction');
    });

    # Translations #
    Route::group(array('prefix' => 'translations'), function () {
        Route::get('/', 'Admin\TranslationController@getIndex');
        Route::get('view/{slug?}', 'Admin\TranslationController@getView');

        Route::post('publish/{slug?}', 'Admin\TranslationController@postPublish');
        Route::post('import/{slug?}', 'Admin\TranslationController@postImport');
    });  

    # Newsletter #
    Route::group(array('prefix' => 'newsletter'), function () {
        Route::get('/', 'Admin\NewsletterController@index');
        Route::get('guests', 'Admin\NewsletterController@guests');
        Route::get('example', 'Admin\NewsletterController@example');

        Route::post('/', 'Admin\NewsletterController@indexAction');
        Route::post('guests', 'Admin\NewsletterController@guestsAction');
        Route::post('example', 'Admin\NewsletterController@exampleAction');
    });

    # Notifications groups #
    Route::group(array('prefix' => 'notifications/groups'), function () {
        Route::get('/', 'Admin\NotificationGroupController@index');
        Route::get('create', 'Admin\NotificationGroupController@create');
        Route::get('update/{id}', 'Admin\NotificationGroupController@update');

        Route::post('update/{id}', 'Admin\NotificationGroupController@updateAction');
        Route::post('create', 'Admin\NotificationGroupController@createAction');
        Route::post('/', 'Admin\NotificationGroupController@indexAction');
    });

    # Notifications #
    Route::group(array('prefix' => 'notifications'), function () {
        Route::get('/', 'Admin\NotificationController@index');
        Route::get('create', 'Admin\NotificationController@create');
        Route::get('update/{id}', 'Admin\NotificationController@update');

        Route::post('update/{id}', 'Admin\NotificationController@updateAction');
        Route::post('create', 'Admin\NotificationController@createAction');
        Route::post('/', 'Admin\NotificationController@indexAction');
    });

    # Settings  #
    Route::group(array('prefix' => 'settings'), function () {
        Route::get('/', 'Admin\SettingsController@index');
        Route::get('run/{slug}', 'Admin\SettingsController@run');

        Route::post('/', 'Admin\SettingsController@indexAction');
        Route::resource('website', 'Admin\SettingsController@websiteAction');
        Route::resource('discount', 'Admin\SettingsController@discountAction');
        Route::resource('eetnu', 'Admin\SettingsController@eetnuAction');
        Route::resource('cronjobs', 'Admin\SettingsController@cronjobsAction');
        Route::resource('invoices', 'Admin\SettingsController@invoicesAction');
		Route::resource('newsletter', 'Admin\SettingsController@newsletterAction');
    });

    # Transactions #
    Route::group(array('prefix' => 'transactions'), function () {
        Route::get('/', 'Admin\TransactionsController@index');
        Route::get('update/{slug}', 'Admin\TransactionsController@update');
        Route::get('create', 'Admin\TransactionsController@create');

        Route::post('/', 'Admin\TransactionsController@indexAction');
        Route::post('create', 'Admin\TransactionsController@createAction');
        Route::post('update/{slug}', 'Admin\TransactionsController@updateAction');
    });   

    # Reservations #
    Route::group(array('prefix' => 'reservations'), function () {
        Route::get('clients', 'Admin\ReservationsController@listClients');
        Route::post('clients', 'Admin\ReservationsController@listClientsAction');
    });

    # Incassos #
    Route::group(array('prefix' => 'incassos'), function () {
        Route::get('/', 'Admin\IncassosController@index');
        Route::get('download/{incasso_id}', 'Admin\IncassosController@downloadXml');
        Route::get('generate/incasso', 'Admin\IncassosController@generateIncasso');
    });

    # Payments #
    Route::group(array('prefix' => 'payments'), function () {
        Route::get('/', 'Admin\PaymentsController@index');
        Route::get('update/{id}', 'Admin\PaymentsController@update');

        Route::post('/', 'Admin\PaymentsController@indexAction');
        Route::post('update/{id}', 'Admin\PaymentsController@updateAction');
    });

    # Widgets #
    Route::get('widgets', 'Admin\CompaniesController@widgetsIndex');

    # News #
    Route::group(array('prefix' => 'news'), function () {
        Route::get('create', 'Admin\NewsController@create');
        Route::post('create', 'Admin\NewsController@createAction');
    });

    # Mail templates #
    Route::group(array('prefix' => 'mailtemplates'), function () {
        Route::get('/', 'Admin\MailTemplatesController@index');
        Route::get('settings', 'Admin\MailTemplatesController@settings');

        Route::post('settings', 'Admin\MailTemplatesController@settingsAction');
    });

    # Services #
    Route::group(array('prefix' => 'services'), function () {
        Route::get('create', 'Admin\ServicesController@create');
        Route::get('update/{id}', 'Admin\ServicesController@update');

        Route::post('create', 'Admin\ServicesController@createAction');
        Route::post('update/{id}', 'Admin\ServicesController@updateAction');
        Route::post('delete', 'Admin\ServicesController@deleteAction');

        Route::get('/{slug?}', 'Admin\ServicesController@index');
    });

    # User #
    Route::group(array('prefix' => 'users'), function () {
        Route::get('/', 'Admin\UsersController@index');
        Route::get('create', 'Admin\UsersController@create');
        Route::get('update/{id}', 'Admin\UsersController@update');
        Route::get('login/{id}', 'Admin\UsersController@login');
        Route::get('saldo/reset/{id}', 'Admin\UsersController@resetSaldo');

        Route::post('create', 'Admin\UsersController@createAction');
        Route::post('update/{id}', 'Admin\UsersController@updateAction');
        Route::post('delete', 'Admin\UsersController@deleteAction');
    });

    # Roles #
    Route::group(array('prefix' => 'roles'), function () {
        Route::get('/', 'Admin\RolesController@index');
        Route::get('create', 'Admin\RolesController@create');
        Route::get('update/{id}', 'Admin\RolesController@update');

        Route::post('update/{id}', 'Admin\RolesController@updateAction');
        Route::post('create', 'Admin\RolesController@createAction');
        Route::post('delete', 'Admin\RolesController@deleteAction');
    });

    # Companies #
    Route::group(array('prefix' => 'companies'), function () {
        Route::get('/', 'Admin\CompaniesController@index');
        Route::get('create', 'Admin\CompaniesController@create');
        Route::get('login/{slug}', 'Admin\CompaniesController@login');

        Route::post('create', 'Admin\CompaniesController@createAction');
        Route::post('delete', 'Admin\CompaniesController@deleteAction');
    });

    # Subcategories #
    Route::group(array('prefix' => 'subcategories'), function () {
        Route::get('/', 'Admin\SubCategoryController@index');
        Route::get('create', 'Admin\SubCategoryController@create');
        Route::get('update/{id}', 'Admin\SubCategoryController@update');
        Route::get('merge', 'Admin\SubCategoryController@merge');

        Route::post('merge', 'Admin\SubCategoryController@mergeAction');
        Route::post('create', 'Admin\SubCategoryController@createAction');
        Route::post('update/{id}', 'Admin\SubCategoryController@updateAction');
        Route::post('delete', 'Admin\SubCategoryController@deleteAction');
    });

    # Categories #
    Route::group(array('prefix' => 'categories'), function () {
        Route::get('/', 'Admin\CategoryController@index');
        Route::get('create', 'Admin\CategoryController@create');
        Route::get('update/{id}', 'Admin\CategoryController@update');
        Route::get('merge', 'Admin\CategoryController@merge');
        Route::get('delete/image/{id}/{image}', 'Admin\CategoryController@deleteImage');

        Route::post('merge', 'Admin\CategoryController@mergeAction');
        Route::post('create', 'Admin\CategoryController@createAction');
        Route::post('update/{id}', 'Admin\CategoryController@updateAction');
        Route::post('delete', 'Admin\CategoryController@deleteAction');
    });

    # Affiliate #
    Route::group(array('prefix' => 'affiliates'), function () {
        Route::get('/', 'Admin\AffiliatesController@index');
        Route::get('create', 'Admin\AffiliatesController@create');
        Route::get('update/{id}', 'Admin\AffiliatesController@update');

        Route::post('create', 'Admin\AffiliatesController@createAction');
        Route::post('update/{id}', 'Admin\AffiliatesController@updateAction');
        Route::post('delete', 'Admin\AffiliatesController@deleteAction');
    });
    
    # Pages #
    Route::group(array('prefix' => 'pages'), function () {
        Route::get('/', 'Admin\PagesController@index');
        Route::get('create', 'Admin\PagesController@create');
        Route::get('update/{id}', 'Admin\PagesController@update');

        Route::post('create', 'Admin\PagesController@createAction');
        Route::post('update/{id}', 'Admin\PagesController@updateAction');
        Route::post('delete', 'Admin\PagesController@deleteAction');
    });

    # Barcodes #
    Route::group(array('prefix' => 'barcodes'), function () {
        Route::get('/', 'Admin\BarcodesController@index');
        Route::get('create', 'Admin\BarcodesController@create');
        Route::get('update/{id}', 'Admin\BarcodesController@update');

        Route::post('create', 'Admin\BarcodesController@createAction');
        Route::post('update/{id}', 'Admin\BarcodesController@updateAction');
        Route::post('delete', 'Admin\BarcodesController@deleteAction');
    });

    # Content blocks #
    Route::group(array('prefix' => 'contents'), function () {
        Route::get('/', 'Admin\ContentsController@index');
        Route::get('create', 'Admin\ContentsController@create');
        Route::get('update/{id}', 'Admin\ContentsController@update');

        Route::post('create', 'Admin\ContentsController@createAction');
        Route::post('update/{id}', 'Admin\ContentsController@updateAction');
        Route::post('delete', 'Admin\ContentsController@deleteAction');
    });

    # FAQ # 
    Route::group(array('prefix' => 'faq'), function () {
        Route::get('/', 'Admin\FaqController@index');
        Route::get('create', 'Admin\FaqController@create');
        Route::get('update/{id}', 'Admin\FaqController@update');

        Route::post('create', 'Admin\FaqController@createAction');
        Route::post('update/{id}', 'Admin\FaqController@updateAction');
        Route::post('delete', 'Admin\FaqController@deleteAction');
    });

    # FAQ # 
    Route::group(array('prefix' => 'faq/categories'), function () {
        Route::get('/', 'Admin\FaqCategoryController@indexParent');
        Route::get('create/parent', 'Admin\FaqCategoryController@createParent');
        Route::get('update/child/{id}', 'Admin\FaqCategoryController@updateChild');
        Route::get('update/parent/{id}', 'Admin\FaqCategoryController@updateParent');
        Route::get('children', 'Admin\FaqCategoryController@indexChild');
        Route::get('create/child', 'Admin\FaqCategoryController@createChild'); 

        Route::post('create/child', 'Admin\FaqCategoryController@createChildAction');
        Route::post('create/parent', 'Admin\FaqCategoryController@createParentAction');
        Route::post('update/parent/{id}', 'Admin\FaqCategoryController@updateParentAction');
        Route::post('update/child/{id}', 'Admin\FaqCategoryController@updateChildAction');
        Route::post('delete', 'Admin\FaqCategoryController@deleteAction');
    });

    # Preferences #
    Route::group(array('prefix' => 'preferences'), function () {
        Route::get('/', 'Admin\PreferencesController@index');
        Route::get('create', 'Admin\PreferencesController@create');
        Route::get('update/{id}', 'Admin\PreferencesController@update');

        Route::post('create', 'Admin\PreferencesController@createAction');
        Route::post('update/{id}', 'Admin\PreferencesController@updateAction');
        Route::post('delete', 'Admin\PreferencesController@deleteAction');
    });

    # Reviews #
    Route::group(array('prefix' => 'reviews'), function () {
        Route::get('/', 'Admin\ReviewsController@index');

        ## Post routes - Reviews ##
        Route::post('update', 'Admin\ReviewsController@updateAction');
    });

    # Reservations #
    Route::group(array('prefix' => 'reservations'), function () {
        Route::get('emails', 'Admin\ReservationsController@emails');
        
        Route::post('emails', 'Admin\ReservationsController@emailsAction');
        Route::post('date/update', 'Admin\ReservationsController@dateUpdateAction');
    });

    # Invoices #
    Route::group(array('prefix' => 'invoices'), function () {
        Route::get('/', 'Admin\InvoicesController@index');
        Route::get('create', 'Admin\InvoicesController@create');
        Route::get('update/{id}', 'Admin\InvoicesController@update');
        Route::get('setpaid', 'Admin\InvoicesController@setPaid');
        Route::get('send/{id}', 'Admin\InvoicesController@sendInvoice');

        Route::post('update/{id}', 'Admin\InvoicesController@updateAction');
        Route::post('action', 'Admin\InvoicesController@invoicesAction');
        Route::post('create', 'Admin\InvoicesController@createAction');
    });
});

/**
 *  Waiter / Admin / Company
 */
Route::group(array('prefix' => 'admin', 'middleware' => array('waiter', 'auth', 'userInfo')), function () {
    # Reviews #
    Route::get('reviews/{slug}', 'Admin\ReviewsController@index');

    # Reservations #
    Route::group(array('prefix' => 'reservations'), function () {
        Route::get('edit-status/{reservationId}', 'Admin\ReservationsController@statusUpdate');
        Route::get('update/{company}/{date?}', 'Admin\ReservationsController@update');
        Route::get('date/{company}/{date}', 'Admin\ReservationsController@listDate');
        Route::get('saldo/{company?}', 'Admin\ReservationsController@listSaldo');
        Route::get('clients/{company}/{date?}', 'Admin\ReservationsController@listClients');
        Route::get('{slug?}', 'Admin\ReservationsController@index');
        Route::get('create/{company?}', 'Admin\ReservationsController@create');

        ## Post routes - Reservations ##
        Route::post('create/{company?}', 'Admin\ReservationsController@createAction');
        Route::post('update/{company}/{date?}', 'Admin\ReservationsController@updateAction');
        Route::post('{company?}/date/update', 'Admin\ReservationsController@dateUpdateAction');
        Route::post('clients', 'Admin\ReservationsController@listClientsAction');
        Route::post('clients/{company}/{date?}', 'Admin\ReservationsController@listClientsAction');
        Route::post('date/{company}/{date}/time/update', 'Admin\ReservationsController@timeUpdateAction');
    });

    # Reservations #
    Route::group(array('prefix' => 'reservations-options'), function () {
        Route::get('update/{id}', 'Admin\ReservationsOptionsController@update');
        Route::get('create/{company?}', 'Admin\ReservationsOptionsController@create');
        Route::get('{company?}', 'Admin\ReservationsOptionsController@index');

        Route::post('update/{id}', 'Admin\ReservationsOptionsController@updateAction');
        Route::post('create/{company?}', 'Admin\ReservationsOptionsController@createAction');
        Route::post('{company?}', 'Admin\ReservationsOptionsController@indexAction');
    });
});

/**
 *  Admin / Company
 */
Route::group(array('prefix' => 'admin', 'middleware' => array('adminowner', 'auth', 'userInfo')), function () {
    # Guests #
    Route::group(array('prefix' => 'guests'), function () {
        Route::get('{slug}', 'Admin\GuestsController@index');
        Route::get('export/{slug}', 'Admin\GuestsController@exportGuests');
        Route::get('import/{slug}', 'Admin\GuestsController@importGuests');
        Route::get('create/{slug}', 'Admin\GuestsController@create');
        Route::get('create-reservation/{slug}', 'Admin\GuestsController@createReservation');

        Route::post('import/{slug}', 'Admin\GuestsController@importGuestsAction');
        Route::post('create-reservation/{slug}', 'Admin\GuestsController@createReservationAction');
        Route::post('create/{slug}', 'Admin\GuestsController@createAction');
        Route::post('delete/{slug}', 'Admin\GuestsController@deleteAction');
    });

    # Barcodes #
    Route::group(array('prefix' => 'barcodes'), function () {
        Route::get('{slug}', 'Admin\BarcodesController@company');
    });

    # Invoices #
    Route::group(array('prefix' => 'invoices'), function () {
        Route::get('download/{id}', 'Admin\InvoicesController@downloadInvoice');
        Route::get('overview/{slug}', 'Admin\InvoicesController@index');
    });

    # Companies #
    Route::group(array('prefix' => 'companies'), function () {
        Route::get('update/{id}/{slug}', 'Admin\CompaniesController@update');
        Route::get('crop/image/{slug}/{image}', 'Admin\CompaniesController@cropImage');
        Route::get('delete/image/{slug}/{image}', 'Admin\CompaniesController@deleteImage');
        Route::get('contract/{id}/{slug}', 'Admin\CompaniesController@contract');

        Route::post('crop/image/{slug}/{image}', 'Admin\CompaniesController@cropImageAction');
        Route::post('update/{id}/{slug}', 'Admin\CompaniesController@updateAction');
    });

    # Widgets #
    Route::get('widgets/{slug}', 'Admin\CompaniesController@widgets');

    # News #
    Route::group(array('prefix' => 'news'), function () {
        Route::get('{slug?}/create', 'Admin\NewsController@create');
        Route::get('update/{id}', 'Admin\NewsController@update');
        Route::get('{slug?}', 'Admin\NewsController@index');

        Route::post('{slug?}/create', 'Admin\NewsController@createAction');
        Route::post('update/{id}', 'Admin\NewsController@updateAction');
        Route::post('delete', 'Admin\NewsController@deleteAction');
    });

    # Mail templates #
    Route::group(array('prefix' => 'mailtemplates'), function () {
        Route::get('create/{slug?}', 'Admin\MailTemplatesController@create');
        Route::get('update/{id}', 'Admin\MailTemplatesController@update');
        Route::get('{slug}', 'Admin\MailTemplatesController@indexCompany');

        Route::post('create/{slug?}', 'Admin\MailTemplatesController@createAction');
        Route::post('update/{id}', 'Admin\MailTemplatesController@updateAction');
        Route::post('delete/{slug?}', 'Admin\MailTemplatesController@deleteAction');
        Route::post('search', 'Admin\MailTemplatesController@searchAction');
    });

    # Reviews #
    Route::post('reviews/update/{company}', 'Admin\ReviewsController@updateAction');
});

/**
 *  Pages
 */
Route::any('{slug}', 'HomeController@page')->where('slug', '[\-_A-Za-z0-9]+')->middleware(array('userInfo'));

/**
 * Ajax
 */
//Route::get('public/images/signatures/{filename}png', 'FileController@getFile')->where('filename', '(.*)');


/* Soufyane Kaddouri - TodayDevelopment - API */


Route::group(['prefix' => 'api'], function () {
    Route::get('/users/{key}', 'ApiController@getUsers');
    Route::get('/affiliates/{key}', 'ApiController@getAffiliates');
    Route::get('/afflinks', 'ApiController@getAffiliatesUrl');
    Route::get('affiliates/find/{userid}/{url}', 'ApiController@findProgram');
    Route::get('extension-saldo', 'ApiController@saldoForExtension');
    Route::get('auth', 'ApiController@checkAuth');
});

Route::get('/development12345', 'DevelopmentController@index');

Route::get('/dev/viewdata', 'DevelopmentController@viewdata');
Route::post('/dev/rundata', 'DevelopmentController@rundata');




