##### 公共模板输出
```

    use SeasLog;
    public function test()
    {

        $path = 'D:\SeasLog\\' . date('Y-m-d H'); // 设置路径
        $param = [
            'id'    => mt_rand(100, 999),
            'name'  => mt_rand(100, 999),
        ];
        var_dump($this->set($path, $param));
    }

```