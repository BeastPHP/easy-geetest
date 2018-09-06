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

namespace Guowoo\EasyGeetest\Support;

class Config
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $config = $this->config;
        if (is_null($key)) {
            return null;
        }

        if (isset($config[$key])) {
            return $config[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        $segments = explode('.', $key);
        foreach ($segments as $segment) {
            if (!is_array($config) || !isset($config[$segment])) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }

    /**
     * Whether a offset exists.
     *
     * @see  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset
     *
     * @return bool true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned
     *
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * Offset to retrieve.
     *
     * @see  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset
     *
     * @return mixed Can return all value types
     *
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set.
     *
     * @see  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (isset($this->config[$offset])) {
            $this->config[$offset] = $value;
        }
    }

    /**
     * Offset to unset.
     *
     * @see  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset
     *
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if (isset($this->config[$offset])) {
            unset($this->config[$offset]);
        }
    }
}
