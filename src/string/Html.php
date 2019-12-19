<?php

namespace nook\string;

class Html
{
    /**
     * getJquery
     * @return array
     */
    public static function getJquery(): array
    {
        $array = [
            '1.12.4' => 'https://code.jquery.com/jquery-1.12.4.min.js',
            '2.2.4'  => 'https://code.jquery.com/jquery-2.2.4.min.js',
            '3.4.1'  => 'https://code.jquery.com/jquery-3.4.1.min.js',
        ];

        return $array;
    }

    /**
     * getJavaScript
     * @return array
     */
    public static function getJavaScript(): array
    {
        $array = [
            'layui_css' => '//res.layui.com/layui/dist/css/layui.css',
            'layui_js'  => '//res.layui.com/layui/dist/layui.js',
        ];

        return $array;
    }
}
