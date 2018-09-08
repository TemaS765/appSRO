//устанавливаем начальные настройки
window.onload = function () {  //функция загрузки документа

    $('#process').css("display","none"); //скрываем прогресс бар
    $('#log').css("display","none"); //скрываем лог
    $('#out').css("display","none");    //скрываем вывод

}
//функции эмулирующие нажатие кнопки
function clk1() {
    $('#btn_start').css("opacity", "0.2");
}
function clk2(el) {
    $('#btn_start').css("opacity", "1");
}



//Прогресс бар
function progressSim(al) {
        var ctx = document.getElementById('my_canvas').getContext("2d");
        //var al = 0;
        var start = 4.72;
        var cw = ctx.canvas.width;
        var ch = ctx.canvas.height;
        var diff;
        ctx.font = 'bold 20px sans-serif';

        diff = ((al / 100) * Math.PI * 2 * 10).toFixed(2);
        ctx.clearRect(0, 0, cw, ch);
        ctx.lineWidth = 20;
        ctx.fillStyle = '#09F';
        ctx.strokeStyle = '#09F';
        ctx.textAlign = 'center';
        ctx.fillText(al + '%', cw * .5, ch * .5 + 5, cw);
        ctx.beginPath();
        ctx.arc(80, 80, 50, start, diff / 10 + start, false);
        ctx.stroke();

        /*if (al >= 100) {
            clearTimeout(sim);
        }*/

        al++;
}

//////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////
//функция очистки поля при активности поля
function CleanPole() {

    var url_pole;

    url_pole = document.getElementById("url_pole");

    if (url_pole.value === 'Введите адрес ссылки для парсинга'){
        url_pole.value ='';
        url_pole.style.fontSize = '14pt';
        url_pole.style.color = 'black';
    }
}
////////////////////////////////////////////////////
//функция получаем адрес введеного в поле ввода
function getUrl() {

    var url_pole;

    url_pole = document.getElementById("url_pole");

    return url_pole.value;

}
//////////////////////////////////////////////////////
////Функция получения прогресса///////////////////////
function GetProgress() {

    $.ajax({
        async:true,
        type: 'POST',
        dataType:'html',
        url: 'scripts/ajax/progress.php',
        success: function(data){
           // console.log("Прогресс: "+data);
           //progressSim(data);
            //ds = jQuery.parseJSON(data);
            process = data;
        }
    });

    return process;
}
//////////////////////////////////////////////////////
//основная функция после нажатия кнопки
function Run() {

   //$('#process').css("display","block"); //показываем прогресс бар

    $('#process').show("slow");

    progressSim(0);

   var url_const, pages; //известно что всего записей на странице 20

    url_const = $("#url_pole").val();
    //получаем количество страниц
    $.ajax({
        async:false,
        type: 'POST',
        dataType:'html',
        url: 'scripts/ajax/functionCount.php',
        data:"url="+url_const,
        beforeSend: function () {
            $("#log").append("<p>Получение количества страниц...</p>");
        },
        success: function(data){
            $("#log").append("<p>Получено страниц: "+data+"</p>");
            pages = Number(data);
        }
    });
    /////////////////////////////////

        var url;

        url = url_const;

        $("#log").append("<p>URL: "+url+"</p>");


        $.ajax({
            async:true,
            type: 'POST',
            dataType:'html',
            url: 'scripts/ajax/functionGetInfoPage.php',
            data:{  url : url,
                    pages : pages
                    },
            beforeSend: function (data) {
                //console.log(data);
               // $("#log").append("<p>Получение информации с  страницы</p>");
                //setInterval(function () {GetProgress();},2000);
            },
            success: function(data){
                //console.log(data);
               // $("#out").css("display","block");
                $("table").append(data);
                $('#out').css("display","block");
            }
        });



        //реализация вывода процесса


    proc = GetProgress();

      var id = setInterval(function () {
                        if (proc >= pages){
                            //$('#out').css("display","block");
                            $("canvas").css("display", "none");
                            $("#process p").css("color","#09F");
                            $("#process p").css("font-size", "20pt");
                            $("#process p").css("font-weight", "bold");
                            $("#process p").css("font-family", "sans-serif");
                            $("#process p").text("Считывание успешно завершено!");
                            clearInterval(id);}
                        else {
                            proc = GetProgress();
                            //console.log("Обработанно страниц: "+proc); //для отладки
                           // console.log("Всего страниц: "+pages);
                            procent = Math.round((100 * proc) / pages); //вычисляем процент
                            progressSim(procent);
                        }
                        },1000);
}