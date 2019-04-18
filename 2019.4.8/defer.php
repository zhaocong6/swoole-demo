<?php
Swoole\Runtime::enableCoroutine();

go(function (){
    echo '1';

    /**
     * 特点 先进后出
     */
    defer(function (){
        echo '~1';
    });

    echo '2';

    defer(function (){
        echo '~2';
    });
});