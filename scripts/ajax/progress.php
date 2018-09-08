<?php
    // скрипт получения состояния прогресса

    //читаем файл процесса

    $f = fopen("process.txt","r");

    echo fgets($f);

    fclose($f);
?>