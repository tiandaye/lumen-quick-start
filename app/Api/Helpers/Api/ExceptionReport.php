<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-08 14:12:55
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-08 14:58:49
 */

namespace App\Api\Helpers\Api;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Http\Request;

class ExceptionReport
{
    use ApiResponse;

    /**
     * @var Exception
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Exception $exception
     */
    public function __construct(Request $request, Exception $exception)
    {
        $this->request   = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */
    public $doReport = [
        // OAuthServerException:: => ['未授权', 401],
        AuthenticationException::class => ['未授权', 401],
        ModelNotFoundException::class  => ['该模型未找到', 404],
    ];

    /**
     * @return bool
     */
    public function shouldReturn()
    {

        if (!($this->request->wantsJson() || $this->request->ajax())) {
            return false;
        }

        foreach (array_keys($this->doReport) as $report) {

            if ($this->exception instanceof $report) {

                $this->report = $report;
                return true;
            }
        }

        return false;

    }

    /**
     * @param Exception $e
     * @return static
     */
    public static function make(Exception $e)
    {

        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {

        $message = $this->doReport[$this->report];

        return $this->failed($message[0], $message[1]);

    }
}