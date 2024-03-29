<?php

namespace nook\number;

class Math
{
    /**
     * 格式化文件大小单位
     * @param int    $size
     * @param string $delimiter
     * @return string
     */
    public static function format_size(int $size, string $delimiter = ''): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 把数值转化成倍数值
     * @param float $number
     * @param float $times
     * @return float
     */
    public static function numberTimes(float $number, float $times): float
    {
        if (empty($number)) return 0;
        $number = floor($number / $times) * $times;
        return $number;
    }

    /**
     * 返回两个坐标的距离
     * @param     $longitude1
     * @param     $latitude1
     * @param     $longitude2
     * @param     $latitude2
     * @param int $decimal
     * @return string
     */
    public static function getLong($longitude1, $latitude1, $longitude2, $latitude2, $decimal = 2): string
    {
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;
        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if ($distance >= 1000) {
            $result = round($distance / 1000, $decimal) . 'km';
        } else {
            $result = floor($distance) . 'm';
        }

        return $result;
    }
}
