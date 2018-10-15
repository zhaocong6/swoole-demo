<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-12
 * Time: 下午2:32
 */

class Client
{
    private $client;

    public function __construct()
    {
//        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
//        $this->client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
//        if (!$this->client->connect('127.0.0.1', 9510, 1)){
//            echo '服务器链接失败!';
//            die;
//        }
//
//        fwrite(STDOUT, '请输入:');
//
//        $msg = trim(fgets(STDIN));
//        $this->client->send($msg);
//
//        echo $this->client->recv();

        $http = new swoole_http_server("127.0.0.1", 9501);

        $http->on("request", function ($request, $response) {
            $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
            $client->connect("127.0.0.1", 9510, 0.5);
            //调用connect将触发协程切换
            $client->send("hello world from swoole");
            //调用recv将触发协程切换
            $ret = $client->recv();
            $response->header("Content-Type", "text/plain");
            $response->end($ret);
            $client->close();
        });

        $http->start();
    }
}

$client = new Client();
$client->connect();
