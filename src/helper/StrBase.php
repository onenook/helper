<?php

namespace nook\helper;

class StrBase
{
    /**
     * 获取指定长度的随机字母数字组合的字符串
     * @param int $length
     * @return string
     */
    public static function random(int $length = 16): string
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return static::substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * 字符串转小写
     * @param string $value
     * @return string
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     * @param string $value
     * @return string
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     * @param string $value
     * @return int
     */
    public static function length(string $value): string
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     * @param string   $string
     * @param int      $start
     * @param int|null $length
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     * @param string $value
     * @param string $delimiter
     * @return string
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     * @param string $value
     * @return string
     */
    public static function camel(string $value): string
    {
        return lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     * @param string $value
     * @return string
     */
    public static function studly(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * 转为首字母大写的标题格式
     * @param string $value
     * @return string
     */
    public static function title(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    // ----------------------------------------------------------------------------------------------------

    /**
     * 后置追加字符串
     * @param string $value
     * @param string $append
     * @return string
     */
    public static function append(string $value, string $append): string
    {
        return $value . $append;
    }

    /**
     * 前置添加字符串
     * @param string $value
     * @param string $prepend
     * @return string
     */
    public static function prepend(string $value, string $prepend): string
    {
        return $prepend . $value;
    }

    /**
     * 用给定字符串环绕文本
     * @param string $value
     * @param string $start
     * @param string $end
     * @return string
     */
    public static function wrap(string $value, string $start, string $end = ''): string
    {
        return $start . $value . (empty($end) ? $start : $end);
    }

    /**
     * 查找字符串在另一个字符串中首次出现的位置
     * @param string $haystack
     * @param string $needle
     * @param int    $offset
     * @return int
     */
    public static function indexOf(string $haystack, string $needle, int $offset = 0): int
    {
        $result = mb_strpos($haystack, $needle, $offset);
        return ($result === false) ? -1 : $result;
    }

    /**
     * 将字符串转换为数组
     * @param string $value
     * @return array
     */
    public static function chars(string $value): array
    {
        if (strlen($value) === self::length($value)) {
            return str_split($value);
        }

        return preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 通过 正则表达式 拆分字符串
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public static function split(string $value, string $pattern): array
    {
        // array_map() — 为数组的每个元素应用回调函数
        return array_map(function ($item) {
            return $item;
        }, preg_split($pattern, $value, -1, PREG_SPLIT_DELIM_CAPTURE));
    }

    /**
     * 通过 换行符 拆分字符串
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public static function lines(string $value, string $pattern = '/(\r?\n)/'): array
    {
        $lines = preg_split($pattern, $value, -1, PREG_SPLIT_DELIM_CAPTURE);
        $chunk = array_chunk($lines, 2);

        $result = [];
        foreach ($chunk as $values) {
            $result[] = implode('', $values);
        }

        return $result;
    }

    /**
     * 返回字符串行数
     * @param string $value
     * @return int
     */
    public static function countLines(string $value): int
    {
        return count(self::lines($value));
    }

    /**
     * 将用户函数应用于字符串的每一行
     * @param string   $value
     * @param callable $callback 回调函数：建议使用匿名函数
     * @return string
     */
    public static function eachLine(string $value, callable $callback): string
    {
        $lines = self::lines($value);

        foreach ($lines as $index => $line) {
            $lines[$index] = (string)call_user_func_array($callback, [$line, $index]);
        }

        return implode('', $lines);
    }

    /**
     * 子字符串替换
     * @param string       $value
     * @param string|array $search
     * @param string|array $replace
     * @return string
     */
    public static function strReplace(string $value, $search, $replace): string
    {
        return str_replace($search, $replace, $value);
    }

    /**
     * 执行正则表达式的搜索和替换
     * @param string|array    $value
     * @param string          $pattern
     * @param string|callable $replacement
     * @return string
     */
    public static function pregReplace(string $value, string $pattern, $replacement): string
    {
        if (is_callable($replacement)) {
            // 如果是回调函数; 执行一个正则表达式搜索并且使用一个回调进行替换
            $value = preg_replace_callback($pattern, function ($matches) use ($replacement) {
                // $args = array_map(function ($item) {return $item;}, $matches);
                return call_user_func_array($replacement, $matches);
            }, $value);
        } else {
            // 如果不是回调函数; 执行一个正则表达式的搜索和替换
            $value = preg_replace($pattern, $replacement, $value);
        }

        return $value;
    }

    /**
     * 执行匹配正则表达式
     * @param string $value   输入字符串
     * @param string $pattern 正则表达式
     * @param null   $matches 返回数据
     * @return int            匹配次数
     */
    public static function pregMatch(string $value, string $pattern, &$matches = null): int
    {
        return preg_match($pattern, $value, $matches);
    }

    // ----------------------------------------------------------------------------------------------------

    public static function trim(string $value, string $charList = " \t\n\r\0\x0B"): string
    {
        if (empty($charList)) {
            $value = trim($value);
        } else {
            $value = trim($value, $charList);
        }

        return $value;
    }

    public static function rtrim(string $value, string $charList = " \t\n\r\0\x0B"): string
    {
        if (empty($charList)) {
            $value = rtrim($value);
        } else {
            $value = rtrim($value, $charList);
        }

        return $value;
    }

    public static function ltrim(string $value, string $charList = " \t\n\r\0\x0B"): string
    {
        if (empty($charList)) {
            $value = ltrim($value);
        } else {
            $value = ltrim($value, $charList);
        }

        return $value;
    }

    public static function jsonEncode(string $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function jsonDecode(string $value): string
    {
        return json_decode($value, true);
    }

    public static function serializeEncode($value): string
    {
        return serialize($value);
    }

    public static function serializeDecode(string $value)
    {
        return unserialize($value);
    }

    public static function specialCharsEncode(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }

    public static function specialCharsDecode(string $value): string
    {
        return htmlspecialchars_decode($value, ENT_QUOTES | ENT_HTML5);
    }

    public static function save(string $value, string $path, bool $isAppend = false): bool
    {
        if (false === $isAppend) {
            return file_put_contents($path, $value, LOCK_EX);
        }
        return file_put_contents($path, $value, FILE_APPEND | LOCK_EX);
    }

    public static function isEmpty(string $value): bool
    {
        return empty($value);
    }

    public static function isNumeric(string $value): bool
    {
        return is_numeric($value);
    }
}
