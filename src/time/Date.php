<?php

namespace nook\time;

class Date
{
    /**
     * 获取时间区间
     *      此函数不可以超过相应的进制数 -> PS: ①个月最多31天，就不要填写大于31的数值
     *      参数介绍：[1-2-3-4-5-6] -> [①年②月③天④小时⑤分钟⑥秒]
     * @param string $param
     * @return string
     */
    public static function getTime(string $param = '0-0-0-0-0-0'): string
    {
        // 天：不固定，动态计算；
        // 月：1-12；时分秒：0-23/59；全部先 +1，月份再 +1
        $step = [1000, 13, 0, 24, 60, 60]; // 年 月 日 时 分 秒
        $time = explode('-', date('Y-m-d-H-i-s'));

        static $c = 0; // 默认差值
        static $x = false; // 日期影响月份使用
        static $s_string = ''; // 开始时间字符串
        static $e_string = ''; // 结束时间字符串

        // 数据分割后进行倒序，先处理秒
        $param = array_reverse(explode('-', $param), true);
        foreach ($param as $key => $value) {
            $n = $time[$key];                   // 当前时间
            $s = $step[$key];                   // 当前进制

            $n = $n - $c;                       // 当前
            $d = $n - $value;                   // 差值

            if ($key === 1) {
                if ($d === 0 || true === $x) {
                    $d = $d - 1;
                }
            }               // 处理月份
            if ($key === 2) {
                $n_y = $time[0];
                $n_m = $time[1];

                $year = $n_m - 1 === 0 ? $n_y - 1 : $n_y;
                $month = $n_m - 1 === 0 ? 12 + ($n_m - 1) : $n_m - 1;

                // 计算上个自然月有几天
                $s = (strtotime($n_y . '-' . $n_m) - strtotime($year . '-' . $month)) / 86400;

                if ($d === 0) {
                    $d = $s; // 日期：如果日期为零，则天数等于上月天数
                    $x = true; // 月份：如果日期为零，则月份根据此变量减①
                }
            }               // 处理日期

            $s_time = $d < 0 ? $s + $d : $d;    // 开始时间
            $e_time = $n + $c;                  // 结束时间

            $c = $d < 0 ? 1 : 0;                // 进制差

            // 使用连接符拼接字符串
            $s_string = sprintf('%02d', $s_time) . $s_string;
            $e_string = sprintf('%02d', $e_time) . $e_string;
        }

        // 返回数据
        return json_encode([strtotime($s_string), strtotime($e_string)], 256);
    }

    /**
     * 判断时间过去了多久
     * @param string $time
     * @return string
     */
    public static function pastTime(string $time = '2019-12-12'): string
    {
        $time = is_string($time) ? strtotime($time) : intval($time);
        $time = false === $time || $time > time() ? time() : $time;

        $t = time() - $time; // 时间差(秒)
        $y = date('Y', $time) - date('Y', time()); // 是否跨年
        switch ($t) {
            case $t < 60:
                $text = $t . '秒前';
                break; // 1分钟内
            case $t < 60 * 60:
                $text = floor($t / 60) . '分钟前';
                break; // 1小时内
            case $t < 60 * 60 * 24:
                $text = floor($t / (60 * 60)) . '小时前';
                break; // 1天内
            case $t < 60 * 60 * 24 * 3:
                $text = floor($time / (60 * 60 * 24)) === 1 ? '昨天' . date('H:i', $time) : '前天' . date('H:i', $time);
                break; // 昨天|前天
            case $t < 60 * 60 * 24 * 30:
                $text = date('m月d日 H:i', $time);
                break; // 1月内
            case $t < 60 * 60 * 24 * 365 && $y == 0:
                $text = date('m月d日', $time);
                break; // 1年内
            default:
                $text = date('Y年m月d日', $time);
                break; // 1年以前
        }

        return $text;
    }
}
