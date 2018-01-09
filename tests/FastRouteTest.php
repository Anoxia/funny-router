<?php
namespace Test;

use Funny\Router;
use Funny\RouterException;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

/**
 * 快速定义路由测试
 * @package Test
 */
class FastRouteTest extends TestCase
{
    public function createRouter()
    {
        return new Router();
    }

    /**
     * @depends createRouter
     * @param Router $router
     */
    public function definitionTest(Router $router)
    {

        try {
            $router
                ->get('/user/{name:[a-z]+}', function () {})
                ->get('/box/open', function () {})
                ->get('/notice', function () {})
                ->get('/package/list', function () {})
                ->get('/word/collect', function () {})
                ->get('/word/collect/times', function () {})
                ->get('/word/rank', function () {})
                ->get('/word/rank/user', function () {})
                ->get('/box/release/surplus', function () {});

            $router->handle('/', 'GET');

        } catch (RouterException $e) {
        }
    }
}