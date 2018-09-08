<?php

//подключаем библиотеку для парсинга
require "../phpQuery/phpQuery/phpQuery.php";

/**
 * возвращает количество страниц
 * @param $url адрес документа
 * @return float|int количество страниц
 */


function ColPage ($url){

    if ( isset($_POST["bms_id"]) )
        $bms_id = "&bms_id=" . $_POST["bms_id"];
    else
        $bms_id = "";
    $content = file_get_contents($url.$bms_id);
    $document = phpQuery::newDocument($content);
    $count_comp = $document->find('.tatal-count-wrapper p');
    $text = pq($count_comp)->text();
    $text =  substr ($text, strrpos($text, ' ')); //получаем количество компаний
    $n = preg_replace("/[^0-9]/", '', $text);//преобразуем в число
    //вычисляем количество страниц
    $pages = ($n / 20)+ 0.49;
    $pages = round($pages);


    echo $pages;
}
 ColPage($_POST['url']);

?>