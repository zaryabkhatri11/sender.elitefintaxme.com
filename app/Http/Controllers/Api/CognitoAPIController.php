<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\ForgotPasswordCodeRequest;
use App\Http\Requests\Api\LoginAPIRequest;
use App\Http\Requests\Api\RegistrationAPIRequest;
use App\Http\Requests\Api\SocialLoginAPIRequest;
use App\Http\Requests\Api\UpdateCognitoPassword;
use App\Http\Requests\Api\VerifyCognitoCode;
use App\Jobs\SendVerificationCode;
use App\Models\Role;
use App\Repositories\Admin\SocialAccountRepository;
use App\Repositories\Admin\UDeviceRepository;
use App\Repositories\Admin\UserDetailRepository;
use App\Repositories\Admin\UserRepository;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use BlackBits\LaravelCognitoAuth\Auth\AuthenticatesUsers;
use BlackBits\LaravelCognitoAuth\CognitoClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class CognitoAPIController extends AppBaseController
{

    const NEW_PASSWORD_CHALLENGE = 'NEW_PASSWORD_REQUIRED';
    const FORCE_PASSWORD_STATUS  = 'FORCE_CHANGE_PASSWORD';
    const RESET_REQUIRED         = 'PasswordResetRequiredException';
    const USER_NOT_FOUND         = 'UserNotFoundException';
    const USERNAME_EXISTS        = 'UsernameExistsException';
    const INVALID_PASSWORD       = 'InvalidPasswordException';
    const CODE_MISMATCH          = 'CodeMismatchException';
    const EXPIRED_CODE           = 'ExpiredCodeException';
    protected $userRepository;

    protected $userDetailRepository;

    protected $uDevice;

    protected $socialAccountRepository;

    public function __construct(UserRepository $userRepo, UserDetailRepository $userDetailRepo, UDeviceRepository $uDeviceRepo, SocialAccountRepository $socialAccountRepo)
    {
        $this->userRepository          = $userRepo;
        $this->userDetailRepository    = $userDetailRepo;
        $this->uDevice                 = $uDeviceRepo;
        $this->socialAccountRepository = $socialAccountRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(RegistrationAPIRequest $request)
    {

        try {
            $attributes = [];

            $userFields = ['name', 'email'];

            foreach ($userFields as $userField) {

                if ($request->$userField === null) {
                    throw new \Exception("The configured user field $userField is not provided in the request.");
                }

                $attributes[$userField] = $request->$userField;
            }
            /* cognito registration */

            $register = app()->make(CognitoClient::class)->register($request->email, $request->password, $attributes);
            $user     = $this->userRepository->saveRecord($request);
            $this->userDetailRepository->saveRecord($user->id, $request);
            $this->userRepository->attachRole($user->id, 3);

            /* send verification code to user */
            $code = rand(1111, 9999);

            $subject = "Verification Code";
            try {
                $email = $user->email;
                $name  = $user->name;

                $check = DB::table('password_resets')->where('email', $email)->first();
                if ($check) {
                    DB::table('password_resets')->where('email', $email)->delete();
                }
//                $code = '0000';
                DB::table('password_resets')->insert(['email' => $email, 'code' => $code, 'created_at' => Carbon::now()]);
                Mail::send('email.signup', ['name' => $user->name, 'verification_code' => $code],
                    function ($mail) use ($email, $name, $subject) {
                        $mail->from(getenv('MAIL_FROM_ADDRESS'), getenv('APP_NAME'));
                        $mail->to($email, $name);
                        $mail->subject($subject);
                    });
            } catch (\Exception $e) {
                return $this->sendErrorWithData([$e->getMessage()], 403);
            }

            return $this->sendResponse($user, "User Registered Successfully.");
        } catch (CognitoIdentityProviderException $e) {
            return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
        }
    }

    function encodeString($input)
    {
        return hash('sha256', $input);
    }

    public function socialLogin(SocialLoginAPIRequest $request)
    {

        $request2 = $request;
        $hash     = $this->encodeString($request->email);

        $request->merge(['password' => $hash . '%RES']);
        $user    = false;
        $input   = $request->all();
        $account = $this->socialAccountRepository->where(['platform' => $input['platform'], 'secret_hash' => $hash, 'deleted_at' => null])->first();


        if ($account) {
//            dd('i am here');
            // Account found. generate token;
            $user = $account->user;


//             loging in
            try {


                $user = $this->userRepository->findWhere(['email' => $request->email])->first();
                if (empty($user)) {
                    return $this->sendErrorWithData(["User not found"], 404);
                }

                $token = app()->make(CognitoClient::class)->authenticate($request->email, $request->password);

                return $this->respondWithToken($user->id, $token->toArray(), $request);

            } catch (CognitoIdentityProviderException $e) {
                return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
            }

        } else {


            // Check if email address already exists. if yes, then link social account. else register new user.
            if (isset($input['email'])) {
                $user = $this->userRepository->findWhere(['email' => $input['email']])->first();
            }

            if (!$user) {
//              register user here in cognito
                try {
                    $attributes = [];

                    $userFields = ['name', 'email'];

                    foreach ($userFields as $userField) {

                        if ($request->$userField === null) {
                            throw new \Exception("The configured user field $userField is not provided in the request.");
                        }

                        $attributes[$userField] = $request->$userField;
                    }
                    /* cognito registration */

                    $register = app()->make(CognitoClient::class)->register($request->email, $request->password, $attributes);
                    $user     = $this->userRepository->saveRecord($request);
                    $request->request->add(['is_verified' => 1]);
                    $request->request->add(['is_social_login' => 1]);
                    $request->request->add(['is_patient' => $request->is_patient]);


                    $this->userDetailRepository->saveRecord($user->id, $request);
                    $this->userRepository->attachRole($user->id, 3);
                    $this->socialAccountRepository->saveRecord($user->id, $request, $hash);


                    /* send verification code to user */
                    try {
                        $user = $this->userRepository->findWhere(['email' => $request->email])->first();
                        if (empty($user)) {
                            return $this->sendErrorWithData(["User not found"], 404);
                        }

                        $token = app()->make(CognitoClient::class)->authenticate($request->email, $request->password);

                        $data['is_social_login'] = 1;
                        $data['is_verified']     = 1;
                        $this->userDetailRepository->saveRecord2($user->id, $data);
                        return $this->respondWithToken($user->id, $token->toArray(), $request);


                    } catch (CognitoIdentityProviderException $e) {
                        return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
                    }
                } catch (CognitoIdentityProviderException $e) {
                    return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
                }
                // Register user with only social details and no password.
                $userData             = [];
                $userData['name']     = $input['name'] ?? "user_" . $input['client_id'];
                $userData['email']    = $input['email'] ?? $input['client_id'] . '_' . $input['platform'] . '@' . config('app.name') . '.com';
                $userData['password'] = bcrypt(substr(str_shuffle(MD5(microtime())), 0, 12));
                $user                 = $this->userRepository->create($userData);

                $userDetails['user_id']    = $user->id;
                $userDetails['first_name'] = $user->name;
                $userDetails['image']      = null;

                $userDetails['image'] = isset($input['image']) ? $input['image'] : null;

                $userDetails['email_updates']   = 1;
                $userDetails['is_social_login'] = 1;
                $userDetails['is_verified']     = 1;
                $this->userDetailRepository->create($userDetails);
            } else {

                return $this->sendErrorWithData(["Email already exist"], 404);
            }
            // Add social media link to the user
            $request['is_social_login'] = 1;
            $request['is_verified']     = 1;
            $this->socialAccountRepository->saveRecord($user->id, $request);


        }

        if (isset($input['name'])) {
            if (!isset($user->name)) {
                $user->name = $input['username'];
                $user->save();
            }
        }

        if (!$token = JWTAuth::fromUser($user)) {
            return $this->sendError(['Invalid credentials, please try login again']);
        }

        return $this->respondWithToken($user->id, $token, $request);
    }


    public function login(LoginAPIRequest $request)
    {

        try {
            $user = $this->userRepository->findWhere(['email' => $request->email])->first();
            if (empty($user)) {
                return $this->sendErrorWithData(["User not found"], 404);
            }
//            if (!$user->details->is_verified) {
//                return $this->sendErrorWithData(["User email is not verified"], 400);
//            }

            $token = app()->make(CognitoClient::class)->authenticate($request->email, $request->password);

            return $this->respondWithToken($user->id, $token->toArray(), $request);

        } catch (CognitoIdentityProviderException $e) {
            return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
        }
    }

    public function forgotPassword(ForgotPasswordCodeRequest $request)
    {
        try {
            return app(AuthAPIController::class)->getForgetPasswordCode($request);
        } catch (\Exception $e) {
            return $this->sendErrorWithData([$e->getMessage()]);
        }
    }

    public function verifyCode(VerifyCognitoCode $request)
    {
        $check = DB::table('password_resets')->where('code', $request->verification_code)->where('email', $request->email)->first();
        if (isset($check)) {
            $data['email'] = $check->email;
            $data['code']  = "valid";
            $user          = $this->userRepository->findWhere(['email' => $check->email])->first();
            if ($user && $user->details->is_verified == 0) {
                $this->emailVerified($request->email);
                $this->userDetailRepository->where('user_id', $user->id)->update(['is_verified' => 1]);
            }
            return $this->sendResponse(['user' => $data], 'Verified');
        } else {
            return $this->sendErrorWithData(['Code Is Invalid'], 403);
        }
    }


    public function resendOTP(Request $request)
    {
        $mail = $request->email;
        if (!isset($mail)) {
            return $this->sendErrorWithData(["Email" => "Email address is Required."], 403);
        }
        $user = $this->userRepository->getUserByEmail($mail);
        if (!$user) {
            return $this->sendErrorWithData(["Email" => "Your email address was not found."], 403);
        }
        DB::table('password_resets')->where('email', $mail)->delete();
        $code = rand(1111, 9999);

        $subject = "Verification Code";
        try {
            $email = $user->email;
            $name  = $user->name;

            $check = DB::table('password_resets')->where('email', $email)->first();
            if ($check) {
                DB::table('password_resets')->where('email', $email)->delete();
            }

            DB::table('password_resets')->insert(['email' => $email, 'code' => $code, 'created_at' => Carbon::now()]);
            Mail::send('email.signup', ['name' => $user->name, 'verification_code' => $code],
                function ($mail) use ($email, $name, $subject) {
                    $mail->from(getenv('MAIL_FROM_ADDRESS'), getenv('APP_NAME'));
                    $mail->to($email, $name);
                    $mail->subject($subject);
                });
        } catch (\Exception $e) {
            return $this->sendErrorWithData([$e->getMessage()], 403);
        }

        return $this->sendResponse([], "OTP code sent to your email");
    }


    public function emailVerified($email)
    {
        try {
            $response = app()->make(CognitoClient::class)->setUserAttributes($email, ['email_verified' => 'true']);
            return $this->sendResponse($response, "Email verified Successfully.");

        } catch (CognitoIdentityProviderException $e) {
            return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
        }
    }

    public function setUserPassword(UpdateCognitoPassword $request)
    {
        try {
            $response = app()->make(CognitoClient::class)->setUserPassword($request->email, $request->password);
            if ($response == "passwords.password") {
                return $this->sendErrorWithData(['Invalid Password'], 400);
            } else {
                return $this->sendResponse(null, "Password updated Successfully.");
            }


        } catch (CognitoIdentityProviderException $e) {
            return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
        }
    }

    public function me()
    {
        $user = $this->userRepository->findWithoutFail(\Auth::id());
        return $this->sendResponse($user->toArray(), "My Profile");
    }

    public function refreshToken(Request $request)
    {
        try {
            $email = $request->email;
            $user  = $this->userRepository->findWhere(['email' => $email])->first();
            if (!isset($user)) {
                return $this->sendErrorWithData(['User not found'], 404);
            }
            $token  = $request->bearerToken();
            $config = [
                'credentials'       => config('cognito.credentials'),
                'region'            => config('cognito.region'),
                'version'           => config('cognito.version'),
                'app_client_id'     => config(' .app_client_id'),
                'app_client_secret' => config('cognito.app_client_secret'),
                'user_pool_id'      => config('cognito.user_pool_id'),
            ];
            $aws    = new \Aws\Sdk($config);

            $client = $aws->createCognitoIdentityProvider();

            $new_token                                         = $client->initiateAuth([
                'ClientId'       => config('cognito.app_client_id'),
                'AuthFlow'       => 'REFRESH_TOKEN_AUTH',
                'AuthParameters' => [
                    'REFRESH_TOKEN' => $token,
                    'SECRET_HASH'   => $this->hash($email . config('cognito.app_client_id'))
                ]
            ]);
            $new_token                                         = $new_token->toArray();
            $new_token['AuthenticationResult']['RefreshToken'] = $token;
            return $this->respondWithToken($user->id, $new_token, $request);
        } catch (CognitoIdentityProviderException $e) {
            return $this->sendErrorWithData([$e->getAwsErrorMessage()]);
        }
    }

    protected function hash($message)
    {
        $hash = hash_hmac(
            'sha256',
            $message,
            config('cognito.app_client_secret'),
            true
        );

        return base64_encode($hash);
    }

    public function respondWithToken($id, $token, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if ($request->has('device_token')) {
            $this->uDevice->saveRecord($user['id'], $request);
        }
        $user = array_merge($user->toArray(), $token['AuthenticationResult']);
        return $this->sendResponse($user, 'Logged in successfully');
    }


}
