<?php

namespace homevip\helper;

class Redis
{
    private $redis; // 连接资源

    /**
     * 初始化操作
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        try {
            $this->redis = new \Redis();
            $this->redis->connect($options['host'], $options['port']);
            if ('' != $options['password']) {
                $this->redis->auth($options['password']);
            }
            $this->redis->select($options['db']);
        } catch (\Exception $e) {
            echo 'redis 服务异常! ' . $e->getMessage();
        }
    }


    /**
     * 当类中不存在该方法,直接调用call 实现调用底层redis相关的方法
     *
     * @param [type] $name 方法名
     * @param [type] $args 参数
     * @return void
     */
    public function __call($name, $args)
    {
        return $this->redis->$name(...$args);
    }
}
