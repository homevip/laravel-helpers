<?php

namespace homevip\helper;

trait SeasLog
{
    public function __construct()
    {
        // 检查扩展
        if (!extension_loaded('SeasLog')) {
            exit('SeasLog 扩展未启用');
        }
    }


    /**
     * 写入数据
     *
     * @param string $path      保存路径
     * @param [type] $param     写入数据
     * @param string $separator 分隔符
     * @return boolean
     */
    public function set(string $path, $param, string $separator = ' | '): bool
    {

        // 保存路径
        \SeasLog::setBasePath($path);

        // 数组 转换 字符串
        if (is_array($param)) {
            $param = implode($separator, $param);
        }

        // 写入
        return \SeasLog::info($param);
    }
}
