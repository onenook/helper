<?php

namespace nook\collect;

use QL\QueryList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Base
{
    /**
     * @var Client
     */
    public $Client = null;

    /**
     * @var QueryList
     */
    public $QueryList = null;

    public function __construct()
    {
        set_time_limit(0);
        libxml_use_internal_errors(true);

        $this->Client = new Client();
        $this->QueryList = new QueryList();
    }

    public static function GetCurlData(string $url, array $rules, bool $type = false)
    {
        $data = [];

        try {
            $data = (new static())->QueryList->get($url)->rules($rules);

            if ($type) {
                $data = $data->removeHead()->query()->getData();
                $data = $data->flatten()->all();
            } else {
                $data = $data->removeHead()->queryData();
            }
        } catch (RequestException $e) {

            $Response = $e->getResponse();
            if (empty($Response)) return [];

            $Code = $Response->getStatusCode();
            if ($Code <> 200) return [];
        }

        return $data;
    }

    public static function FilePutContent(string $file_path, string $string): bool
    {
        if (!$file_path || !$string) return false;

        file_put_contents($file_path, $string . PHP_EOL, FILE_APPEND);

        return true;
    }

    public static function FilePutContents(string $file_path, array $data): bool
    {
        if (!$file_path || !$data) return false;

        foreach ($data as $value) {
            if (empty($value)) continue;
            file_put_contents($file_path, $value . PHP_EOL, FILE_APPEND);
        }

        return true;
    }

    public static function GetFileLine(string $file_path, int $line): string
    {
        $line = $line - 1;

        if (!$file_path || $line < 0 || !is_file($file_path)) return '';

        $content = @file($file_path);

        if (isset($content[$line]) === false) return '';

        return str_replace([' ', PHP_EOL], '', $content[$line]);
    }

    public static function GetFileNum(string $file_path, int $page = 1, int $line = 100): array
    {
        if (!$file_path || $page < 0 || !$line || !is_file($file_path)) return [];

        $result = [];
        $start = $page - 1;
        $end = $start + $line;
        $content = @file($file_path);

        for ($i = $start; $i < $end; $i++) {
            if (isset($content[$i]) === false) continue;

            $url = str_replace([' ', PHP_EOL], '', $content[$i]);
            $result[] = json_decode($url, true);
        }

        return $result;
    }


    public static function getFolder(string $path): array
    {
        $folder = scandir($path);

        unset($folder[0], $folder[1]);

        return $folder;
    }

    public static function moveFolder(string $old_name, string $new_name): bool
    {
        return rename($old_name, $new_name);
    }
}
