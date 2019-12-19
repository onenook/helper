<?php

namespace nook\api;

use nook\helper\Base;

class Juhe
{
    /**
     * 发送短信
     * @param int $phone    接受者手机号
     * @param int $sms_code 4-8位的字母数字组合
     * @param int $minute   有效期, 单位: 分钟
     * @return string
     */
    public static function sendSms(int $phone, int $sms_code, int $minute = 5): string
    {
        $sendUrl = 'http://v.juhe.cn/sms/send'; // 短信接口的URL
        $smsConf = [
            'key'       => strrev('17dc5e073c272e2b812f292492966696'),
            'mobile'    => $phone, // 手机号码
            'tpl_id'    => strrev('173916'), // 模板ID
            'tpl_value' => '#code#=' . $sms_code . '&#m#=' . $minute, //您设置的模板变量，根据实际情况修改
        ];

        $content = self::curl($sendUrl, $smsConf); //请求发送短信

        return $content;
    }

    /**
     * 简繁体火星文互转
     * @param array $data
     * @return string
     */
    public static function charconvert(array $data): string
    {
        $url = 'http://japi.juhe.cn/charconvert/change.from';

        $fields = ['appkey', 'text', 'type'];
        if (false === Base::checkFieldsIsset($data, $fields)) {
            return Base::return_json(-1, '参数错误');
        }

        $params = [
            'ip'   => '', // 需要查询的IP地址或域名
            'text' => $data['text'], // 需要转换字符串
            'type' => $data['type'], // 需要转换成的类型。0：简体 1：繁体 2：火星文
            'key'  => $data['appkey'], // 应用APPKEY(应用详细页查询)
        ];

        return self::curl($url, $params);
    }

    /**
     * 根据汉字查询字典
     * @param array $data
     * @return string
     */
    public static function query(array $data): string
    {
        $url = 'http://v.juhe.cn/xhzd/query';

        $fields = ['appkey', 'word'];
        if (false === Base::checkFieldsIsset($data, $fields)) {
            return Base::return_json(-1, '参数错误');
        }

        $params = [
            'word'  => $data['word'], // 填写需要查询的汉字，UTF8 urlencode编码
            'key'   => $data['appkey'], // 应用APPKEY(应用详细页查询)
            'dtype' => 'json', // 返回数据的格式,xml或json，默认json
        ];

        return self::curl($url, $params);
    }

    /**
     * 请求数据
     * @param string $url
     * @param array  $params
     * @return string
     */
    public static function curl(string $url, array $params): string
    {
        $paramstring = http_build_query($params);
        $content = Curl::JuHeCurl($url, $paramstring);
        $result = json_decode($content, true);

        if ($result) {
            if ($result['error_code'] === 0) {
                $return_data = [0, 'Success', $result];
            } else {
                $return_data = [$result['error_code'], $result['reason'], []];
            }
        } else {
            $return_data = [-1, '请求失败', []];
        }

        return Base::return_json($return_data[0], $return_data[1], $return_data[2]);
    }
}
