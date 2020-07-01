# laravel-helpers

##### Cookie 使用方法 [支持连贯操作]
```
    $Cookie = Cookie::instance();

    // 存储
    $Cookie->set('key', 'test', [
        'expire' => 60 * 10
    ]);

    // 修改
    $Cookie->update('key', '李四', [
        'expire' => 60 * 10
    ]);

    // 删除
    $Cookie->del('key');

    // 删除全部
    $Cookie->delAll();

    // 读取
    $Cookie->get('key');

```

##### IDCard 用于检测身份证号码的函数
```
    $nber = '123456789';
    $IDCard = new IDCard();
    dd($IDCard::isCard($nber));
```


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

    // 使用
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

##### ResponseJson 公共模板输出
```

    use ResponseJson;
    public function test(Request $request)
    {
        // 成功输出
        $success = [];
        return $this->outSuccess($success);


        // 错误输出
        $error = '非正常数值';
        return $this->outError(4100, $array); // 错误码, 错误其他信息[选填]
    }
```

##### SeasLog 日志扩展
```
    use SeasLog;
    public function test()
    {

        $path = 'D:\SeasLog\\' . date('Y-m-d H');
        $param = [
            'id'    => mt_rand(100, 999),
            'name'  => mt_rand(100, 999),
        ];
        $param = json_encode($param, JSON_UNESCAPED_UNICODE);
        var_dump($this->set($path, $param));
    }
```