<?php

namespace homevip\helper;

class Cookie
{
	/**
	 * 定义实例
	 *
	 * @var [type]
	 */
	private static $instance;

	private $expire 	= 0; 		// 设置过期时间[时间戳],默认值 0
	private $path 		= '/'; 		// Cookie 的有效路径
	private $domain 	= ''; 		// Cookie 的作用域,默认在本域下
	private $secure 	= false; 	// 设置 Cookie 只能通过 https传输,默认值 false
	private $httponly 	= true; 	// 是否使用 https访问 Cookie,默认 false,如果设置成`true, 客户端 js 无法操作这个 Cookie了,使用这个参数可以减少 XSS攻击


	/**
	 * 初始化操作
	 *
	 * @param array $options
	 */
	private function __construct(array $options = [])
	{
		// 设置相关选项
		$this->setOptions($options);
	}


	/**
	 * 设置相关选项
	 *
	 * @param array $options
	 * @return void
	 */
	private function setOptions(array $options = [])
	{
		if (isset($options['expire'])) {
			$this->expire = (int) time() + $options['expire'];
		}

		if (isset($options['path'])) {
			$this->path = $options['path'];
		}

		if (isset($options['domain'])) {
			$this->domain = $options['domain'];
		} else {
			$this->domain = '.' . $_SERVER['HTTP_HOST'];
		}

		if (isset($options['secure'])) {
			$this->secure = (bool) $options['secure'];
		}

		if (isset($options['httponly'])) {
			$this->httponly = (bool) $options['httponly'];
		}
	}


	/**
	 * 返回静态实例
	 *
	 * @return void
	 */
	public static function instance(array $options = [])
	{
		if (is_null(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class($options);
		}
		return self::$instance;
	}


	/**
	 * 设置 Cookie
	 *
	 * @param string $name 	Cookie 名称
	 * @param [string || array] $value	Cookie 值
	 * @param array $options
	 * @return void
	 */
	public function set(string $name, $value, array $options = [])
	{
		if (is_array($options) && count($options) > 0) {
			$this->setOptions($options);
		}

		if (is_array($value) || is_object($value)) {
			$value = json_encode($value, JSON_UNESCAPED_UNICODE);
		}
		return setcookie($name, $value, $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
	}


	/**
	 * 读取 Cookie
	 *
	 * @param string $name
	 * @return void
	 */
	public function get(string $name)
	{
		if (isset($_COOKIE[$name])) {

			if (is_json($_COOKIE[$name])) {
				return json_decode($_COOKIE[$name], true);
			}
			return $_COOKIE[$name];
		}
		return null;
	}


	/**
	 * 更新 Cookie
	 *
	 * @param string $name 	Cookie 名称
	 * @param [string || array] $value	Cookie 值
	 * @param array $options
	 * @return void
	 */
	public function update(string $name, $value, array $options = [])
	{
		return $this->set($name, $value, $options);
	}

	/**
	 * 删除 Cookie
	 *
	 * @param string $name
	 * @return void
	 */
	public function del(string $name)
	{
		return $this->set($name, '', ['expire' => time() - 1]);
	}
}
