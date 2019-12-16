<?php

namespace homevip\helper;


interface ISession
{
	/**
	 * 初始化参数
	 *
	 * @return void
	 */
	public function initi();


	/**
	 * 回调函数类似于类的构造函数
	 *
	 * @param [type] $savePath
	 * @param [type] $sessionName
	 * @return void
	 */
	function open(string $savePath, string $sessionName): bool;


	/**
	 * 当前会话状态
	 *
	 * @return void
	 */
	public function status();


	/**
	 * 回调函数类似于类的析构函数
	 *
	 * @return void
	 */
	function close(): bool;


	/**
	 * 如果会话中有数据，read 回调函数必须返回将会话数据编码（序列化）后的字符串
	 *
	 * @param string $sessionId
	 * @return void
	 */
	function read(string $sessionId): string;


	/**
	 * 在会话保存数据时会调用 write 回调函数
	 *
	 * @param string $sessionId 会话Id
	 * @param string $data 序列化后的数据
	 * @return void
	 */
	function write(string $sessionId, string $data);


	/**
	 * 当调用 session_destroy() 函数
	 *
	 * @param [type] $sessionId 会话Id
	 * @return void
	 */
	function destroy($sessionId);


	/**
	 * 为了清理会话中的旧数据，PHP 会不时的调用垃圾收集回调函数
	 *
	 * @param [type] $lifetime
	 * @return void
	 */
	function gc($lifetime): bool;
}
