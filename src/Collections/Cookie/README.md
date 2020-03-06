##### 使用方法 [支持连贯操作]
```
    // 存储
    Cookie::instance()
        ->expire(60 * 10)
        ->set('key', 'aaaa');

    //  更新
    Cookie::instance()
        ->expire(60 * 10)
        ->update('key', 'bbbb');

    // 删除
    Cookie::instance()
        ->expire(60 * 10)
        ->del('key');
```