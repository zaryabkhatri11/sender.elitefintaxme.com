<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('dashboard');
Route::get('/home', 'HomeController@index')->name('dashboard');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('about', 'HomeController@index');

Route::resource('roles', 'RoleController');

Route::resource('modules', 'ModuleController');

Route::get('/module/step1/{id?}', 'ModuleController@getStep1')->name('modules.create');
Route::get('/module/step2/{tablename?}', 'ModuleController@getStep2')->name('modules.create');
Route::get('/getJoinFields/{tablename?}', 'ModuleController@getJoinFields');
Route::get('/module/step3/{tablename?}', 'ModuleController@getStep3')->name('modules.create');

Route::post('/step1', 'ModuleController@postStep1');
Route::post('/step2', 'ModuleController@postStep2');
Route::post('/step3', 'ModuleController@postStep3');


Route::resource('users', 'UserController');

Route::resource('permissions', 'PermissionController');

//Route::resource('profile', 'UserController');

Route::get('user/profile', 'UserController@profile')->name('users.profile');
//Route::patch('users/profile-update/{id}', 'UserController@profileUpdate')->name('users.profile-update');

Route::resource('languages', 'LanguageController');

Route::resource('pages', 'PageController');

Route::resource('contactus', 'ContactUsController');

Route::resource('notifications', 'NotificationController');

Route::resource('menus', 'MenuController');

//Menu #TODO need to be fixed
Route::get('statusChange/{id}', 'MenuController@statusChange');

Route::post('updateChannelPosition', 'MenuController@update_channel_position')->name('channels');
Route::resource('settings', 'SettingController');

Route::resource('email-templates', 'EmailTemplateController');

Route::resource('demands', 'DemandController');

Route::resource('user-demands', 'UserDemandController');

Route::resource('categories', 'CategoryController');

Route::resource('libraries', 'LibraryController');

Route::resource('definations', 'DefinationController');

Route::resource('webinars', 'WebinarController');

Route::resource('emails', 'EmailController');

Route::resource('videos', 'VideoController');

Route::resource('sheets', 'SheetController');

Route::resource('customers', 'CustomerController');
Route::get('customers/{id}/threads', 'CustomerController@threads')->name('customers.threads');
Route::get('customers/{id}/threads/{thread_id}', 'CustomerController@threadDetail')->name('customers.thread_detail');
Route::get('customers/{id}/emails', 'CustomerController@emails')->name('customers.emails');
Route::post('customers/{id}/send-email', 'CustomerController@sendEmail')->name('customers.send_email');


Route::resource('payment-accounts', 'PaymentAccountController');

Route::resource('merchants', 'MerchantController');

Route::resource('invoices', 'InvoiceController');

Route::resource('messages-logs', 'MessagesLogController');
Route::post('messages-logs/initiate-call', 'MessagesLogController@initiateCall')->name('messages-logs.initiate-call');

Route::get('clear-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return "All caches cleared successfully!";
})->name('clear-cache');

Route::get('test-twilio', function () {
    $sid       = config('services.twilio.sid');
    $authToken = config('services.twilio.auth_token');
    $apiKey    = config('services.twilio.api_key');
    $secret    = config('services.twilio.api_secret');

    $results = [];

    // Test 1: Using SID + Auth Token
    try {
        $client1 = new \Twilio\Rest\Client($sid, $authToken);
        $account1 = $client1->api->v2010->accounts($sid)->fetch();
        $results['auth_token_test'] = [
            'success' => true,
            'message' => 'SID + Auth Token: VALID',
            'account_name' => $account1->friendlyName
        ];
    } catch (\Exception $e) {
        $results['auth_token_test'] = [
            'success' => false,
            'message' => 'SID + Auth Token: INVALID',
            'error' => $e->getMessage()
        ];
    }

    // Test 2: Using API Key + Secret
    try {
        $client2 = new \Twilio\Rest\Client($apiKey, $secret, $sid);
        $account2 = $client2->api->v2010->accounts($sid)->fetch();
        $results['api_key_test'] = [
            'success' => true,
            'message' => 'API Key + Secret: VALID',
            'account_name' => $account2->friendlyName
        ];
    } catch (\Exception $e) {
        $results['api_key_test'] = [
            'success' => false,
            'message' => 'API Key + Secret: INVALID',
            'error' => $e->getMessage(),
            'tip' => 'If Auth Token works but API Key fails, your API Key/Secret were likely created in a different account or have a typo.'
        ];
    }

    return [
        'server_time' => now()->toDateTimeString(),
        'results' => $results
    ];
})->name('test-twilio');

