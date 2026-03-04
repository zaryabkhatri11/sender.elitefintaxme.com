<?php

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Images Resize Route
Route::get('/resize/{img}', function ($img) {

    ob_end_clean();
    try {
        $w = request()->get('w');
        $h = request()->get('h');
        $crop = request()->get('crop', false);
        $method = ($crop) ? "fit" : "resize";
        if ($h && $w) {
            // Check if file exists in storage
            $path = storage_path("app/$img");
            if (!file_exists($path)) {
                // Fallback to public folder if not in storage
                $path = public_path($img);
            }

            if (!file_exists($path)) {
                return abort(404, "Image not found: $path");
            }

            return Image::make($path)->$method($w, $h, function ($c) {
                $c->upsize();
                $c->aspectRatio();
            })->response('png');
        } else {
            $path = storage_path("app/$img");
            if (!file_exists($path)) {
                $path = public_path($img);
            }
            return response(file_get_contents($path))
                ->header('Content-Type', 'image/png');
        }

    } catch (\Exception $e) {
        //        dd($e->getMessage());
        return abort(404, $e->getMessage());
    }
})->name('resize')->where('img', '(.*)');


/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

## No Token Required
Route::post('v1/register', 'AuthAPIController@register')->name('register');

Route::post('v1/login', 'AuthAPIController@login')->name('login');
Route::post('v1/social_login', 'AuthAPIController@socialLogin')->name('socialLogin');

Route::get('v1/forget-password', 'AuthAPIController@getForgetPasswordCode')->name('forget-password');
//Route::post('v1/resend-code', 'AuthAPIController@resendCode');
Route::post('v1/verify-reset-code', 'AuthAPIController@verifyCode')->name('verify-code');
Route::post('v1/reset-password', 'AuthAPIController@updatePassword')->name('reset-password');
Route::resource('v1/settings', 'SettingAPIController');
Route::post('v1/refresh', 'AuthAPIController@refresh');


Route::post('v1/cognito-register', 'CognitoAPIController@register');
Route::post('v1/cognito-verify-code', 'CognitoAPIController@verifyCode');
Route::post('v1/cognito-resend-otp-code', 'CognitoAPIController@resendOTP');
Route::post('v1/cognito-login', 'CognitoAPIController@login');
Route::post('v1/cognito-forgot-password', 'CognitoAPIController@forgotPassword');
Route::post('v1/cognito-reset-password', 'CognitoAPIController@setUserPassword');
Route::post('v1/cognito-refresh-token', 'CognitoAPIController@refreshToken');
Route::post('v1/cognito-social-login', 'CognitoAPIController@socialLogin');


Route::middleware('cognito-auth:api')->group(function () {
    ## Token Required to below APIs
    Route::post('v1/logout', 'AuthAPIController@logout');

    Route::post('v1/account-setup', 'UserAPIController@accountSetup');

    Route::post('v1/me', 'AuthAPIController@me');

    Route::resource('v1/users', 'UserAPIController');

    Route::resource('v1/roles', 'RoleAPIController');
    Route::resource('v1/permissions', 'PermissionAPIController');

    Route::resource('v1/languages', 'LanguageAPIController');

    Route::resource('v1/pages', 'PageAPIController');

    Route::resource('v1/contactus', 'ContactUsAPIController');

    Route::resource('v1/notifications', 'NotificationAPIController');

    Route::resource('v1/menus', 'MenuAPIController');

    Route::get('v1/mark-all-read', 'NotificationAPIController@markAllRead');

});

Route::resource('v1/email-templates', 'EmailTemplateAPIController');

Route::get('v1/get-emails', 'EmailAPIController@getMail');
Route::resource('v1/emails', 'EmailAPIController');

Route::resource('v1/videos', 'VideoAPIController');

Route::resource('v1/sheets', 'SheetAPIController');

Route::resource('v1/customers', 'CustomerAPIController');

// Webhook for incoming email replies
Route::post('v1/emails/reply', 'EmailReplyController@handle')->name('emails.reply');

Route::resource('v1/payment-accounts', 'PaymentAccountAPIController');

Route::resource('v1/merchants', 'MerchantAPIController');

Route::resource('v1/invoices', 'InvoiceAPIController');

// Webhooks
Route::post('v1/webhooks/paypal', 'WebhookController@paypal')->name('webhooks.paypal');
Route::post('v1/webhooks/square', 'WebhookController@square')->name('webhooks.square');