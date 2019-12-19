<?php

namespace nook\helper;

class Check
{
    /**
     * 检查手机号格式
     * @param string $mobile
     * @return bool
     */
    public static function checkMobile(string $mobile): bool
    {
        $pattern = "/^1[3456789]\d{9}$/";
        return preg_match($pattern, $mobile) ? true : false;
    }

    /**
     * 检查是否微信内访问
     * @return bool
     */
    public static function isWeiXin(): bool
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') ? true : false;
    }
}
