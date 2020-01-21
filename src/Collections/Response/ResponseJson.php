<?php

namespace homevip\helper;


trait ResponseJson
{


	private $package = array();


	/**
	 * 成功返回
	 *
	 * @param array $data	返回数据内容
	 * @return void
	 */
	public function outSuccess(array $data = [])
	{
		return $this->template(0, config('response_code')[0], $data);
	}


	/**
	 * 异常返回
	 *
	 * @param integer $code 错误码
	 * @return void
	 */
	public function outError(int $code)
	{
		return $this->template($code, config('response_code')[$code], []);
	}


	/**
	 * 返回数据模板
	 *
	 * @param [type] $code		错误码
	 * @param [type] $message	成功/错误 消息
	 * @param [type] $data		返回数据内容
	 * @return json
	 */
	private function template(int $code, string $message, array $data)
	{
		$this->package = [
			'code' 	=> $code,
			'msg' 	=> $message,
			'data' 	=> $data,
		];
		return response()->json($this->package);
	}
}
