##### 用于检测身份证号码的函数
```
    $nber = '123456789';
    $IDCard = new IDCard();
    dd($IDCard::isCard($nber));
```