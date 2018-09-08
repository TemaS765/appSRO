<?php

//подключаем библиотеку для парсинга
require "phpQuery/phpQuery/phpQuery.php";

//функция вывода для отладки кода
function debug($arr) {
    echo '<pre>' . print_r($arr,true) . '</pre>';
}

/**
 * возвращает информацию о компаниях
 * @param $url адрес документа
 * @return array необходимая информация со страницы
 */
function GetInfoPage($url){

    //$list_cmp = [];

    $content = file_get_contents($url);  //создаем контент по адресу
    $document = phpQuery::newDocument($content); //создаем обьек на основе контента
    $sros = $document->find('.sro-link'); //ище все компании в сро
    //записываем ссылки на компании в массив ссылок


    foreach ($sros as $test){
        $company_link = pq($test)->attr('rel');
        $company_list[] = $company_link;

    }



    //осуществляем перебор по компаниям
    foreach ($company_list as $link){


        $client = file_get_contents('http://reestr.nostroy.ru'.$link); //ссылка на компанию
        $document_client = phpQuery::newDocument($client); //создаем обьект
        $company_info = $document_client->find('table tr'); //ищем необходимый аргумент

        //получаем необходимую информацию из элемента


        foreach ($company_info as $row) {

            if (pq($row)->find('th')->html() == 'Сокращенное наименование:') {

                $name = pq($row)->find('td')->html();

                 echo '<td>'.$name.'</td>';

            }

            if (pq($row)->find('th')->html() == 'Номер контактного телефона:') {

                $phone = pq($row)->find('td')->html();

                 echo "<td>".$phone."</td></tr>";

            }
        }
    }

    //return $list_cmp;

}

/**
 * возвращает количество страниц
 * @param $url адрес документа
 * @return float|int количество страниц
 */
function ColPage ($url){

    $content = file_get_contents($url);
    $document = phpQuery::newDocument($content);
    $count_comp = $document->find('.tatal-count-wrapper p');
    $text = pq($count_comp)->text();
    $text =  substr ($text, strrpos($text, ' ')); //получаем количество компаний

    $n = preg_replace("/[^0-9]/", '', $text);//преобразуем в число
    //вычисляем количество страниц
    $pages = ($n / 20)+ 0.4;

    $pages = round($pages);


    return $pages;
}