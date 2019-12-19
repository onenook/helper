<?php

namespace nook\api;

class Curl
{
    /**
     * 获取链接内容
     * @param string $url
     * @return string
     */
    public static function GetContent(string $url)
    {
        $arrContextOptions = [
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        return file_get_contents($url, false, stream_context_create($arrContextOptions));
    }

    /**
     * GetCurl
     * @param string $url
     * @return bool|string
     */
    public static function GetCurl(string $url)
    {
        $curl = curl_init(); // 创建一个curl对象

        curl_setopt($curl, CURLOPT_URL, $url); // 设置curl
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 设置相应的选项
        curl_setopt($curl, CURLOPT_HEADER, false); // 去掉header头信息
        curl_setopt($curl, CURLOPT_REFERER, 'https://www.baidu.com/');

        $output = curl_exec($curl); // 执行并获取内容
        curl_close($curl); // 释放curl句柄
        return $output;
    }

    /**
     * PostCurl
     * @param string $url
     * @param array  $post_data
     * @return bool|string
     */
    public static function PostCurl(string $url, array $post_data)
    {
        // $url = 'http://localhost/web_services.php';
        // $post_data = ['username' => 'bob', 'key' => '12345'];

        $curl = curl_init(); // 创建一个curl对象

        curl_setopt($curl, CURLOPT_URL, $url); // 设置curl
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 设置相应的选项
        curl_setopt($curl, CURLOPT_POST, 1); // post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); // post变量

        $output = curl_exec($curl); // 执行并获取内容
        curl_close($curl); // 释放curl句柄
        return $output;
    }

    /**
     * BaseCurl
     * @param string $url
     * @param array  $data
     * @param bool   $cookie
     * @param bool   $is_post
     * @return bool|string
     */
    public static function BaseCurl(string $url, array $data, bool $cookie = false, bool $is_post = false)
    {
        // 如果 URL 为空直接返回
        if (empty($url)) return false;

        $Headers = [
            'Accept-Encoding: gzip, deflate, br',
            "Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4",
            'Referer: https://blog.csdn.net/ljl890705/article/details/52219565',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Safari/537.36',
            'Content-length: ' . http_build_query($data),
        ]; // 设置浏览器的特定HEADER
        $Headers = $_SERVER['HTTP_USER_AGENT'];

        // 设置 Get 请求参数
        if (false === $is_post && $data) {
            $url = $url . '?' . http_build_query($data);
        }

        // 初始化 CURL
        $ch = curl_init();

        // 请求的基本设置
        curl_setopt($ch, CURLOPT_URL, $url);                      // 设置URL地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // 以字符串返回，而不是直接输出
        curl_setopt($ch, CURLOPT_HEADER, false);            // 去掉header信息，启用时会将头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);           // 在HTTP请求中 Headers 的内容

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        // 连接-响应时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);       // 连接超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);              // 响应超时

        // 重定向相关设置
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);             // 3次重定向后停止
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);     // TRUE时将会根据服务器返回HTTP头中的"Location:"重定向
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);        // TRUE时将根据Location:重定向时，自动设置header中的"Referer:"信息

        // SSL 相关设置
        if (substr($url, 0, 5) === 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }

        // POST 相关操作
        if (false <> $is_post) {
            $post_data = http_build_query($data);
            curl_setopt($ch, CURLOPT_POST, true);           // 使用POST方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);     // 设置POST变量
        }

        // Cookie 相关操作
        if (false <> $cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }

        // 执行 CURL 请求
        $response = curl_exec($ch);

        // 获取请求相关信息
        // $status = curl_getinfo($ch);

        // 获取 Set-Cookie 信息
        // list($header, $body) = explode("\r\n\r\n", $response, 2);
        // preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);

        // 返回格式化信息
        if (true === false) {
            json_decode($response, true);
            simplexml_load_string($response);
        }

        // 返回错误信息
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        // 释放 CURL 句柄
        curl_close($ch);

        // 返回结果
        return $response;
    }

    /**
     * JuHeCurl
     * @param string $url     [请求的URL地址]
     * @param string $params  [请求的参数]
     * @param int    $is_post [是否采用POST形式]
     * @return bool|string
     */
    public static function JuHeCurl(string $url, string $params, int $is_post = 0)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        if ($response === false) {
            // echo 'error: ' . curl_error($ch);
            return false;
        }

        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $httpInfo = array_merge($httpInfo, curl_getinfo($ch));

        curl_close($ch);

        return $response;
    }
}
