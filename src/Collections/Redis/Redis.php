<?php

namespace homevip\helper;

class Redis
{
    private static $instance; // 静态实例

    private $redis; // 连接资源


    /**
     * 初始化操作
     *
     * @param array $options
     */
    private function __construct(array $options = [])
    {
        // 默认配置
        if (empty($options)) {

            if (!extension_loaded('yaconf')) {
                exit('yaconf 服务异常');
            }

            // Yaconf 配置
            $config = \Yaconf::get('config');
            $options = [
                'host'      => $config['redis']['REDIS_HOST'],
                'port'      => $config['redis']['REDIS_PORT'],
                'password'  => $config['redis']['REDIS_PASSWORD'],
                'db'        => $config['redis']['REDIS_DEFAULT_DB'],
            ];
        }

        // 自定义配置
        $this->setOptions($options);
    }


    /**
     * 设置相关选项
     *
     * @param array $options
     * @return void
     */
    private function setOptions(array $options = [])
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
     * 返回静态实例
     *
     * @return void
     */
    public static function instance(array $options = [])
    {
        if (is_null(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class($options);
        }
        return self::$instance;
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
