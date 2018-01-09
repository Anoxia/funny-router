<?php
class TestCollect
{
    public function get(){print_r(func_get_args()); return 'get';}
    public function post(){print_r(func_get_args()); return 'post';}
    public function put(){print_r(func_get_args()); return 'put';}
    public function delete(){print_r(func_get_args()); return 'delete';}
    public function test(){print_r(func_get_args()); return 'test';}
    public function getUserArticle($id)
    {
        return $id;
    }
}


require __DIR__ . '/vendor/autoload.php';

$router = new \Funny\Router(true, __DIR__ . '/routes.php');

try {

//    $router
//        ->get('/user/{[a-z]+}/article/{[0-9]+}', function ($name, $articleID) {
//            var_dump($name);
//            var_dump($articleID);
//        })
//        ->get('/box/open', function () {})
//        ->get('/notice', function () {})
//        ->get('/package/list', function () {})
//        ->get('/word/collect', function () {})
//        ->get('/word/collect/times', function () {})
//        ->get('/word/rank', function () {})
//        ->get('/word/rank/user', function () {})
//        ->get('/box/release/surplus', function () {echo 'surplus';})
//        ->get('/box/open', function () {echo 'open';})
//        ->post('/box/{[a-z]+}/surplus', function () {echo 'regex';})
//        ->get('/box', function () {echo 'box';})
//    ;
//
//    $router->notFound(function () {
//        echo 404;
//    });
//
////    $router->handle('/box/abc/surplus', 'GET');
////    $router->handle('/user', 'GET');
////    $router->handle('/box', 'GET');
//    $handler = $router->handle('/user/hello/article/100', 'GET');
//
//    $handler->dispatch();

//    print_r($router->getRouteTree());

//    $collect = new \Funny\Router\Collection();
//
//    $collect->setHandler(\TestCollect::class)->setPrefix('/user');
//
////
//
//    // 事件路由
//    $collect
//        ->before([
//            [\TestCollect::class, 'get'],
//            'test'
//        ])
//        ->after(
//            TestCollect::class,
//            [
//                'put',
//                'delete'
//            ]
//        )
//        ->get('/rank', 'post')
//    ;
//
////     事件路由
//    $collect
//        ->before(
//            \TestCollect::class,
//            [
//                'get',
//                'test'
//            ]
//        )
//        ->get('/rank', 'get')
//    ;
//
//    $collect->group('\Service\Word', '/word', function (\Funny\Router\CollectionInterface $collection) {
//
//        $collection->setHandler('Rank')->setPrefix('/rank');
//
//        $collection
//            ->before([
//
//            ])
//            ->after([
//
//            ])
//            ->get('/times', 'list');
//
//        $collection->post('/times', 'post');
//    });

//    $collect
//        ->post('/article', 'post', 4)
//        ->get('/article/{[a-zA-Z0-9]+}', 'getUserArticle', 2)
//        ->get('/article', 'get', 3)
//    ;

//    print_r($collect->getUrls());

//    $router->notFound(function () {
//        print_r('404，路由未找到');
//    });
//
//    $router->mount($collect);
//
////    print_r($router->getRouteTree());
//
//    $handler = $router->handle('/user/rank', 'get');
//
//    $handler->dispatch();

    $routes = [
        'GET' => [
            '/user' => ['\TestCollect', 'test', 3],
            '/user/rank' => [TestCollect::class, 'get', 2]
        ],
        'POST' => [
            '/user' => [TestCollect::class, 'test', 3],
            '/user/rank' => [TestCollect::class, 'post', 2],
            '/box' => []
        ]
    ];

    $router->mount($routes);

    $handler = $router->handle('/user/r', 'POST');

    $handler->dispatch(['hello' => 'word']);

//    var_dump($handler->getReturnValue());

} catch (\Exception $e) {
    print_r($e);
}

//preg_match('/(?:\{)(.*)(?:\})/i', '{[a-z]+}', $match);
//
//print_r($match);

//preg_match('/^[0-9]+/', '12734', $match);
//
//print_r($match);

//var_dump(gettype(\Funny\Router::class));

//echo \Funny\Router::class;

