<?php

namespace homevip\helper;

trait RedisLock
{
    private $redis;

    /**
     * 初始化 Redis 实例
     */
    public function __construct()
    {
        $this->redis = app('redis')->connection('cache');
    }


    /**
     * 加锁
     * @param string $key 锁名
     * @param string $requestId 唯一请求ID，根据这个表示来判断是哪个客户端加的锁，从而保证加解锁的唯一性
     * @param int $expireTime 过期时间 [EX 代表秒，PX 代表毫秒。]
     * @return string 成功加锁返回请求ID否则返回false
     */
    public function addLock(string $key, string $requestId, int $expireTime = 2)
    {
        $result = $this->redis->set($key, $requestId, 'EX', $expireTime, 'NX');
        return $result ? $requestId : $result;
    }


    /**
     * 解锁
     * @param string $key 锁名
     * @param string $requestId 唯一请求ID，根据这个表示来判断是哪个客户端加的锁，从而保证加解锁的唯一性
     * @return mixed
     */
    public function releaseLock(string $key, string $requestId)
    {
        //使用Lua脚本来实现解锁, 从而保证解锁的原子性，语句的意思的如果通过key获取的值与传递过来的参数相等，就删除这个key
        $lua = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('del', KEYS[1]) else return 0 end";
        $result = $this->redis->eval($lua, 1, $key, $requestId);
        return $result;
    }
}
