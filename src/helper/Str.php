<?php

namespace nook\helper;

class Str
{
    /**
     * 对银行卡号进行掩码处理
     * @param string $bankCardNo
     * @return string
     */
    public static function formatCardNo(string $bankCardNo): string
    {
        $suffix = substr($bankCardNo, -4, 4); // 截取银行卡号后4位
        $maskBankCardNo = substr($bankCardNo, 0, 4) . "**** **** " . $suffix;
        return $maskBankCardNo;
    }

    /**
     * 随机获取字符串
     * Numeric：0-数字大写小写三种随机；1-纯数字
     * @param int $length
     * @param int $numeric
     * @return string
     */
    public static function createRandomStr(int $length, int $numeric = 0): string
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }

        return $hash;
    }

    /**
     * 获取两个字符串中间的字符
     * @param string $str
     * @param string $left_str
     * @param string $right_str
     * @return string
     */
    public static function getMiddleStr(string $str, string $left_str, string $right_str): string
    {
        $left = strpos($str, $left_str);
        $right = strpos($str, $right_str, $left);

        if ($right < $left || $left < 0) return '';

        return substr($str, $left + strlen($left_str), $right - $left - strlen($left_str));
    }

    /**
     * 获取违禁词
     * @param string $base_path
     * @param bool   $type false:字符串 true:数组
     * @return mixed
     * @throws
     */
    public static function getViolationWords(string $base_path = '', bool $type = false)
    {
        if (empty($base_path)) {
            $base_path = app()->getRootPath() . "extend/config";
        }
        $file_path = $base_path . "/ViolationWords.php";

        if (is_file($file_path) === false) {
            file_put_contents($file_path, "<?php\n", LOCK_EX);
        }

        $violation_words = require $file_path . '';
        if (!empty($violation_words) && $violation_words <> 1) {
            if ($type === false) {
                $violation_words = implode(',', $violation_words);
            } else {
                $violation_words = array_combine($violation_words, array_fill(0, count($violation_words), '**'));
            }
        }

        return $violation_words;
    }

    /**
     * 设置违禁词
     * @param string $base_path
     * @param string $new_words
     * @return bool
     * @throws
     */
    public static function setViolationWords(string $base_path = '', string $new_words = ''): bool
    {
        $violation_words = self::getViolationWords($base_path);

        $violation_words = $violation_words . $new_words; // [,测试]
        $contents = array_unique(explode(",", $violation_words));
        $contents = stripslashes(var_export($contents, true)) . ";\n";
        $violation_words_file = $base_path . "/ViolationWords.php";
        file_put_contents($violation_words_file, "<?php\n\nreturn " . $contents, LOCK_EX);

        return true;
    }
}
