<?php
namespace Funny;

use Funny\Router\CollectionInterface;
use Funny\Router\OptionsAbstract;

/**
 * Interface RouterInterface
 * @package Funny
 */
interface RouterInterface
{
    /**
     * RouterInterface constructor.
     * @param bool $cached
     * @param string $path
     */
    public function __construct($cached = true, $path = '');

    /**
     * 设置通用跨域处理类
     * @param OptionsAbstract $handler
     */
    public function setOptionsHandler(OptionsAbstract $handler);

    /**
     * 设置指定方法请求允许方法
     * @param $name
     * @return mixed
     */
    public function setMethodAccept($name);

    /**
     * 设置指定方法请求不允许方法
     * @param $name
     * @return mixed
     */
    public function setMethodNotAccept($name);

    /**
     * HTTP HEAD
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function head($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP GET
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function get($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP POST
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function post($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP PUT
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function put($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP DELETE
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function delete($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP PATCH
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     */
    public function patch($path, $callback, $optionsMode = OptionsAbstract::NONE);

    /**
     * HTTP OPTIONS
     * @param string $path
     * @param callable|array $callback
     * @return $this
     */
    public function options($path, $callback);

    /**
     * Mount routes
     * @param CollectionInterface|array $collect
     */
    public function mount($collect);

    /**
     * 进行路由解析准备
     * @param string $method
     * @param string $path
     * @param callable|array $handle
     * @param array $events
     * @param int $optionsMode
     * @throws RouterException
     * @throws Router\TreeException
     */
    public function add($method, $path, $handle, $events = [], $optionsMode = OptionsAbstract::NONE);

    /**
     * 执行路由操作
     * @param string $url
     * @param string $method
     * @return array|false
     */
    public function handle($url, $method);

    /**
     * 没有匹配URL
     * @param $callback
     * @return mixed
     */
    public function notFound($callback);

    /**
     * 获取路由树
     * @return array
     */
    public function getRouteTree();

    /**
     * 获取当前使用中的叶子节点数据
     * @return array|false
     */
    public function getActiveNode();
}