<?php

namespace nook\helper;

class Crypto
{
    /**
     * 加密解密
     * @param string $string
     * @param string $operation
     * @param int    $expiry
     * @param string $key
     * @return string
     */
    public function authCode(string $string, string $operation = 'decode', int $expiry = 0, string $key = ''): string
    {
        $cKey_length = 4;
        $key = md5($key <> '' ? $key : 'setting.auth_key');
        $keyA = md5(substr($key, 0, 16));
        $keyB = md5(substr($key, 16, 16));
        $keyC = $cKey_length ? ($operation === 'decode' ? substr($string, 0, $cKey_length) : substr(md5(microtime()), -$cKey_length)) : '';

        $cryptKey = $keyA . md5($keyA . $keyC);
        $key_length = strlen($cryptKey);

        $string = $operation === 'decode' ? base64_decode(substr($string, $cKey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyB), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndKey = [];
        for ($i = 0; $i <= 255; $i++) {
            $rndKey[$i] = ord($cryptKey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndKey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation === 'decode') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyB), 0, 16)) {
                return substr($result, 26) ?? '';
            } else {
                return '';
            }
        } else {
            return $keyC . str_replace('=', '', base64_encode($result));
        }
    }
}
