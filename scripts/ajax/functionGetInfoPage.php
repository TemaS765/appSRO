<?php

//подключаем библиотеку для парсинга
require "../phpQuery/phpQuery/phpQuery.php";



/**
 * возвращает информацию о компаниях
 * @param $url адрес документа
 * @param $pages количество страниц
 */
function GetInfoPage($url, $pages){

    for ($page = 1; $page <= $pages; $page++){

        //создаем файл для дампа
        $f = fopen('process.txt','w'); /// дампим файл для процесса;
        fwrite($f,$page);
        fclose($f);


       // $build_url =  $url . "?" . "bms_id=" . $bms_id . "&" . "page=" . $page;

        if (stripos($url,"?")){
            $build_url =  $url .  "&page=" . $page;
        }
        else $build_url =  $url .  "?page=" . $page;

        //echo $build_url;

        $content = file_get_contents($build_url);  //создаем контент по адресу
        $document = phpQuery::newDocument($content); //создаем обьек на основе контента
        $sros = $document->find('.sro-link'); //ище все компании в сро

        //записываем ссылки на компании в массив ссылок

        $company_list = []; //очищаем массив ссылок на компании

        foreach ($sros as $sro){
            $company_link = pq($sro)->attr('rel');
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

                    //echo '<tr><td>'.$name.'</td>';

                }

                if (pq($row)->find('th')->html() == 'Номер контактного телефона:') {

                    $phone = pq($row)->find('td')->html();

                    $phone = preg_replace("~[-]~", "", $phone); //оставляем только цифры
                    //достаем номера
                    $phone = preg_match_all('~[\s-(]?\d{3,5}[\s-)]{0,2}\s?\d{1,3}[\s-()]{0,2}\d{1,3}[\s-()]?\d{0,3}~',$phone,$matches);

                    $c = count($matches[0]);

                    //если нет номера

                    if ($c < 1){
                        echo '<tr><td>' . $name . '</td>';
                        echo "<td></td></tr>";
                    }

                    for($i=0;$i<$c;$i++){

                        $matches[0][$i] = preg_replace('~[^0-9]~',"",$matches[0][$i]); //убираем лишнее

                        switch ($matches[0][$i][0]){  //корректируем номера

                            case 8 : if (strlen($matches[0][$i]) == 10)      //если код города начинается с 8-ки
                                $matches[0][$i] = "7".$matches[0][$i];
                            else
                                $matches[0][$i][0]= 7;break;
                            case 9 : $matches[0][$i] = "7".$matches[0][$i] ;break;
                            case 4 : $matches[0][$i] = "7".$matches[0][$i] ;break;
                            case 3 : $matches[0][$i] = "7".$matches[0][$i] ;break;
                        }

                        echo '<tr><td>' . $name . '</td>';
                        echo "<td>" . $matches[0][$i]."</td></tr>";
                    }

                    //echo "<td>".$phone."</td></tr>";

                }


            }

        }





    }

    if (file_exists("process.txt")) unlink("process.txt");

}

GetInfoPage($_POST["url"], $_POST["pages"]);

