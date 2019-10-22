<?php

namespace nook\helper;

class Juhe
{
    /**
     * 简繁体火星文互转
     * @param array $data
     * @return string
     */
    public function charconvert(array $data): string
    {
        $url = 'http://japi.juhe.cn/charconvert/change.from';

        $fields = ['appkey', 'text', 'type'];
        if (false === Base::checkFieldsIsset($data, $fields)) {
            return Base::json(-1, '参数错误');
        }

        $params = [
            'ip'   => '', // 需要查询的IP地址或域名
            'text' => $data['text'], // 需要转换字符串
            'type' => $data['type'], // 需要转换成的类型。0：简体 1：繁体 2：火星文
            'key'  => $data['appkey'], // 应用APPKEY(应用详细页查询)
        ];

        return $this->curl($url, $params);
    }

    /**
     * 根据汉字查询字典
     * @param array $data
     * @return string
     */
    public function query(array $data): string
    {
        $url = 'http://v.juhe.cn/xhzd/query';

        $fields = ['appkey', 'word'];
        if (false === Base::checkFieldsIsset($data, $fields)) {
            return Base::json(-1, '参数错误');
        }

        $params = [
            'word'  => $data['word'], // 填写需要查询的汉字，UTF8 urlencode编码
            'key'   => $data['appkey'], // 应用APPKEY(应用详细页查询)
            'dtype' => 'json', // 返回数据的格式,xml或json，默认json
        ];

        return $this->curl($url, $params);
    }

    /**
     * 请求数据
     * @param string $url
     * @param array  $params
     * @return string
     */
    public function curl(string $url, array $params): string
    {
        $paramstring = http_build_query($params);
        $content = Curl::JuHeCurl($url, $paramstring);
        $result = json_decode($content, true);

        if ($result) {
            if ($result['error_code'] === 0) {
                return Base::json(0, 'Success', $result);
            } else {
                return Base::json($result['error_code'], $result['reason']);
            }
        } else {
            return Base::json(-1, '请求失败');
        }
    }
}
