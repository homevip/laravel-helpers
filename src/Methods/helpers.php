<?php

/**
 * 获取客户端IP
 * 
 * @return string 返回IP
 */
if (!function_exists('getIP')) {
    function getIP()
    {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
}


/**
 * 自定义缓存
 * 
 * @param [type] $name
 * @param string $value
 * @param integer $options 缓存时间 /秒
 * @return void
 */
if (!function_exists('S')) {
    function S(string $name, $value = '', int $options = 60)
    {
        $cache = app('redis')->connection('cache');
        $name = 'S_' . md5($name);

        if ('' === $value) {
            // 获取缓存
            return json_decode($cache->get($name), true);
        } elseif (is_null($value)) {
            // 删除缓存
            return $cache->del($name);
        } else {
            // 缓存数据
            $expire = (int) $options;
            return $cache->setex($name, $expire, json_encode($value, JSON_UNESCAPED_UNICODE));
        }
    }
}


/**
 * 自定义返回函数
 */
if (!function_exists('outPutJson')) {
    function outPutJson($code = 200, $message = NULL, $data = [])
    {
        $package = array();
        if ($code) {
            $package['code']    = $code;
            $package['message'] = $message ?? config('response_code')[$code];
            $package['data']    = null;
        } else {
            $package['code']    = $code;
            $package['message'] = config('response_code')[$code];
            $package['data']    = $message ?? $data;
        }
        return \Response::json($package);
    }
}


/**
 * 生成订单号
 * 
 * @param string $prefix 前缀
 * @return void
 */
if (!function_exists('builderOrderSn')) {
    function builderOrderSn(string $prefix = ''): string
    {
        $prefix = !empty($prefix) ? $prefix : '';
        return  $prefix . date('Ymd') .
            substr(microtime(), 2, 5) .
            substr(implode(
                NULL,
                array_map('ord', str_split(substr(uniqid($prefix), 7, 13), 1))
            ), 0, 8) .
            sprintf('%04d', rand(0, 9999));
    }
}


/**
 * 两个时间 相差天数
 * 
 * @param integer $startTime   起始日期
 * @param integer $endTime     结束日期
 * @return integer             天数
 */
if (!function_exists('getIntervalDays')) {
    function getIntervalDays(string $startDate, string $endDate): int
    {
        $start_Date = new DateTime($startDate);
        $end_Date   = new DateTime($endDate);
        return $start_Date->diff($end_Date)->days;
    }
}


/**
 * 两个日期间的所有日期
 * 
 * @param integer $startTime   起始日期
 * @param integer $endTime     结束日期
 * @return integer             天数
 */
if (!function_exists('getDatesBetweenTwoDays')) {
    function getDatesBetweenTwoDays(string $startDate, string $endDate): array
    {
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);

        $array = array();
        while ($startDate <= $endDate) {
            $array[]    = date('Y-m-d', $startDate);
            $startDate  = strtotime('+1 day', $startDate);
        }
        return $array;
    }
}


/**
 * 二维数组去重
 * 
 * @param array $array 数组
 * @return array 去重后的数组
 */
if (!function_exists('duplicateRemoval')) {
    function duplicateRemoval(array $array): array
    {
        return array_unique($array, SORT_REGULAR);
    }
}


/**
 * SQL 语句调试
 */
if (!function_exists('sql_debug')) {
    function sql_debug()
    {
        \Illuminate\Support\Facades\DB::listen(function ($sql) {

            $SQL = null;
            $array = explode('?', $sql->sql);
            foreach ($array as $key => $value) {
                if (isset($sql->bindings[$key])) {
                    $SQL .= $value . "'" . $sql->bindings[$key] . "'";
                } else {
                    $SQL .= $array[$key];
                }
            }

            // 	dump ($sql->sql);
            dump($SQL);
            // 	dump ($sql->bindings);
            // 	dump ( $sql );
            // 	echo $sql->sql;
            // 	dump ( $sql->bindings );
        });
    }
}


/**
 * 检测是否是JSON数据
 * 
 * @param string $string   字符串
 * @return bool             返回值 true|false
 */
