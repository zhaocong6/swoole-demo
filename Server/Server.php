<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-12
 * Time: 下午2:25
 */


/**
 * 一个php程序只能创建启动一个Server实例
 *
 * Class Server
 */
class Server
{
    private $serv;

    public function __construct()
    {
        /**
         * 0.0.0.0 监听所有ip地址, 监听单个地址 (如:127.0.0.1)
         * 9501 监听端口
         * SWOOLE_PROCESS 多进程运行模式
         * SWOOLE_SOCK_TCP socket类型
         */
        $this->serv = new swoole_server('0.0.0.0', 9510, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $this->serv->set([
            'reactor_num'   =>  2,   //reactor 线程数
            'worker_num'    =>  4,   //worker 进程数 配置为CPU核数的1-4倍即可
            'backlog'       =>  128, //listen backlog
            'max_request'   =>  50,  //worker进程在处理完n次请求后结束运行, manager会重新创建一个worker进程。此选项用来防止worker进程内存溢出。
            //PHP代码也可以使用memory_get_usage来检测进程的内存占用情况，发现接近memory_limit时，调用exit()退出进程。manager进程会回收此进程，然后重新启动一个新的Worker进程。
            'max_conn'      =>  10000,//此参数用来设置Server最大允许维持多少个tcp连接。超过此数量后，新进入的连接将被拒绝
//            'daemonize'     =>  true,//守护进程
//            'dispatch_mode' =>  1,//worker进程数据包分配模式, 1 //1平均分配，2按FD取模固定分配，3抢占式分配，默认为取模(dispatch=2)
        ]);




        $this->serv->on('Start',   [$this, 'onStart']);
        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Close',   [$this, 'onClose']);

        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "服务开启 \n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        $serv->send($fd, '你好: 链接号码.'.$fd);
    }

    public function onReceive(swoole_server $server, $fd, $from_id, $data)
    {
        echo '完成链接.';

        $server->tick(1000, function ()use($server, $fd){
            file_put_contents('./text.txt', "hello \n", FILE_APPEND);
        });

        $server->send($fd, $data);
    }

    public function onClose()
    {
        echo '关闭链接';
    }
}

$server = new Server();
