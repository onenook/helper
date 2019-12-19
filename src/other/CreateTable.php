<?php

namespace nook\other;

class CreateTable
{
    private $encoded = 'utf8mb4';

    public function getField()
    {
        $array = [
            'id'          => $this->getNumber('id', 'int', 11, 0, '主键ID'),
            'uid'         => $this->getNumber('uid', 'int', 11, 0, '用户ID'),
            'pid'         => $this->getNumber('pid', 'int', 11, 0, '父级ID'),
            'user_id'     => $this->getNumber('user_id', 'int', 11, 0, '用户ID'),
            'admin_id'    => $this->getNumber('admin_id', 'int', 11, 0, '管理员ID'),
            'order'       => $this->getNumber('order', 'smallint', 8, 0, '排序'),
            'type'        => $this->getNumber('type', 'tinyint', 3, 0, '类型'),
            'category'    => $this->getNumber('category', 'tinyint', 3, 0, '类别'),
            'status'      => $this->getNumber('status', 'tinyint', 3, 0, '状态'),
            // 0 No 1 Yes
            'is_top'      => $this->getNumber('is_top', 'tinyint', 3, 0, '是否置顶'),
            'time'        => $this->getNumber('time', 'int', 11, 0, '时间'),
            'create_time' => $this->getNumber('create_time', 'int', 11, 0, '创建时间'),
            'update_time' => $this->getNumber('update_time', 'int', 11, 0, '更新时间'),
            'delete_time' => $this->getNumber('delete_time', 'int', 11, 0, '删除时间'),
            'nickname'    => $this->getNumber('nickname', 'varchar', 255, '', '昵称'),
            'username'    => $this->getNumber('nickname', 'varchar', 255, '', '昵称'),
        ];

        return $array;
    }

    public function getNumber($field, $type = 'int', $length = 11, $default = '0', $annotation = '')
    {
        return "ADD COLUMN `{$field}` {$type}({$length}) UNSIGNED NOT NULL DEFAULT {$default} COMMENT '{$annotation}',";
    }

    public function getDecimal($field, $length = 11, $decimal = 2, $default = '0.00', $annotation = '')
    {
        return "ADD COLUMN `{$field}` decimal({$length},{$decimal}) NOT NULL DEFAULT '{$default}' COMMENT '{$annotation}}',";
    }

    public function getString($field, $type = 'varchar', $length = 11, $default = '', $annotation = '')
    {
        return "ADD COLUMN `{$field}` {$type}({$length}) CHARACTER SET {$this->encoded} COLLATE {$this->encoded}_general_ci NOT NULL DEFAULT '{$default}' COMMENT '{$annotation}',";
    }

    public function getText($field, $default = '', $annotation = '')
    {
        return "ADD COLUMN `{$field}` text CHARACTER SET {$this->encoded} COLLATE {$this->encoded}_general_ci NOT NULL DEFAULT '{$default}' COMMENT '{$annotation}',";
    }

    public function getAfterString($string, $field)
    {
        $string = explode(',', $string);
        return "{$string} AFTER `{$field}`,";
    }

    public function delField($field)
    {
        return "DROP COLUMN `{$field}`,";
    }

    public function commonWords()
    {
        $array = [
            '类型'  => 'type',
            '状态'  => 'status',
            '时间'  => 'time',
            '类别'  => 'category',
            '排序'  => 'order',
            '登录'  => 'login',
            '注册'  => 'register',
            '管理员' => 'admin',
            '用户'  => 'user',
            '昵称'  => 'nickname',
            '用户名' => 'username',
            '密码'  => 'password',
            '盐值'  => 'salt',
            '性别'  => 'sex',
            '生日'  => 'birthday',
            '名称'  => 'name',
            '号码'  => 'num',
            '数字'  => 'number',
            '标题'  => 'title',
            '作者'  => 'author',
            '头像'  => 'avatar',
            '画像'  => 'image',
            '图标'  => 'icon',
            '照片'  => 'photo',
            '视频'  => 'video',
            '钱'   => 'money',
            '总数'  => 'total',
            '价格'  => 'price',
            '费用'  => 'rate',
            '信息'  => 'info',
            '数据'  => 'data',
            '默认'  => 'default',
            '配置'  => 'config',
            '省份'  => 'province',
            '城市'  => 'city',
            '地区'  => 'area',
            '地址'  => 'address',
            '经度'  => 'longitude',
            '纬度'  => 'latitude',
            '开始'  => 'start',
            '结束'  => 'end',
            '之前'  => 'before',
            '现在'  => 'now',
            '之后'  => 'after',
            '上传'  => 'upload',
            '下载'  => 'download',
            '显示'  => 'show',
            '隐藏'  => 'hide',
            '错误'  => 'error',
            '成功'  => 'success',
            '创建'  => 'create',
            '更新'  => 'update',
            '删除'  => 'delete',
            '版本'  => 'version',
            '键'   => 'key',
            '值'   => 'value',
            '结果'  => 'result',
            '连接'  => 'link',
            '网址'  => 'url',
            '规则'  => 'rule',
            '组'   => 'group',
            '列表'  => 'list',
            '日志'  => 'log',
            '代码'  => 'code',
            '级别'  => 'level',
            '扩展'  => 'extend',
            '评论'  => 'remark',
            '字段'  => 'field',
            '来源'  => 'source',
            '记录'  => 'record',
            '描述'  => 'description',
            '文件'  => 'file',
            '路径'  => 'path',
            '大小'  => 'size',
        ];

        return $array;
    }
}
