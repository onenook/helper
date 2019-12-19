<?php

namespace nook\helper;

class Env
{
    /**
     * Composer Info
     * @return array
     */
    public static function composerInfo(): array
    {
        $composer = json_decode(file_get_contents(app()->getRootPath() . 'composer.json'), true);

        $require_list = [];
        $require_dev_list = [];
        if (array_key_exists('require', $composer)) {
            $require_list = $composer['require'];
        }
        if (array_key_exists('require-dev', $composer)) {
            $require_dev_list = $composer['require-dev'];
        }

        return [$require_list, $require_dev_list];
    }

    /**
     * Browser Info
     * @return array
     */
    public static function browserInfo(): array
    {
        $user_agent = request()->header('user-agent');

        if (false !== stripos($user_agent, 'win')) {
            $user_os = 'Windows';
        } else if (false !== stripos($user_agent, 'mac')) {
            $user_os = 'MAC';
        } else if (false !== stripos($user_agent, 'linux')) {
            $user_os = 'Linux';
        } else if (false !== stripos($user_agent, 'unix')) {
            $user_os = 'Unix';
        } else if (false !== stripos($user_agent, 'bsd')) {
            $user_os = 'BSD';
        } else if (false !== stripos($user_agent, 'iPad') || false !== stripos($user_agent, 'iPhone')) {
            $user_os = 'IOS';
        } else if (false !== stripos($user_agent, 'android')) {
            $user_os = 'Android';
        } else {
            $user_os = 'Other';
        }

        if (false !== stripos($user_agent, 'MSIE')) {
            $user_browser = 'MSIE';
        } else if (false !== stripos($user_agent, 'Firefox')) {
            $user_browser = 'Firefox';
        } else if (false !== stripos($user_agent, 'Chrome')) {
            $user_browser = 'Chrome';
        } else if (false !== stripos($user_agent, 'Safari')) {
            $user_browser = 'Safari';
        } else if (false !== stripos($user_agent, 'Opera')) {
            $user_browser = 'Opera';
        } else {
            $user_browser = 'Other';
        }

        return [$user_os, $user_browser];
    }

    /**
     * Server Info
     * @return array
     */
    public static function serverInfo(): array
    {
        $info = [
            'url'           => $_SERVER['HTTP_HOST'],
            'server_ip'     => GetHostByName($_SERVER['SERVER_NAME']),
            'document_root' => $_SERVER['DOCUMENT_ROOT'],
            'server_soft'   => $_SERVER['SERVER_SOFTWARE'],
            'think_version' => app()->version(),
        ];

        return $info;
    }

    /**
     * PHP Info
     * @return array
     */
    public static function phpInfo(): array
    {
        $info = [
            'server_os'     => PHP_OS,      // PHP环境
            'php_version'   => PHP_VERSION, // PHP版本
            'php_sapi_name' => PHP_SAPI,    // 运行模式
            'timezone'      => date_default_timezone_get(), // PHP时区
        ];

        return $info;
    }

    /**
     * PHP Ini Info
     * @return array
     */
    public static function phpIniInfo(): array
    {
        $info = [
            'memory_limit'        => ini_get('memory_limit'),       // 运行内存限制
            'upload_max_filesize' => ini_get('upload_max_filesize'),// 最大文件上传限制
            'max_file_uploads'    => ini_get('max_file_uploads'),   // 单次上传数量限制
            'post_max_size'       => ini_get('post_max_size'),      // 最大post限制
        ];

        return $info;
    }
}
