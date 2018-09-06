<?php
/*
 * This file is part of the easy-geetest package.
 *
 * (c) Maxwell Guo <beastmaxwell.guo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Guowoo\EasyGeetest;

use Guowoo\EasyGeetest\Support\Config;
use Guowoo\EasyGeetest\Traits\Request;

class Geetest
{
    use Request;

    const GT_SDK_VERSION = 'php_3.0.0';

    const CONNECT_TIMEOUT = 1;
    const SOCKET_TIMEOUT = 1;

    const GEETEST_API_URL = 'http://api.geetest.com/register.php';
    const GEETEST_API_VALIDATE = 'http://api.geetest.com/validate.php';

    public $response;

    private $config;

    /**
     * Geetest constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * 判断极验服务器是否down机
     *
     * @param array $param
     * @param int $newCaptcha
     *
     * @return bool
     */
    public function proProcess(array $param, int $newCaptcha = 1): bool
    {
        $result = false;
        $query = array(
            'gt' => $this->config->get('captcha_id'),
            'new_captcha' => $newCaptcha
        );

        $query = array_merge($query, $param);
        $response = $this->get(self::GEETEST_API_URL, $query);

        if (strlen($response['data']) != 32) {
            $this->failbackProcess();
        } else {
            $this->successProcess($response['data']);
            $result = true;
        }

        return $result;
    }

    /**
     * @param string $challenge
     *
     * @void
     */
    private function successProcess(string $challenge)
    {
        $challenge = md5($challenge . $this->config->get('private_key'));
        $result = array(
            'success' => 1,
            'gt' => $this->config->get('captcha_id'),
            'challenge' => $challenge,
            'new_captcha' => 1
        );
        $this->response = $result;
    }

    /**
     * @void
     */
    private function failbackProcess()
    {
        $rnd1 = md5(mt_rand(0, 100));
        $rnd2 = md5(mt_rand(0, 100));
        $challenge = $rnd1 . substr($rnd2, 0, 2);
        $result = array(
            'success' => 0,
            'gt' => $this->config->get('captcha_id'),
            'challenge' => $challenge,
            'new_captcha' => 1
        );
        $this->response = $result;
    }

    /**
     * @return mixed
     */
    public function getResponseStr()
    {
        return json_encode($this->response);
    }

    /**
     * 返回数组方便扩展
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 正常模式获取验证结果
     *
     * @param string $challenge
     * @param string $validate
     * @param string $seccode
     * @param array $param
     * @param int $jsonFormat
     *
     * @return bool
     */
    public function successValidate(
        string $challenge,
        string $validate,
        string $seccode,
        array $param,
        int $jsonFormat = 1
    ): bool {
        $result = false;

        if (!$this->checkValidate($challenge, $validate)) {
            return $result;
        }

        $query = array(
            'seccode' => $seccode,
            'timestamp' => time(),
            'challenge' => $challenge,
            'captchaid' => $this->config->get('captcha_id'),
            'json_format' => $jsonFormat,
            'sdk' => self::GT_SDK_VERSION
        );
        $query = array_merge($query, $param);

        $codeValidate = $this->post(self::GEETEST_API_VALIDATE, $query);
        $data = json_decode($codeValidate['data'], true);

        if ($data && $data['seccode'] == md5($seccode)) {
            $result = true;
        }

        return $result;
    }

    /**
     * 宕机模式获取验证结果
     *
     * @param string $challenge
     * @param string $validate
     *
     * @return bool
     */
    public function failValidate(string $challenge, string $validate): bool
    {
        $result = false;
        if (md5($challenge) == $validate) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param string $challenge
     * @param string $validate
     * @return bool
     */
    private function checkValidate(string $challenge, string $validate): bool
    {
        if (strlen($validate) != 32) {
            return false;
        }

        if (md5($this->config->get('private_key') . 'geetest' . $challenge) != $validate) {
            return false;
        }

        return true;
    }
}
