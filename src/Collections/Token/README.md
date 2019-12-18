##### 使用
```
use homevip\helper;

# 加密
Token::instance()
    ->exp('指定过期时间 [时间戳]')
    ->aud('自定义参数, 可做参数判断')
    ->sub('xxx')
    ->encrypt();

# 解密
Token::instance()->decrypt($token);
```