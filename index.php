<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Приложение для парсинга СРО</title>
    <link rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" src="script.js"></script>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="/scripts/jquery-3.2.1.min.js"></script>
</head>
<body>
    <div id="forma">
        <form method="post">
            <input onclick="CleanPole()" id="url_pole" name="URL" value="Введите адрес ссылки для парсинга">
            <input id="btn_start" type="button" name="start" value="Начать" onmousedown="clk1();" onmouseup="clk2();" onclick="Run();">
        </form>
    </div>
    <hr>
    <!--Прогресс бар-->
    <div id="process" style="padding:20px; text-align: center">
        <p>Прогресс считывания информации:</p>
        <canvas id="my_canvas" width="160" height="160"></canvas>
    </div>



    <div id="log">
        <p>Лог отладки:</p>
        <hr>
    </div>
    <div id="out">

       <table>

           <tr>
               <th>Наименование организации</th>
               <th>Контактный номер</th>
           </tr>

       </table>
    </div>

</body>
</html>
