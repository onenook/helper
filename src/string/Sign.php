<?php

namespace nook\string;

class Sign
{
    // ------------------------------ 微信签名 ------------------------------

    /**
     * 生成签名
     * @param array $data
     * @return string
     */
    public static function makeSign(array $data): string
    {
        // 签名步骤一：按字典序排序参数
        ksort($data);
        $string = self::toUrlParams($data);

        // 签名步骤二：在string后加入time
        $string = $string . '&time=' . time();

        // 签名步骤三：sha1加密
        $string = sha1($string); // md5()

        // 签名步骤四：所有字符转为大写
        $string = strtoupper($string);
        return $string;
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $data
     * @return string
     */
    public static function toUrlParams(array $data): string
    {
        $buff = '';
        foreach ($data as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        $buff = trim($buff, '&');
        return $buff;
    }
}