if (!function_exists('is_json')) {
    function is_json(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}


/**
 *  随机生成字符串
 * 
 * @param string $len  生成长度
 * @param integer $chars 可自定义字符串
 * @return string 
 */
if (!function_exists('randomString')) {
    function randomString(int $len = 6, string $chars = null): string
    {
        if (is_null($chars)) {
            $chars .= "abcdefghijklmnopqrstuvwxyz";
            $chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $chars .= "0123456789";
            $chars .= "!@#$?|{/:;%^&*()-_[]}<>~+=.;";
        }
        mt_srand(10000000 * (float) microtime());

        $str = '';
        $lc = strlen($chars) - 1;

        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}


/**
 * 数组模糊搜索
 * 
 * @param array    $param      原始数组 [一维数组]
 * @param string  $keyWord     搜索的关键字
 * @return bool                返回值 array
 */
if (!function_exists('arraySearch')) {
    function arraySearch(array $param, string $keyWord): array
    {
        $list = array(); // 匹配后的结果
        foreach ($param as $value) {
            if (strstr($value, $keyWord) !== false) {
                array_push($list, $value);
            }
        }
        return $list;
    }
}


/**
 * 生成某个范围内的随机时间
 * 
 * @param string   $startData      起始日期
 * @param string   $endData        结束日期
 * @param bool     $now            true:返回日期 false:返回时间戳
 * @return void
 */
if (!function_exists('randomDate')) {
    function randomDate(string $startData, string $endData = "", bool $now = true): string
    {
        $begin = strtotime($startData);
        $end = $endData == "" ? mktime() : strtotime($endData);
        $timestamp = rand($begin, $end);
        return $now ? date("Y-m-d H:i:s", $timestamp) : $timestamp;
    }
}


/**
 * 无限分类
 * 
 * @param array    $list   原始数组
 * @param string   $id     主键
 * @param string   $pid    父ID
 * @param string   $son    子名称
 * @return array           返回值
 */
if (!function_exists('tree')) {
    function tree(array $list, string $id = 'id', string $pid = 'pid', string $son = '_child')
    {
        $tree   = array(); //格式化的树
        $tmpMap = array(); //临时扁平数据

        foreach ($list as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($list as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }
}


/**
 * 格式化价格 保留到两位小数
 * 
 * @param string $price 金额
 * @return void
 */
if (!function_exists('price')) {
    function price($price): string
    {
        return sprintf("%.2f", $price);
    }
}


/**
 * 将字符串拆分成数组
 * 
 * @param string $string 字符串
 * @return void
 */
if (!function_exists('stringToArray')) {
    function stringToArray(string $string): array
    {
        preg_match_all("/./u", $string, $math);
        return $math[0];
    }
}


/**
 * 获取汉字拼音的首字母
 * 
 * @param string $str
 * @return void
 */
if (!function_exists('firstCharter')) {
    function firstCharter(string $string)
    {
        static $fchar;
        if (empty($string)) return '';
        $fchar = ord($string{
            0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($string{
            0});
        $s1 = iconv('UTF-8', 'gb2312', $string);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $string ? $s1 : $string;
        $asc = ord($s{
            0}) * 256 + ord($s{
            1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }
}


/**
 * 递归移动目录所有内容到指定目录
 * 
 * @param $disDir 旧目录
 * $targetDir 目标目录
 * @return bool
 * @throws \Exception
 */
if (!function_exists('copyDir')) {
    function copyDir(string $disDir, string $targetDir)
    {
        if (!is_dir($disDir)) {
            throw new \Exception('源目录不存在无法移动!');
        }
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0770, true);
        }
        $dir = opendir($disDir);
        while (false !== ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                $disFile = $disDir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($disFile)) {
                    copyDir($disFile, $targetDir . DIRECTORY_SEPARATOR . $file);
                    continue;
                } else {
                    copy($disFile, $targetDir . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        closedir($dir);
        return true;
    }
}


/**
 * CURL - GET
 */
if (!function_exists('getCurl')) {
    function getCurl($url)
    {
        // 初始化
        $curl = curl_init();
        $user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';

        // 设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置头文件的信息作为数据流输出
        // curl_setopt ( $curl, CURLOPT_HEADER, 1 ); // 注释掉 显示 HTTP 头部信息 开启后有助于调试信息

        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, []);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        // 发起 https 请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);


        // 执行命令
        $data = curl_exec($curl);

        // 关闭URL请求
        curl_close($curl);

        // 返回(显示)获得的数据
        return $data;
    }
}

/**
 * CURL - POST
 */
if (!function_exists('postCurl')) {
    function postCurl($url, $param)
    {
        // 初始化
        $curl = curl_init();
        $user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';

        // 设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置头文件的信息作为数据流输出
        // curl_setopt ( $curl, CURLOPT_HEADER, 1 ); // 注释掉 显示 HTTP 头部信息 开启后有助于调试信息

        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);

        // 设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        // 发起 https 请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        // 执行命令
        $data = curl_exec($curl);

        // 关闭URL请求
        curl_close($curl);
        // 返回(显示)获得的数据
        return $data;
    }
}
