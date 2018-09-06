<?php
/*
 * This file is part of the easy-geetest package.
 *
 * (c) Maxwell Guo <beastmaxwell.guo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Guowoo\EasyGeetest\Geetest;

require __DIR__ . '/../vendor/autoload.php';

$geetest = new Geetest([
    'captcha_id' => '42f869801ce42186f7241d6f826e4993',
    'private_key' => '909aba1631b9c1a5ede45e8afb6a9995',
]);

//$geetest->proProcess([]);

$geetest->successValidate(
    'a3a588d3852d512dc6bff526439041d7',
    'a79ca9222ab5b7231ed5ed6c535597e8',
    'a79ca9222ab5b7231ed5ed6c535597e8|jordan',
    [
        "user_id" => "a3ddcf3bea7f2d0bbadbc0b8a72d3e99",
        "client_type" => "web",
        "ip_address" => "127.0.0.1",
    ]
);