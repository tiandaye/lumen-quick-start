<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Api\Helpers\Api\ExceptionReport;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        /**
         * 将方法拦截到自己的ExceptionReport
         */
        // $reporter = ExceptionReport::make($exception);

        // if ($reporter->shouldReturn()){
        //     return $reporter->report();
        // }

        // return parent::render($request, $exception);

        /**
         * 调试
         */
        // 加入跟踪调试
        // $backtrace = debug_backtrace();
        // var_dump($backtrace);

        // 错误信息
        // dd($e->getMessage());
        // dd($e instanceof OAuthServerException);
        // dd(get_class($e));

        /**
         * 自定义处理异常
         */
        // switch (true) {
        //     case $e instanceof OAuthServerException:
        //         return response('未授权.', 401);
        //         break;

        //     case $e instanceof AuthorizationException:
        //         return response('This action is unauthorized.', 403);
        //         break;

        //     case $e instanceof ModelNotFoundException:
        //         return response('The model is not found.', 404);
        //         break;

        //     default:
        //         break;
        // }

        return parent::render($request, $e);
    }
}
