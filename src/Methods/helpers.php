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
 * 自定义返回函数
 */
if (!function_exists('error')) {
    function error($code = 200, $message = NULL, $data = [])
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
