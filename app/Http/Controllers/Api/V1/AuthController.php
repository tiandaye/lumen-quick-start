<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends BaseController
{
    protected $errorMessage = '账号或密码错误!';

    /**
     * [username description]
     * @return [type] [description]
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // 校验登录信息
        $validator = $this->validateLogin($request->input());
        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code'   => 422,
            ]);
            // return $this->errorBadRequest($validator);
            return $this->sendFailedLoginResponse($request);
        }

        // 账号和密码
        $credentials = $this->credentials($request);

        if (!$this->checkUser($credentials)) {
            return $this->setStatusCode(401)->failed($this->errorMessage);
        }

        return $this->access('password', $credentials);
    }

    /**
     * Handle a refresh token request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request)
    {
        return $this->access('refresh_token', [
            'refresh_token' => $request->input('refresh_token'),
        ]);
    }

    /**
     * Send request to the laravel passport.
     *
     * @param  string  $grantType
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    private function access($grantType, array $data = [])
    {
        try {
            // 合并需要发送的数据
            $data = array_merge([
                'client_id'     => config('secrets.client_id'),
                'client_secret' => config('secrets.client_secret'),
                'grant_type'    => $grantType,
            ], $data);

            $http = new Client();
            // $http->setDefaultOption('verify', false);
            $guzzleResponse = $http->post(config('app.url') . '/oauth/token', [
                'form_params' => $data,
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $this->setStatusCode(400)->failed('/oauth/token请求出错!');
        }

        $response = json_decode($guzzleResponse->getBody());

        $response = [
            'token_type'    => $response->token_type,
            'expires_in'    => $response->expires_in,
            'access_token'  => $response->access_token,
            'refresh_token' => $response->refresh_token,
        ];

        $data = [
            'data' => $response,
            'code' => $guzzleResponse->getStatusCode(),
            'status' => 'success'
        ];

        return $this->setHeaders($guzzleResponse->getHeaders())->success($response);

        // return response()->json($data, $guzzleResponse->getStatusCode(), $guzzleResponse->getHeaders());

        // $response = response()->json($response);

        // $response->setStatusCode($guzzleResponse->getStatusCode());

        // $headers = $guzzleResponse->getHeaders();

        // foreach ($headers as $headerType => $headerValue) {
        //     $response->header($headerType, $headerValue);
        // }
        // return $response;
    }

    /**
     * [validateLogin 验证登录信息]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'required' => ':attribute 为必填项',
        ], [
            'username' => '账号',
            'password' => '密码',
        ]);
    }

    /**
     * Check the given user credentials.
     *
     * @return boolean
     */
    protected function checkUser($credentials)
    {
        $user = User::where(['phone' => $credentials['username']])->first();

        if (is_null($user)) {
            $this->errorMessage = '账号不存在!';
            return false;
        }

        if (Hash::check($credentials['password'], $user->password)) {
            // 是否被禁用
            if ($user->isBanned()) {
                $this->errorMessage = '该账号被禁用!';
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $code = $request['code'];
        $msg  = $request['errors'];
        return $this->setStatusCode($code)->failed($msg);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('username', 'password');
    }
}
