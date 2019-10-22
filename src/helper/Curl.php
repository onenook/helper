<?php

namespace nook\helper;

class Curl
{
    /**
     * 请求接口返回内容
     * @param  string $url     [请求的URL地址]
     * @param  string $params  [请求的参数]
     * @param  int    $is_post [是否采用POST形式]
     * @return  string
     */
    public static function JuHeCurl(string $url, string $params = '', int $is_post = 0): string
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
