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