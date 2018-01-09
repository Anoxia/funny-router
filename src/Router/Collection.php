<?php
namespace Funny\Router;

/**
 * 路由规则收集器
 * @package Funny\Router
 */
class Collection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var string
     */
    protected $groupPrefix = '';

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var array
     */
    protected $beforeEvents = [];

    /**
     * @var array
     */
    protected $afterEvents = [];

    /**
     * Collection constructor.
     * @param null $class
     * @param string $prefix
     */
    public function __construct($class = null, $prefix = '')
    {
        if (!empty($class)) {
            $this->class = $class;
        }

        if (!empty($prefix)) {
            $this->prefix = $prefix;
        }
    }

    /**
     * 解析并完成URL组装
     * @param string $method
     * @param string $routePattern
     * @param array $handle
     * @param $options
     * @throws CollectionException
     */
    private function create($method, $routePattern, $handle, $options)
    {
        // Handle Class检查
        if (empty($handle[0])) {
            throw new CollectionException("Handler class不能为空", 500);
        }

        // 创建URL
        $url = [];

        // 路由方法
        $url['method']  = $method;
        // 路由URL
        $url['path']    = $this->prefix . $routePattern;
        // 路由handle
        $url['handle']  = $handle;
        // 跨域options
        $url['options'] = $options;

        // 路由事件
        $url['events']  = [];
        if (count($this->beforeEvents)) {
            $url['events']['before'] = $this->beforeEvents;
        }
        if (count($this->afterEvents)) {
            $url['events']['after'] = $this->afterEvents;
        }

        // 装载一条路由规则
        $this->urls[] = $url;

        // 清空前置事件
        if (count($this->beforeEvents)) {
            $this->beforeEvents = [];
        }

        // 清空后置事件
        if (count($this->afterEvents)) {
            $this->afterEvents  = [];
        }
    }

    /**
     * Handler class
     * @param string $class
     * @return $this
     */
    public function setHandler($class)
    {
        if (!empty($this->namespace)) {
            // 存在路由组定义
            $this->class = $this->namespace . '\\' . trim($class, '\\');
        } else {
            $this->class = '\\' . trim($class, '\\');
        }

        return $this;
    }

    /**
     * 获取handler class
     * @return string
     */
    public function getHandler()
    {
        return $this->class;
    }

    /**
     * 设置URL前缀
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        if (!empty($this->groupPrefix)) {
            // 存在路由组定义
            $this->prefix = $this->groupPrefix . $prefix;
        } else {
            $this->prefix = $prefix;
        }

        return $this;
    }

    /**
     * 获取URL前缀
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * todo: handle class检查
     * Cet Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     * @throws CollectionException
     */
    public function get($routePattern, $handle, $optionsMode = Options::NONE)
    {
        // 创建一条URL规则
        $this->create('GET', $routePattern, [$this->class, $handle], $optionsMode);

        return $this;
    }

    /**
     * Post Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     * @throws CollectionException
     */
    public function post($routePattern, $handle, $optionsMode = Options::NONE)
    {
        // 创建一条URL规则
        $this->create('POST', $routePattern, [$this->class, $handle], $optionsMode);

        return $this;
    }

    /**
     * Put Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     * @throws CollectionException
     */
    public function put($routePattern, $handle, $optionsMode = Options::NONE)
    {
        // 创建一条URL规则
        $this->create('PUT', $routePattern, [$this->class, $handle], $optionsMode);

        return $this;
    }

    /**
     * Delete Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     * @throws CollectionException
     */
    public function delete($routePattern, $handle, $optionsMode = Options::NONE)
    {
        // 创建一条URL规则
        $this->create('DELETE', $routePattern, [$this->class, $handle], $optionsMode);

        return $this;
    }

    /**
     * Patch Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     * @throws CollectionException
     */
    public function patch($routePattern, $handle, $optionsMode = Options::NONE)
    {
        // 创建一条URL规则
        $this->create('PATCH', $routePattern, [$this->class, $handle], $optionsMode);

        return $this;
    }

    /**
     * Options Method
     * @param string $routePattern
     * @param callable $handle
     * @return $this
     * @throws CollectionException
     */
    public function options($routePattern, $handle)
    {
        // 创建一条URL规则
        $this->create('OPTIONS', $routePattern, [$this->class, $handle], Options::NONE);

        return $this;
    }

    /**
     * 前置事件
     * @param array|string $mixed
     * @param array $events
     * @return $this
     * @throws CollectionException
     */
    public function before($mixed, $events = [])
    {
        $this->beforeEvents = $this->assembleEvent($mixed, $events);

        return $this;
    }

    /**
     * 后置事件
     * @param array|string $mixed
     * @param array $events
     * @return $this
     * @throws CollectionException
     */
    public function after($mixed, $events = [])
    {
        $this->afterEvents = $this->assembleEvent($mixed, $events);

        return $this;
    }

    /**
     * 组装路由事件
     * @param $mixed
     * @param $events
     * @return array|string
     * @throws CollectionException
     */
    public function assembleEvent($mixed, $events)
    {
        // handler组
        $handlers = [];

        if (count($events)) {
            $class = '\\' . trim($mixed, '\\');

            // 组装方法与类绑定
            foreach ($events as $event) {
                $handlers[] = [$class, $event];
            }
        } else {

            // 数组callable
            if (is_array($mixed)) {

                // 命名空间处理
                foreach ($mixed as $item) {
                    // 数组callback
                    if (is_array($item)) {
                        // 转换命名空间
                        $item[0]    = '\\' . trim($item[0], '\\');

                        $handlers[] = $item;
                    }
                    // 字符串callback
                    elseif (is_string($item)) {
                        if (method_exists($this->class, $item)) {
                            $handlers[] = [$this->class, $item];
                        } else {
                            $handlers[] = $item;
                        }
                    }
                    // 参数不合法
                    else {
                        throw new CollectionException("参数必须字符串(可执行函数)或数组(可调用函数方法集合)", 500);
                    }
                }
            }

            // 字符串，优先匹配当前handle class，否则视为函数处理
            elseif (is_string($mixed)) {
                if (method_exists($this->class, $mixed)) {
                    $handlers = [$this->class, $mixed];
                } else {
                    $handlers = $mixed;
                }
            }

            // 参数不合法
            else {
                throw new CollectionException("参数必须字符串(可执行函数)或数组(可调用函数方法集合)", 500);
            }
        }

        return $handlers;
    }

    /**
     * 定义路由组
     * @param string $namespace
     * @param string $prefix
     * @param callable $callback
     */
    public function group($namespace, $prefix, $callback)
    {
        // 备份class
        $backupClass  = $this->getHandler();
        // 备份URL前缀
        $backupPrefix = $this->getPrefix();

        // 命名空间前缀
        $this->namespace    = $namespace;
        // 设置组路由前缀
        $this->groupPrefix  = $prefix;

        // 执行路由定义回调
        call_user_func_array($callback, [$this]);

        // 清空命名空间前缀
        $this->namespace    = '';
        // 清空组路由前缀
        $this->groupPrefix  = '';

        // 恢复class
        $this->setHandler($backupClass);
        // 恢复URL前缀
        $this->setPrefix($backupPrefix);
    }

    /**
     * 获取当前对象上面的所有路由规则
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }
}
