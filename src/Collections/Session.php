<?php

namespace homevip\helper;


class Session
{
	/**
	 * 有效时间/秒
	 */
	const EXPIRES_IN = 7200;


	/**
	 * 连贯操作方法
	 *
	 * @var array
	 */
	private $parameter = [
		'session_name' 	=> NULL,
        'lifetime' 		=> NULL,
		'path' 			=> NULL,
		'domain' 		=> NULL,
		'secure' 		=> NULL,
		'httponly' 		=> NULL,
		'options' 		=> NULL,
	];


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
	private function initial()
	{
		$this->parameter['session_name']  	= $this->parameter['session_name'] 	?? 'HOMEVIP_ID';
		$this->parameter['lifetime']  		= $this->parameter['lifetime'] 		?? self::EXPIRES_IN;
		$this->parameter['path'] 			= $this->parameter['path'] 			?? '/';
		$this->parameter['domain']  		= $this->parameter['domain'] 		?? $_SERVER['SESSION_DOMAIN'];
		$this->parameter['secure']  		= $this->parameter['secure'] 		?? $_SERVER['REQUEST_SCHEME'] ? false : true;
		$this->parameter['httponly']  		= $this->parameter['httponly'] 		?? true;
		$this->parameter['options']  		= $this->parameter['options'] 		?? [];

		// 
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

	public function __call($method, $args)
	{
		$this->initial();
		return call_user_func_array([$this, $method], $args);
	}


	/**
	 * 设置 session
	 *
	 * @return void
	 */
	protected function set()
	{
		return true;
	}
}
