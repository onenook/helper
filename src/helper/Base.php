<?php

namespace nook\helper;

class Base
{
    /**
     * 检查字段是否设置
     * @param array $data
     * @param array $fields
     * @return bool
     */
    public static function checkFieldsIsset(array $data, array $fields): bool
    {
        foreach ($fields as $key => $value) {
            if (isset($data[$value]) === false) return false;
        }

        return true;
    }

    /**
     * 处理更新字段
     * @param array $data
     * @param array $fields
     * @return array
     */
    public static function handleUpdateFields(array $data, array $fields): array
    {
        $return = [];

        foreach ($data as $key => $value) {
            isset($fields[$key]) ? $return[$key] = $value : [];
        }

        return $return;
    }

    /**
     * 返回数据 - Json
     * @param int    $code
     * @param string $msg
     * @param array  $data
     * @param int    $count
     * @return string
     */
    public static function return_json(int $code = 0, string $msg = '', array $data = [], int $count = 0): string
    {
        $result = [
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data,
            'count' => $count,
            'time'  => time(),
        ];

        return json_encode($result, 256);
    }

    /**
     * 返回数据 - Array
     * @param int    $code
     * @param string $msg
     * @param array  $data
     * @param int    $count
     * @return array
     */
    public static function return_array(int $code = 0, string $msg = '', array $data = [], int $count = 0): array
    {
        $result = [
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data,
            'count' => $count,
            'time'  => time(),
        ];

        return $result;
    }
}
