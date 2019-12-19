<?php

namespace nook\other;

class Decryption
{
    public function evalEncryption($base_path)
    {
        $dir_source = $base_path . '/source';
        $dir_in = $base_path . '/in';
        $dir_out = $base_path . '/out';

        if (false === is_dir($dir_source)) {
            return 'source目录不存在';
        }

        $dh = opendir($dir_source);
        if (false === $dh) {
            $string = '';
            while (false <> $file = readdir($dh)) {
                if ($file === '.' or $file === '..') continue;
                $data = [
                    'dir_source' => $dir_source,
                    'dir_in'     => $dir_in,
                    'dir_out'    => $dir_out,
                    'file'       => $file,
                ];
                $string = $this->encryptionEval($data);
            }
            closedir($dh);
            return $string . '文件解密完成';
        }

        return true;
    }

    private function encryptionEval(array $data): string
    {
        $rest = '';
        $string = '';

        $file_path_s = $data['dir_source'] . "/{$data['file']}";
        $string .= "打开加密文件[{$data['dir_source']}/{$data['file']}]<br>";

        $str_source = str_replace('eval($ooo000($ooo00o($o00o)))', '$rest=$ooo000($ooo00o($o00o))', file_get_contents($file_path_s));
        file_put_contents($data['dir_in'] . "/{$data['file']}", $str_source);
        $string .= "写入过渡文件[{$data['dir_in']}/{$data['file']}]<br>";

        include $data['dir_in'] . "/{$data['file']}";
        file_put_contents($data['dir_out'] . "/{$data['file']}", '<?php' . $rest);
        $string .= "写入解密文件[{$data['dir_out']}/{$data['file']}]<br>";

        return $string;
    }
}
