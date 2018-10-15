<?php

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server('0.0.0.0', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $this->serv->set([
            'reactor_num'   => 20,
            'worker_num'    => 20,
            'max_request'   => 10000,
            'max_conn'      => 1000,
            'dispatch_mode' => 1,
            'task_worker_num'=> 20
        ]);

        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Task', [$this, 'onTask']);
        $this->serv->on('finish', function ($serv, $task_id, $data) {
            echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
        });
        $this->serv->start();
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        $de_data = json_decode($data, true);
        foreach ($de_data as $item){
            $serv->task($item);
        }
    }

    public function onTask($serv, $task_id, $from_id, $sql)
    {
        $pdo = $this->buildPdo();
        $result = $pdo->query($sql);
        $filename = './'.uniqid().'.csv';
        $writer = fopen($filename, 'a');

        while($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            fputcsv($writer, $row);
        };
        fclose($writer);
        $serv->finish('ok');
    }

    private function buildPdo()
    {
        $dbms   =   'mysql';
        $host   =   '192.168.88.147';
        $dbName =   'onetp_r4.1';
        $user   =   'root';
        $pass   =   '';
        $dsn    =   "$dbms:host=$host; dbname=$dbName";
        $pdo    =   new \PDO($dsn, $user, $pass, [\PDO::MYSQL_ATTR_INIT_COMMAND=>'set names utf8']);
        $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        return $pdo;
    }
}

new Server();
