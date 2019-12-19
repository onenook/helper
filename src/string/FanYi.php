<?php

namespace nook\string;

class FanYi
{
    /**
     * 百度翻译
     * 中文[zh]英语[en]中文繁体[cht]
     * @param array  $config
     * @param string $query
     * @param string $from
     * @param string $to
     * @return array
     */
    public function translate(array $config, string $query, string $from = 'zh', string $to = 'en')
    {

        $url = $config['url'];
        $app_id = $config['app_id']; // 替换为您的APPID
        $sec_key = $config['sec_key']; // 替换为您的密钥

        $args = [
            'q'     => $query,
            'appid' => $app_id,
            'salt'  => rand(10000, 99999),
            'from'  => $from,
            'to'    => $to,
        ];
        $args['sign'] = $this->buildSign($query, $app_id, $args['salt'], $sec_key);

        $result = json_decode($this->call($url, $args), true);
        if (false === isset($result['error_code'])) $result = $result['trans_result'][0];

        return json_encode($result, 256);
    }

    private function buildSign(string $query, string $appID, int $salt, string $secKey): string
    {
        return md5($appID . $query . $salt . $secKey);
    }

    private function call(string $url, $args = null, $method = 'post', $timeout = 10, $headers = [])
    {
        $i = 0;
        $ret = false;

        while ($ret === false) {
            if ($i > 1)
                break;
            if ($i > 0) {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }

        return $ret;
    }

    private function callOnce($url, $args = null, $method = 'post', $withCookie = false, $timeout = 10, $headers = [])
    {
        $ch = curl_init();
        if ($method == 'post') {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = $this->convert($args);
            if ($data) {
                if (stripos($url, "?") > 0) {
                    $url .= "&$data";
                } else {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    private function convert(&$args)
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data .= "$key=" . rawurlencode($val) . "&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }

    private function language(): array
    {
        $array = [
            'ara' => '阿拉伯语',
            'est' => '爱沙尼亚语',
            'bul' => '保加利亚语',
            'pl'  => '波兰语',
            'dan' => '丹麦语',
            'de'  => '德语',
            'ru'  => '俄语',
            'fra' => '法语',
            'fin' => '芬兰语',
            'kor' => '韩语',
            'nl'  => '荷兰语',
            'cs'  => '捷克语',
            'rom' => '罗马尼亚语',
            'pt'  => '葡萄牙语',
            'jp'  => '日语',
            'swe' => '瑞典语',
            'slo' => '斯洛文尼亚语',
            'th'  => '泰语',
            'wyw' => '文言文',
            'spa' => '西班牙语',
            'el'  => '希腊语',
            'hu'  => '匈牙利语',
            'zh'  => '中文',
            'en'  => '英语',
            'it'  => '意大利语',
            'vie' => '越南语',
            'yue' => '粤语',
            'cht' => '中文繁体',
        ];

        return $array;
    }
}
