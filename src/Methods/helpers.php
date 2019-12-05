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
    function builderOrderSn($prefix = ''): string
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
    function getIntervalDays($startDate, $endDate)
    {
        $start_Date = new DateTime($startDate);
        $end_Date   = new DateTime($endDate);
        return $start_Date->diff($end_Date)->days;
    }
}
