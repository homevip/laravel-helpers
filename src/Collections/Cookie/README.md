##### 使用方法 [支持连贯操作]
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