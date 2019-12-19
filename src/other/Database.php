<?php

namespace nook\other;

use think\facade\Db;

class Database
{
    // 表列表
    public function table()
    {
        $array = [
            'name'            => '表名',
            'engine'          => '引擎',
            'version'         => '版本',
            'row_format'      => '行格式',
            'rows'            => '行数',
            'avg_row_length'  => '平均行长度',
            'data_length'     => '数据量',
            'max_data_length' => '最大数据量',
            'index_length'    => '索引空间大小',
            'data_free'       => '',
            'auto_increment'  => '自动递增',
            'create_time'     => '创建时间',
            'update_time'     => '更新时间',
            'check_time'      => '检查时间',
            'collation'       => '排序规则',
            'checksum'        => '',
            'create_options'  => '',
            'comment'         => '注释',
        ]; // KEY字段说明

        $data = Db::query('show table status');
        $data = array_map('array_change_key_case', $data); // 把键值修改为小写

        return json_encode(['data' => $data, 'total' => count($data)], 256);
    }

    // 表信息
    public function view(string $name = 'tem_user')
    {
        $array = [
            'field'      => '名',
            'type'       => '类型',
            'collation'  => '排序规则',
            'null'       => '是否为空',
            'key'        => '是否为主键',
            'default'    => '默认值',
            'extra'      => '额外信息',
            'privileges' => '权限',
            'comment'    => '注释',
        ]; // KEY字段说明

        $data = Db::query('show full columns from `' . $name . '`');
        $data = array_map('array_change_key_case', $data); // 把键值修改为小写

        return json_encode(['data' => $data, 'total' => count($data)], 256);
    }

    // 表优化
    public function optimize(string $name = 'tem_user')
    {
        $name = is_array($name) ? implode('`,`', $name) : $name;
        $data = Db::query('optimize table `' . $name . '`');

        $info = $data ? '成功' : '失败';
        return '数据表 `' . $name . '` 优化' . $info;
    }

    // 表修复
    public function repair(string $name = 'tem_user')
    {
        $name = is_array($name) ? implode('`,`', $name) : $name;
        $data = Db::query('repair table `' . $name . '`');

        $info = $data ? '成功' : '失败';
        return '数据表 `' . $name . '` 修复' . $info;
    }
}
