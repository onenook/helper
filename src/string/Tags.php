<?php

namespace nook\string;

class Tags
{
    /**
     * 删除指定字符
     * @param string $html 字符串
     * @param array  $tags 标签
     * @return mixed
     */
    public static function del_char($html, array $tags)
    {
        if (empty($tags)) $tags = [' ', '?', '[', ']', PHP_EOL];

        $tag = implode('|', $tags);
        return preg_replace($tag, '', $html);
    }

    /**
     * 删除指定标签
     * @param string $html 字符串
     * @param array  $tags 标签
     * @return mixed
     */
    public static function del_tags($html, array $tags)
    {
        $tag = implode('|', $tags);
        return preg_replace("/<(.?)({$tag})(.*?)>/is", '', $html);
    }

    /**
     * 删除标签内容
     * @param string $html 字符串
     * @param array  $tags 标签
     * @return mixed
     */
    public static function del_tags_or_content($html, array $tags)
    {
        $tag = implode('|', $tags);
        $res = preg_replace(/** @lang text */ "/<({$tag})(.*?)>(.*?)<\/({$tag})>/is", '', $html);
        return preg_replace(/** @lang text */ "/<\/({$tag})>/is", '', $res);
    }

    /**
     * 获取标签属性值
     * @param string $html 字符串
     * @param string $tag  标签
     * @param string $attr 属性名
     * @return mixed
     */
    public static function get_tags_attr_value($html, $tag, $attr)
    {
        $regex = "/<{$tag}(.*?){$attr}=['|\"](.*?)['|\"](.*?)>/is";
        preg_match_all($regex, $html, $matches, PREG_PATTERN_ORDER);
        return $matches[2];
    }

    /**
     * 获取标签内容
     * @param string $html  字符串
     * @param string $tag   标签
     * @param string $attr  属性名
     * @param string $value 属性值
     * @return mixed
     */
    public static function get_tags_attr_content($html, $tag, $attr, $value)
    {
        $regex = "/<{$tag}(.*?){$attr}=['|\"](.*?){$value}(.*?)['|\"](.*?)>(.*?)<\/{$tag}>/is";
        preg_match_all($regex, $html, $matches, PREG_PATTERN_ORDER);
        return end($matches);
    }

    /**
     * 常用正则表达式
     */
    private static function regex()
    {
        // (.?)(.+)(.*)(.*?)/加is的作用
        // ^$匹配 输入字符串的 开始和结束位置
        // ()标记 子表达式的 开始和结束位置
        // []标记 中括号表达式的 开始和结束
        // {}标记 限定符表达式的 开始和结束
        // |指明两项之间的一个选择
        // .匹配除换行符 \n之外的任何单字符
        // ?匹配前面的子表达式零次或一次
        // +匹配前面的子表达式一次或多次
        // *匹配前面的子表达式零次或多次
    }
}
