<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-07 14:48:18
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-07 14:48:35
 */

return [
     /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    // 'Content-Type', 'X-Requested-With', 'Authorization'
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'], // ex: ['GET', 'POST', 'PUT',  'DELETE']
    'exposedHeaders' => [],
    'maxAge' => 0,
];