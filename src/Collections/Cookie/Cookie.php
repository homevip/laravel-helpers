<?php

namespace homevip\helper;

class Cookie
{
	/**
	 * 连贯操作方法
	 *
	 * @var array
	 */
	private $options = [
		'expire' 	=> NULL, // 设置过期时间[时间戳],默认值 0
		'path' 		=> NULL, // Cookie 的有效路径
		'domain' 	=> NULL, // Cookie 的作用域,默认在本域下
		'secure' 	=> NULL, // 设置 Cookie 只能通过 https传输,默认值 false
		'httponly' 	=> NULL, // 是否使用 https访问 Cookie,默认 false,如果设置成true, 客户端 js 无法操作这个 Cookie了,使用这个参数可以减少 XSS攻击
	];

	public function expire(string $expire)
	{
		$this->options['expire'] = $expire;
		return $this;
	}

	public function path(string $path)
	{
		$this->options['path'] = $path;
		return $this;
	}

	public function domain(string $domain)
	{
		$this->options['domain'] = $domain;
		return $this;
	}

	public function secure(string $secure)
	{
		$this->options['secure'] = $secure;
		return $this;
	}

	public function httponly(string $httponly)
	{
		$this->options['httponly'] = $httponly;
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
	public function initi()
	{
		$this->options['expire'] 	= empty($this->options['expire']) ? 0 : time() + $this->options['expire'];
		$this->options['path'] 		= $this->options['path'] ?? '/';
		$this->options['domain'] 	= $this->options['domain'] ?? '.' . $_SERVER['HTTP_HOST'];
		$this->options['secure'] 	= $_SERVER['REQUEST_SCHEME'] == 'http' ? false : true;
		$this->options['httponly'] 	= $this->options['httponly'] ?? true;
	}


	public function __call($method, $args)
	{
		// 初始化
		$this->initi();
		return call_user_func_array([$this, $method], $args);
	}


	/**
	 * 设置 Cookie
	 *
	 * @param [type] $key
	 * @param [type] $value
	 * @return void
	 */
	protected function set(string $key, $value)
	{
		return setcookie($key, $value, $this->options['expire'], $this->options['path'], $this->options['domain'], $this->options['secure'], $this->options['httponly']);
	}

	/**
	 * 更新 Cookie
	 *
	 * @param [type] $key
	 * @param [type] $value
	 * @return void
	 */
	protected function update(string $key, $value)
	{
		return setcookie($key, $value, $this->options['expire'], $this->options['path'], $this->options['domain'], $this->options['secure'], $this->options['httponly']);
	}


	/**
	 * 删除 Cookie
	 *
	 * @param string $key
	 * @return void
	 */
	protected function del(string $key)
	{
		return setcookie($key, '', $this->options['expire'] - 1, $this->options['path'], $this->options['domain'], $this->options['secure'], $this->options['httponly']);
	}
}
