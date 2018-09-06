# easy-geetest

### 项目介绍
easy极验 PHP SDK
由于官方PHP SDK年代久远 不支持Namespace、Composer，于是在官方基础上制作了Easy极验PHP SDK

### 环境需求

    1.PHP >= 7.1

### 使用说明

    use Guowoo\EasyGeetest\EasyGeetest;
    
    $geetest = new EasyGeetest([
        'captcha_id' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'private_key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    ]);
    
    //验证结果
    $geetest->successValidate(
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx|xxxxxx',
        [
            "user_id" => "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
            "client_type" => "web",
            "ip_address" => "127.0.0.1",
        ]
    );

### 参与贡献

1. Fork 本项目
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request