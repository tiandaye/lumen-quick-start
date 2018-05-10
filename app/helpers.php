<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-07 14:11:10
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-08 10:59:42
 */

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return base_path('public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}

if (!function_exists('rest')) {
    /**
     * Lumen RESTful route.
     *
     * @param  string  $path
     * @param  string  $controller
     * @param  string  $binding
     * @param  string  $namespace
     * @return void
     */
    function rest($path, $controller, $binding = 'id', $namespace = '')
    {
        global $app;

        $app->get($path, $namespace . '\\' . $controller . '@all');
        $app->get($path . '/{' . $binding . '}', $namespace . '\\' . $controller . '@get');
        $app->post($path, $namespace . '\\' . $controller . '@store');
        $app->put($path . '/{' . $binding . '}', $namespace . '\\' . $controller . '@update');
        $app->delete($path . '/{' . $binding . '}', $namespace . '\\' . $controller . '@destroy');
    }
}

if (!function_exists('make_code')) {
    /**
     * [makeCode 随机字符串]
     * @param  integer $length [随机数长度]
     * @return [type]          [返回一个指定长度的字符串]
     */
    function make_code($length = 5)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            // 将 $length 个数组元素连接成字符串
            $code .= $chars[$keys[$i]];
        }
        return $code;
    }
}

if (!function_exists('proxy_http_request')) {
    /**
     * [proxy_http_request 请求转发]
     * @param  [type] $type [description]
     * @param  [type] $url  [description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    function proxy_http_request($type, $url, $data = [])
    {
        $ch = curl_init();
        if (strtoupper($type) === 'GET') {
            // 设置选项，包括URL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
        } else if (strtoupper($type) === 'POST') {
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // 设置url
            curl_setopt($ch, CURLOPT_URL, $url);
            // TRUE, 将curl_exec()获取的信息以字符串返回, 而不是直接输出
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // 超时时间
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // curl如果需要进行毫秒超时
            // curl_easy_setopt(curl, CURLOPT_NOSIGNAL,1L);
            // 或者curl_setopt ( $ch,  CURLOPT_NOSIGNAL,true);//支持毫秒级别超时设置
            // 设置发送方式:post
            curl_setopt($ch, CURLOPT_POST, 1);
            // 设置发送数据
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        // 执行cURL会话
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
            // print curl_error($ch);
        }
        //释放curl句柄
        curl_close($ch);
        // 在这里处理要还给
        return $response;
    }
}
// // 获取当前登录用户
// if (! function_exists('auth_user')) {
//     /**
//      * Get the auth_user.
//      *
//      * @return mixed
//      */
//     function auth_user()
//     {
//         return app('Dingo\Api\Auth\Auth')->user();
//     }
// }

// if (! function_exists('dingo_route')) {
//     /**
//      * 根据别名获得url.
//      *
//      * @param string $version
//      * @param string $name
//      * @param string $params
//      *
//      * @return string
//      */
//     function dingo_route($version, $name, $params = [])
//     {
//         return app('Dingo\Api\Routing\UrlGenerator')
//             ->version($version)
//             ->route($name, $params);
//     }
// }

// if (! function_exists('trans')) {
//     /**
//      * Translate the given message.
//      *
//      * @param string $id
//      * @param array  $parameters
//      * @param string $domain
//      * @param string $locale
//      *
//      * @return string
//      */
//     function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
//     {
//         if (is_null($id)) {
//             return app('translator');
//         }

//         return app('translator')->trans($id, $parameters, $domain, $locale);
//     }
// }
