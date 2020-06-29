### 使用

##### 创建类 V1Controller.php
```
    <?php
    namespace App\Http\Controllers\Auths;

    use App\Http\Controllers\Controller;
    use homevip\helper\Session;

    class V1Controller extends Controller
    {
        /**
        *  session 快捷操作
        *  2020_05_07
        *
        * @param string $name
        * @param string $value
        * @param integer $options
        * @return void
        */
        public function session(string $name, $value = '', int $options = 7200)
        {
            // session 初始化
            $Session = Session::instance()
                ->lifetime($options)
                ->domain('.hy9z.com')
                ->httponly(false)
                ->redisConnect(env('REDIS_HOST'), env('REDIS_PORT'), env('REDIS_PASSWORD'), env('REDIS_DB'));

            if ('' === $value) {
                // 获取 session
                return $Session->get($name);
            } elseif (is_null($value)) {
                // 删除缓存
                return $Session->del($name);
            } else {
                // 缓存 session
                return $Session->set($name, $value);
            }
        }
    }
```

##### 使用
```
    $key = 'aaaa';
    $Session = new \App\Http\Controllers\Auths\V1Controller();
    if (!$Session->session($key)) {
        dump('空');
        $Session->session($key, date('H:i:s'));
    }
    var_dump($Session->session($key));
```