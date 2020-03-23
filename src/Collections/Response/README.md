##### 公共模板输出
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