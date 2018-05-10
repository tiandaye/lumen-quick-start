<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-07 16:16:53
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-08 11:02:47
 */

namespace App\Api\Helpers\Api;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * 封装返回的统一消息
 */
trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * [$headers 头部]
     * @var array
     */
    protected $headers = [];

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * [getHeaders description]
     * @return [type] [description]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        if (!empty($headers)) {
            $this->setHeaders($headers);
        }
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status($status, array $data, $code = null)
    {

        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code'   => $this->statusCode,
        ];

        $data = array_merge($status, $data);
        return $this->respond($data);

    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error')
    {
        if ($code != FoundationResponse::HTTP_BAD_REQUEST) {
            return $this->setStatusCode($code)->message($message, $status);
        }
        return $this->message($message, $status);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "success")
    {

        return $this->status($status, [
            'message' => $message,
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!")
    {

        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $status = "success")
    {

        return $this->status($status, compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message, Foundationresponse::HTTP_NOT_FOUND);
    }

}
