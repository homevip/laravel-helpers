<?php


namespace homevip\helper;


class Session implements ISession, ISessionHandler
{
    /**
     * 有效时间/秒
     */
    const EXPIRES_IN = 7200;


    // redis 实例
    public $redis;

    /**
     * 操作方法
     *
     * @var array
     */
    private $parameter = [
        'session_name'  => NULL,
        'lifetime'      => NULL,
        'path'          => NULL,
        'domain'        => NULL,
        'secure'        => NULL,
        'httponly'      => NULL,
        'options'       => NULL,
    ];


    /**
     * 定义实例
     *
     * @var [type]
     */
    private static $instance;


    /**
     * 返回静态实例
     *
     * @return void
     */
    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * 初始化参数
     *
     * @return void
     */
    public function initi()
    {
        if ($this->status()) {
            return true;
        }

        // redis 初始化
        $this->redis = new \Redis();
        $this->redis->connect(
            env('REDIS_HOST', '127.0.0.1'),
            env('REDIS_PORT', 6379)
        );
        $this->redis->auth(env('REDIS_PASSWORD', null));
        $this->redis->select(10);


        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        // 下面这行代码可以防止使用对象作为会话保存管理器时可能引发的非预期行为
        register_shutdown_function('session_write_close');


        $this->parameter['session_name']    = $this->parameter['session_name']  ?? 'HOMEVIP_ID';
        $this->parameter['lifetime']        = $this->parameter['lifetime']      ?? self::EXPIRES_IN;
        $this->parameter['path']            = $this->parameter['path']          ?? '/';
        $this->parameter['domain']          = $this->parameter['domain']        ?? 'homevip@126.com';
        $this->parameter['secure']          = $this->parameter['secure']        ?? $_SERVER['REQUEST_SCHEME'] ? false : true;
        $this->parameter['httponly']        = $this->parameter['httponly']      ?? true;
        $this->parameter['options']         = $this->parameter['options']       ?? [];

        // // 设置 SESSION 名字
        session_name($this->parameter['session_name']);

        // 设置 session_id
        // session_id(uniqid());

        session_set_cookie_params(
            $this->parameter['lifetime'],
            $this->parameter['path'],
            $this->parameter['domain'],
            $this->parameter['secure'],
            $this->parameter['httponly']
        );

        session_start();
    }


    /**
     * 设置会话名称
     *
     * @param string $session_name
     * @return void
     */
    public function session_name(string $session_name)
    {
        $this->parameter['session_name'] = $session_name;
        return $this;
    }


    /**
     * Cookie 的 生命周期，以秒为单位
     *
     * @param integer $lifetime
     * @return void
     */
    public function lifetime(int $lifetime)
    {
        $this->parameter['lifetime'] = $lifetime;
        return $this;
    }


    /**
     * 此 cookie 的有效 路径。 on the domain where 设置为“/”表示对于本域上所有的路径此 cookie 都可用
     *
     * @param string $path
     * @return void
     */
    public function path(string $path)
    {
        $this->parameter['path'] = $path;
        return $this;
    }


    /**
     * Cookie 的作用 域。 例如：“www.php.net”。 如果要让 cookie 在所有的子域中都可用，此参数必须以点（.）开头，例如：“.php.net”
     *
     * @param string $domain
     * @return void
     */
    public function domain(string $domain)
    {
        $this->parameter['domain'] = $domain;
        return $this;
    }


    /**
     * 设置为 TRUE 表示 cookie 仅在使用 安全 链接时可用
     *
     * @param boolean $secure
     * @return void
     */
    public function secure(bool $secure)
    {
        $this->parameter['secure'] = $secure;
        return $this;
    }


    /**
     * 设置为 TRUE 表示 PHP 发送 cookie 的时候会使用 httponly 标记
     *
     * @param boolean $httponly
     * @return void
     */
    public function httponly(bool $httponly)
    {
        $this->parameter['httponly'] = $httponly;
        return $this;
    }


    /**
     * 此参数为一个键值对关联 array，可能包含的键有：
     * lifetime，path，domain, secure，httponly 以及 samesite。 
     * 这些键对应的值和上面所述的一样。 
     * samesite 键对应的值可以是 Lax 或者 Strict。 
     * 如果可以接受的键在传入的数组中不存在， 那么会采用这些键对应的默认值作为运行时的值。 如果不提供 samesite 键， 那么就设置 SameSite cookie 属性
     *
     * @param array $options
     * @return void
     */
    public function options(array $options)
    {
        $this->parameter['options'] = $options;
        return $this;
    }


    /**
     * 回调函数类似于类的构造函数
     *
     * @param [type] $savePath
     * @param [type] $sessionName
     * @return void
     */
    function open(string $savePath, string $sessionName): bool
    {
        return true;
    }


    /**
     * 当前会话状态
     *
     * @return void
     */
    public function status()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * 回调函数类似于类的析构函数
     *
     * @return void
     */
    function close(): bool
    {
        return true;
    }


    /**
     * 如果会话中有数据，read 回调函数必须返回将会话数据编码（序列化）后的字符串
     *
     * @param string $sessionId
     * @return void
     */
    function read(string $sessionId): string
    {
        $value = $this->cache($sessionId);
        return $value ? $value : "";
    }


    /**
     * 在会话保存数据时会调用 write 回调函数
     *
     * @param string $sessionId 会话Id
     * @param string $data 序列化后的数据
     * @return void
     */
    function write(string $sessionId, string $data)
    {
        return $this->cache($sessionId, $data, $this->parameter['lifetime']);
    }


    /**
     * 当调用 session_destroy() 函数
     *
     * @param [type] $sessionId 会话Id
     * @return void
     */
    function destroy($sessionId)
    {
        return $this->cache((string) $sessionId);
    }


    /**
     * 为了清理会话中的旧数据，PHP 会不时的调用垃圾收集回调函数
     *
     * @param [type] $lifetime
     * @return void
     */
    function gc($lifetime): bool
    {
        return true;
    }



    public function __call($method, $args)
    {
        // 初始化
        $this->initi();
        return call_user_func_array([$this, $method], $args);
    }


    /**
     * redis 缓存设置
     *
     * @param string $name
     * @param string $value
     * @param integer $options
     * @return void
     */
    public function cache(string $name, $value = '', int $options = 60)
    {
        $cache = $this->redis;
        $name = 'session_' . $name;

        if ('' === $value) {
            // 获取缓存
            return json_decode($cache->get($name), true);
        } elseif (is_null($value)) {
            // 删除缓存
            return $cache->del($name);
        } else {
            // 缓存数据
            $expire = (int) $options;
            return $cache->setex($name, $expire, json_encode($value, JSON_UNESCAPED_UNICODE));
        }
    }


    /**
     * 设置数据
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    protected function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return isset($_SESSION[$key]) ? true : false;
    }


    /**
     * 读取数据
     *
     * @param [type] $key
     * @return void
     */
    protected function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}
