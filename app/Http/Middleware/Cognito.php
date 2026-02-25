<?php

namespace App\Http\Middleware;

use App\Helper\Util;
use App\Http\Controllers\AppBaseController;
use App\Models\Role;
use App\Repositories\Admin\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cognito extends AppBaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    protected $client;

    public function __construct()
    {
        $config = [
            'credentials'       => config('cognito.credentials'),
            'region'            => config('cognito.region'),
            'version'           => config('cognito.version'),
            'app_client_id'     => config('cognito.app_client_id'),
            'app_client_secret' => config('cognito.app_client_secret'),
            'user_pool_id'      => config('cognito.user_pool_id'),
        ];
        $aws    = new \Aws\Sdk($config);

        $this->client = $aws->createCognitoIdentityProvider();
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        try {
            $user = $this->client->getUser([
                'AccessToken' => $token,
            ]);

            $user = $user->toArray();

            $email           = $user['UserAttributes'][3]['Value'];
            $encrypted_email = $email;
            $user            = app(UserRepository::class)->findWhere(['email' => $encrypted_email])->first();
            if (!isset($user)) {
                return $this->sendErrorWithData(["User has been deleted"], 401);
            }
            Auth::login($user);
        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {

            return $this->sendErrorWithData([$e->getAwsErrorMessage()], 401);
        }
        return $next($request);
    }
}
