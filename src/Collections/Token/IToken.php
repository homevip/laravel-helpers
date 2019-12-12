<?php

namespace homevip\helper;

interface IToken
{

    /**
     * 有效时间/秒
     */
    const EXPIRES_IN = 7200;

    /**
     * 指定token的生命周期,unix时间戳格式 => $_SERVER['REQUEST_TIME'] + 7200,
     *
     * @param string $exp
     * @return void
     */
    public function exp(string $exp);


    /**
     * 接收该token 的一方 可做权限判断
     *
     * @param string $aud
     * @return void
     */
    public function aud(string $aud);


    /**
     * 该token所面向的用户、应用,可做应用模块限制
     *
     * @param string $sub
     * @return void
     */
    public function sub(string $sub);


    /**
     * not before。如果当前时间在nbf里的时间之前，则Token不被接受；一般都会留一些余地，比如几分钟 => 1357000000
     *
     * @param string $nbf
     * @return void
     */
    public function nbf(string $nbf);


    /**
     * 初始化参数
     * 
     * @return void
     */
    public function initial();

    /**
     * 加密数据
     *
     * @param array $param
     * @return void
     */
    public function encrypt(array $param);


    /**
     * 解密数据
     *
     * @param string $ciphertext
     * @return void
     */
    public function decrypt(string $ciphertext);
}
