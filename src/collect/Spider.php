<?php

namespace nook\collect;

class Spider
{
    public function index(string $ip = ''): string
    {
        $ip = '61.163.8.208';

        $domain = htmlentities($ip, ENT_QUOTES | ENT_IGNORE, 'UTF-8');

        if (!filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'IP不合法';
        }

        $lookup = implode('.', array_reverse(explode('.', $domain))) . '.' . 'in-addr.arpa';

        $dns = dns_get_record($lookup);
        $target = $dns['0']['target'];

        if (strpos($target, 'baidu') <> false) {
            $gs = '百度蜘蛛';
        } else if (strpos($target, 'sogou') <> false) {
            $gs = '搜狗蜘蛛';
        } else if (strpos($target, 'msnbot') <> false) {
            $gs = '必应蜘蛛';
        } else if (strpos($target, 'googlebot') <> false) {
            $gs = '谷歌蜘蛛';
        } else if (strpos($target, 'yandex') <> false) {
            $gs = 'YanDex蜘蛛';
        } else if ($this->So360($domain)) {
            $gs = '360蜘蛛';
        } else {
            $gs = '虚假蜘蛛';
        }

        return $gs;
    }

    private function So360(string $ip): bool
    {
        $ip_arr = explode('.', $ip);
        $So_360 = false;
        if ((strpos($ip, '180.153') !== false) or (strpos($ip, '180.163') !== false)) {
            if ('220' <= $ip_arr['2'] and $ip_arr['2'] <= '236') {
                $So_360 = true;
            }

        } else if ((strpos($ip, '42.236') !== false)) {
            if ((10 <= $ip_arr['2'] and $ip_arr['2'] <= 99) or (101 <= $ip_arr['2'] and $ip_arr['2'] <= 103)) {
                $So_360 = true;
            }
        }
        return $So_360;
    }
}
