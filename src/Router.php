<?php
namespace Funny;

use Funny\Router\Collection;
use Funny\Router\Handler;
use Funny\Router\Options;
use Funny\Router\OptionsAbstract;
use Funny\Router\Tree;

/**
 * Router
 * @package Funny
 */
class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected $supportMethods = [
        'HEAD'    => true,
        'GET'     => true,
        'POST'    => true,
        'PUT'     => true,
        'DELETE'  => true,
        'PATCH'   => true,
        'OPTIONS' => true
    ];

    /**
     * @var bool
     */
    protected $cached = false;

    /**
     * @var string
     */
    protected $cacheFile = '';

    /**
     * @var array
     */
    protected $notFoundHandle = [];

    /**
     * @var Tree
     */
    protected $tree = null;

    /**
     * @var null
     */
    protected $attachNode = null;

    /**
     * @var Handler
     */
    protected $handler = null;

    /**
     * @var OptionsAbstract
     */
    protected $optionsHandler = null;

    /**
     * Router constructor.
     * @param bool $cached
     * @param string $file
     * @throws RouterException
     */
    public function __construct($cached = false, $file = '')
    {
        // 开启路由缓存
        if ($cached === true) {
            if (empty($file)) {
                throw new RouterException('检测到你已经开启路由缓存, 必须指定缓存目标文件', 500);
            }

            $this->cached    = true;
            $this->cacheFile = $file;
        }

        // 默认notFound处理方法
        $this->notFoundHandle = [$this, 'defaultNotFoundHandle'];

        // 设置跨域处理类
        $this->optionsHandler = new Options();

        // 路由树基类
        $this->tree = new Tree();
    }

    /**
     * 设置通用跨域处理类
     * @param OptionsAbstract $handler
     */
    public function setOptionsHandler(OptionsAbstract $handler)
    {
        $this->optionsHandler = $handler;
    }

    /**
     * 设置指定方法请求允许方法
     * @param $name
     * @throws RouterException
     */
    public function setMethodAccept($name)
    {
        if (!isset($this->supportMethods[$name])) {
            throw new RouterException("方法 {$name} 不存在", 500);
        }

        $this->supportMethods[$name] = true;
    }

    /**
     * 设置指定方法请求不允许方法
     * @param $name
     * @throws RouterException
     */
    public function setMethodNotAccept($name)
    {
        if (!isset($this->supportMethods[$name])) {
            throw new RouterException("方法 {$name} 不存在", 500);
        }

        $this->supportMethods[$name] = false;
    }

    /**
     * Get Method
     * @param string $path
     * @param array|callable $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function head($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('HEAD', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * Get Method
     * @param string $path
     * @param array|callable $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function get($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('GET', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * Post Method
     * @param string $path
     * @param array|callable $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function post($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('POST', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * Put Method
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function put($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('PUT', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * Delete Method
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function delete($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('DELETE', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * Patch Method
     * @param string $path
     * @param callable|array $callback
     * @param int $optionsMode
     * @return $this
     * @throws RouterException
     */
    public function patch($path, $callback, $optionsMode = OptionsAbstract::NONE)
    {
        $this->add('PATCH', $path, $callback, [], $optionsMode);

        return $this;
    }

    /**
     * OptionsAbstract Method
     * @param string $path
     * @param callable|array $callback
     * @return $this
     * @throws RouterException
     */
    public function options($path, $callback)
    {
        $this->add('PATCH', $path, $callback);

        return $this;
    }

    /**
     * 挂载路由规则
     * @param array|Collection $collect
     * @throws RouterException
     */
    public function mount($collect)
    {
        // 数据路由
        if (is_array($collect)) {

            foreach ($collect as $method => $group) {
                // 以HTTP方法分组的路由
                if (isset($this->supportMethods[strtoupper($method)])) {

                    // 遍历组装路由
                    foreach ($group as $url => $definition) {

                        // 未填写定义，跳过
                        if (empty($definition)) {
                            continue;
                        }

                        // 参数过少
                        if (count($definition) < 2) {
                            throw new RouterException("'{$url}'路由的定义数组参数不合法，参数小于两个", 500);
                        }

                        // 检测方法是否存在
                        $handlerClass   = '\\' . trim($definition[0], '\\');
                        $handlerMethod  = $definition[1];

                        // 跨域设置
                        $options = 0;
                        if (isset($definition[2])) {
                            $options = $definition[2];
                        }

                        // 添加路由
                        $this->add($method, $url, [$handlerClass, $handlerMethod], [], $options);
                    }
                }
                // 非法参数
                else {
                    // todo: 下一版本改造成使用反射API获取错误参数的行和列
                    throw new RouterException("不被支持的路由定义：{$method}", 500);
                }
            }
        }
        // 对象路由
        elseif ($collect instanceof Collection) {

            // 获取所有路由规则
            $urls = $collect->getUrls();

            // 挂载路由
            foreach ($urls as $url) {
                $this->add($url['method'], $url['path'], $url['handle'], $url['events'], $url['options']);
            }

        } else {
            throw new RouterException("路由挂载失败，检测到不被支持的路由定义方式", 500);
        }
    }

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
    public function add($method, $path, $handle, $events = [], $optionsMode = OptionsAbstract::NONE)
    {
        // 转换成大写
        $method = strtoupper($method);

        if (!isset($this->supportMethods[$method])) {
            throw new RouterException("不支持的方法: {$method}", 406);
        }

        // 生成options跨域回调
        $optionsHandle = $this->optionsHandler->getMethodHandle($optionsMode);

        // 添加options方法
        if (!empty($optionsHandle)) {
            // 添加对应的OPTIONS方法
            $this->tree->add('OPTIONS', $path, $optionsHandle);

            // 创建options事件回调
            $events['options'] = $optionsHandle;
        }

        // 添加至路由树
        $this->tree->add($method, $path, $handle, $events);
    }

    /**
     * 执行路由操作
     * @param string $url
     * @param string $method
     * @return Handler|false
     * @throws RouterException
     */
    public function handle($url, $method)
    {
        $method = strtoupper($method);

        if (!isset($this->supportMethods[$method])) {
            throw new RouterException("不支持的方法: {$method}", 406);
        }

        if ($this->supportMethods[$method] === false) {
            throw new RouterException("'{$method}'方法已被禁用", 405);
        }

        // 执行路由匹配
        $node = $this->tree->match($method, $url);

        // 未匹配到任何路由
        if ($node === false) {

            // 手动创建未找到节点
            $this->attachNode = [
                'handle'    => $this->notFoundHandle,
                'events'    => [],
                'matchVars' => []
            ];

        } else {
            $this->attachNode = $node;
        }

        // 叶子节点实例化
        $this->handler = new Handler(
            $this->attachNode['handle'],
            $this->attachNode['events'],
            $this->attachNode['matchVars']
        );

        return $this->handler;
    }

    /**
     * 没有匹配URL
     * @param callable|array $callback
     * @return mixed|void
     * @throws RouterException
     */
    public function notFound($callback)
    {
        if (is_callable($callback)) {
            $this->notFoundHandle = $callback;
        } else {
            throw new RouterException("notFound方法参数必须为匿名函数或可调用的方法", 500);
        }
    }

    /**
     * 默认URL未找到方法
     */
    public function defaultNotFoundHandle()
    {
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        echo '<h1>Resource Not Found</h1>';
    }

    /**
     * 获取路由树
     * @return array
     */
    public function getRouteTree()
    {
        return $this->tree->export();
    }

    /**
     * 获取当前使用中的叶子节点数据
     * @return array|false
     */
    public function getActiveNode()
    {
        return $this->attachNode;
    }
}
