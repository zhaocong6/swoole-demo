<?php

Swoole\Runtime::enableCoroutine();

go(function (){
    sleep(1);
    echo 'a'.PHP_EOL;
});

go(function (){
    sleep(2);
    echo 'b'.PHP_EOL;
});