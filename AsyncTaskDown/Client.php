<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}

$client->send(json_encode([
    'select * FROM onethink_qrcode_data_c',
    'select * FROM onethink_qrcode_data_c',
]));
$client->close();
