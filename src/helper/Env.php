<?php

namespace nook\helper;

class Env
{
    /**
     * 检查是否微信内访问
     * @return bool
     */
    public function isWeiXin(): bool
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') ? true : false;
    }


    /**
     * Server Info
     * @return array
     */
    public function serverInfo(): array
    {
        $info = [
            'url'           => $_SERVER['HTTP_HOST'],
            'document_root' => $_SERVER['DOCUMENT_ROOT'],
            'server_soft'   => $_SERVER['SERVER_SOFTWARE'],
        ];

        return $info;
    }

    /**
     * PHP Info
     * @return array
     */
    public function phpInfo(): array
    {
        $info = [
            'server_os'   => PHP_OS,
            'php_version' => PHP_VERSION,
        ];

        return $info;
    }

    /**
     * PHP Ini Info
     * @return array
     */
    public function phpIniInfo(): array
    {
        $info = [
            'max_upload_size' => ini_get('upload_max_filesize'),
        ];

        return $info;
    }
}
