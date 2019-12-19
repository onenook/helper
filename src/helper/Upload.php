<?php

declare (strict_types=1);

namespace nook\helper;

use think\Validate;
use think\facade\Filesystem;

class Upload
{
    // 验证
    public static function checkFile(array $files, array $check): array
    {
        if (empty($files)) {
            return return_data(0, '上传文件为空');
        }

        $ext = [
            'gif'  => 'image/gif',
            'png'  => 'image/png',
            'jpg'  => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'bmp'  => 'image/bmp',
        ];

        $validate = new Validate();

        foreach ($files as $key => $value) {

            $file_field = $key;
            $file_value = $value;
            $check_value = $check[$file_field];

            if (isset($check_value['ext'])) {
                $file_ext = explode(',', $check_value['ext']);

                // 验证上传文件后缀
                $r = $validate->fileExt($file_value, $file_ext);
                if ($r === false) return return_data(0, '文件后缀限制：' . implode(',', $file_ext));

                $file_mime = [];
                foreach ($file_ext as $v) {
                    if (isset($ext[$v])) $file_mime[] = $ext[$v];
                }

                // 验证上传文件类型
                $r = $validate->fileMime($file_value, $file_mime);
                if ($r === false) return return_data(0, '文件类型限制：' . implode(',', $file_mime));
            }

            if (isset($check_value['size'])) {
                $file_size = (int)$check_value['size'];

                // 检测上传文件大小
                $r = $validate->fileSize($file_value, 1024 * 1024 * $file_size);
                if ($r === false) return return_data(0, '文件大小限制：' . $file_size . 'M');
            }

            if (isset($check_value['image'])) {
                $file_image = $check_value['image'];

                // 验证图片的宽高及类型
                $r = $validate->image($file_value, $file_image);
                if ($r === false) return return_data(0, '图片大小限制：' . $file_image);
            }
        }

        return return_data(1, '验证通过');
    }

    // 七牛
    public static function uploadQiNiu($file, string $path = ''): array
    {
        // 进行本地图片上传
        // PutFile方法的第三参数: ['rule' => ['time', 'uniQid', 'md5', 'sha1']];
        $save_name = Filesystem::disk('qiniu')->putFile($path, $file);

        // 返回结果信息
        return return_data(1, '上传成功', ['src' => '/' . $save_name]);
    }

    // 本地
    public static function uploadLocal($file, string $path = '', string $rule = 'uniQid'): array
    {
        $path = empty($path) ? 'storage/image' : $path;

        // 进行本地图片上传
        // PutFile方法的第三参数: ['rule' => ['time', 'uniQid', 'md5', 'sha1']];
        $save_name = Filesystem::disk('public')->putFile($path, $file, $rule);

        // 返回结果信息
        return return_data(1, '上传成功', ['src' => '/' . $save_name]);
    }

    // 使用
    public function index()
    {
        // 获取数据
        $files = request()->file();

        // 验证文件
        $check = ['image' => ['ext' => 'jpg,png,gif', 'size' => '100000']];
        $result = self::checkFile($files, $check);
        if (empty($result['code'] === 0)) return json($result);

        // 上传图片
        foreach ($files as $file) {
            $result[] = self::uploadLocal($file);
        }

        return json($result);
    }
}
