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