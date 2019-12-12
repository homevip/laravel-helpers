<?php

namespace homevip\helper;

use Illuminate\Support\Facades\Crypt;

class Token implements IToken
{
    /**
     * 连贯操作方法
     *
     * @var array
     */
    private $token = [
        'iss'   => NULL,
        'iat'   => NULL,
        'exp'   => NULL,
        'aud'   => NULL,
        'sub'   => NULL,
        'key'   => NULL,
        'ip'    => NULL,
    ];


    /**
     * 状态模板
     *
     * @var array
     */
    protected $errorFormat = [
        'code'      => 0,
        'message'   => NULL,
        'data'      => NULL,
    ];

    private $errorCode = [
        '0'     => 'OK',
        '41000' => '不合法的 aud',
        '41001' => '不合法的 iss',
        '41002' => '不合法的 token',
        '41003' => '不合法的 使用令牌',
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

    public function exp(string $exp)
    {
        $this->token['exp'] = $exp;
        return $this;
    }

    public function aud(string $aud)
    {
        $this->token['aud'] = $aud;
        return $this;
    }

    public function sub(string $sub)
    {
        $this->token['sub'] = $sub;
        return $this;
    }

    public function nbf(string $nbf)
    {
        $this->token['nbf'] = $nbf;
        return $this;
    }


    /**
     * 初始化参数
     *
     * iss 	issuer 发起请求的来源用户
     * iat 	token创建时间，unix时间戳格式  => $_SERVER['REQUEST_TIME'],
     * exp	指定token的生命周期,unix时间戳格式 => $_SERVER['REQUEST_TIME'] + 7200,
     * aud	接收该token 的一方 可做权限判断
     * sub	非必须。该token所面向的用户、应用,可做应用模块限制
     * nbf	非必须。not before。如果当前时间在nbf里的时间之前，则Token不被接受；一般都会留一些余地，比如几分钟 => 1357000000
     * key	非必须。TokenID 针对当前token的唯一标识 => '222we',
     * ip	非必须。签发时请求者的IP,
     * ...	其他自定义
     *
     * @return void
     */
    public function initial()
    {
        $time               = time();
        $this->token['iss'] = $this->token['iss'] ?? $_SERVER['HTTP_HOST'];
        $this->token['iat'] = $this->token['iat'] ?? $time;
        $this->token['exp'] = $this->token['exp'] ?? $time + static::EXPIRES_IN;
        $this->token['aud'] = $this->token['aud'] ?? 'public_*';
        $this->token['sub'] = $this->token['sub'] ?? 'homevip@126.com';
        $this->token['key'] = substr(md5($time), 6, 5);
        $this->token['ip']  = getIP();
    }


    /**
     * 加密数据
     *
     * @param array $param
     * @return void
     */
    public function encrypt(array $param)
    {
        $this->initial();
        return Crypt::encrypt(array_merge($this->token, $param));
    }


    /**
     * 解密数据
     *
     * @param string $ciphertext
     * @return void
     */
    public function decrypt(string $ciphertext)
    {
        try {
            if ($result = Crypt::decrypt($ciphertext)) {
                if ($this->token['aud'] != $result['aud']) {
                    $this->errorFormat['code']    = 41000; // 验证 aud

                } elseif ($_SERVER['HTTP_HOST'] != $result['iss']) {
                    $this->errorFormat['code']    = 41001; // 颁发令牌是否与请求是同域

                } elseif (($result['iat'] + static::EXPIRES_IN) < time() || $result['exp'] < time()) {
                    $this->errorFormat['code']    = 41002; // Token 过期

                } elseif (getIP() != $result['ip']) {
                    $this->errorFormat['code']    = 41003; // 获取令牌的ip与使用者的ip 对比
                }
            }
        } catch (\Exception $e) {
            $this->errorFormat['code']  = 41002; // 验证 aud
            \Illuminate\Support\Facades\Log::channel('zdyErrorLog')->info($e->getMessage() . ' 异常抛出行号:' . __CLASS__ . ' 行号: ' . __LINE__);
        }

        // 返回解密信息
        $this->errorFormat['message']   = $this->errorCode[$this->errorFormat['code']];
        $this->errorFormat['data']      = $result ?? null;
        return $this->errorFormat;
    }
}
