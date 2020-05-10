### Redis.php
```
// 安装 yaconf 扩展

// 定义配置
$Redis = Redis::instance([
    'host'      =>  config('database.redis.cache.host'),
    'port'      =>  config('database.redis.cache.port'),
    'password'  =>  config('database.redis.cache.password'),
    'db'        =>  config('database.redis.cache.database'),
]);

// 默认配置
$Redis = Redis::instance();


// __call 方法使用 [当类中不存在该方法,直接调用call 实现调用底层redis相关的方法]
var_dump($Redis->set('aaa', 600)); // 添加
var_dump($Redis->get('aaa')); // 获取
```
##### 使用
```
    use RedisLock;
    public function test(Request $request)
    {
        $key = 'key_' . uniqid();
        $requestID = 'requestID_' . uniqid();

        // 加锁
        if ($this->addLock($key, $requestID)) {
            // 代码逻辑

            // 释放锁
            $this->releaseLock($key, $requestID);
        }
    }
```