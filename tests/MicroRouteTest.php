<?php
namespace Tests;

use Funny\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class MicroRouteTest
 * @package Funny
 */
class MicroRouteTest extends TestCase
{
    /**
     * @var Router
     */
    protected $router = null;

    public function setUp()
    {
        parent::setUp();

        $this->router = new Router();
    }

    /**
     * @throws \Funny\RouterException
     */
    public function testRootPath()
    {
        $this->expectOutputString('/');

        $this->router->get('/', function () {
            echo '/';
        });

        ($this->router->handle('/', 'GET'))->dispatch();
    }

    /**
     * @throws \Funny\RouterException
     */
    public function testEcho()
    {
        $time = date('His');

        $this->expectOutputString($time);

        $this->router->get('/get', function () use ($time) {
            echo $time;
        });

        ($this->router->handle('/get', 'GET'))->dispatch();
    }

    /**
     * @throws \Funny\RouterException
     */
    public function testReturn()
    {
        $time = date('His');

        $this->router->get('/user/rank', function () use ($time) {
            return $time;
        });

        $returnValue = ($this->router->handle('/user/rank', 'get'))
            ->dispatch()
            ->getReturnValue()
        ;

        $this->assertEquals($time, $returnValue);
    }

    /**
     * @throws \Funny\RouterException
     */
    public function testRegex()
    {
        $time = date('His');

        $this->expectOutputString($time);

        $this->router->get('/user/{[0-9]+}', function ($param) {
            echo $param;
        });

        ($this->router->handle('/user/' . $time, 'get'))->dispatch();
    }

    /**
     * 测试多正则路由
     * @throws \Funny\RouterException
     */
    public function testMultiRegex()
    {
        $date = date('Ymd');
        $time = date('His');
        $name = 'sandy';

        $this->expectOutputString($date . $name . $time);

        $this->router->put("/word/rank/{[0-9]+}/abs/{[a-z]+}/filter/time/{[0-9]+}", function ($arg1, $arg2, $arg3) {
            echo $arg1, $arg2, $arg3;
        });

        ($this->router->handle("/word/rank/{$date}/abs/{$name}/filter/time/{$time}", 'put'))->dispatch();
    }

    /**
     * 测试URL未找到路由
     * @throws \Funny\RouterException
     */
    public function testNotFund()
    {
        $this->expectOutputString('<h1>Resource Not Found</h1>');

        $this->router->get('/user/{[a-z]+}', function () {});

        ($this->router->handle('/user/chunk', 'POST'))->dispatch();
    }
}
