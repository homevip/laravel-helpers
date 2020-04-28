##### 公共模板输出
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