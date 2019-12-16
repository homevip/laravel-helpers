<?php

namespace homevip\helper;


interface ISessionHandler
{
	/**
	 * 初始化参数
	 *
	 * @return void
	 */
	public function initi();


	/**
	 * 设置会话名称
	 *
	 * @param string $session_name
	 * @return void
	 */
	public function session_name(string $session_name);


	/**
	 * Cookie 的 生命周期，以秒为单位
	 *
	 * @param integer $lifetime
	 * @return void
	 */
	public function lifetime(int $lifetime);


	/**
	 * 此 cookie 的有效 路径。 on the domain where 设置为“/”表示对于本域上所有的路径此 cookie 都可用
	 *
	 * @param string $path
	 * @return void
	 */
	public function path(string $path);


	/**
	 * Cookie 的作用 域。 例如：“www.php.net”。 如果要让 cookie 在所有的子域中都可用，此参数必须以点（.）开头，例如：“.php.net”
	 *
	 * @param string $domain
	 * @return void
	 */
	public function domain(string $domain);


	/**
	 * 设置为 TRUE 表示 cookie 仅在使用 安全 链接时可用
	 *
	 * @param boolean $secure
	 * @return void
	 */
	public function secure(bool $secure);


	/**
	 * 设置为 TRUE 表示 PHP 发送 cookie 的时候会使用 httponly 标记
	 *
	 * @param boolean $httponly
	 * @return void
	 */
	public function httponly(bool $httponly);


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
	public function options(array $options);
}
