import $ from 'jquery';

$(() => {

     // $('a.test_tr').on('click', function(e) {
      $(document).on('click','a.test_tr',function(e){
            e.preventDefault();
           // $(".dynamickyPridany").remove();


            $(document).on('click', function(e) {

                console.log($(".dynamickyPridany").length);
                if ($(e.target).closest('tr').attr('class') != 'dynamickyPridany')
                {


                if($(".dynamickyPridany").length > 0)
                {
                   $(".dynamickyPridany").remove();
                }
                }

            });


            var url = $(this).attr('href');
            $.getJSON(url, function(response) {
                  var gpxx = zacatek;
                  gpxx += response;
                  gpxx += konec;
                  var value = gpxx.trim();


                  var m = new SMap(JAK.gel("m"));
                  var xmlDoc = JAK.XML.createDocument(value);

                  var gpx = new SMap.Layer.GPX(xmlDoc, null, {maxPoints:500}); /* GPX vrstva */
                  m.addDefaultLayer(SMap.DEF_BASE).enable();
                  m.addLayer(gpx); /* Přidáme ji do mapy */
                  m.addDefaultControls();
                  gpx.enable();    /* Zapnout vrstvu */
                  gpx.fit();
              }).fail(function(xhr) {
                  // Zpracování chyb
                  console.error(xhr);
              });




           var trId =  $(this).closest('tr').attr('id');
           if($(window).width() < 640)
           {
            var novyRadek = $('<tr class="dynamickyPridany"><td class="text-center" colspan="5"><div id="m" style="height:300px"></div></td></tr>'); // Vytvoření nového řádku
            $("#result_table_sm #"+trId).after(novyRadek);

           }
           else
           {
            var novyRadek = $('<tr class="dynamickyPridany"><td class="text-center" colspan="9"><div id="m" style="height:400px"></div></td></tr>'); // Vytvoření nového řádku
            $("#result_table #"+trId).after(novyRadek);

           }


      });












var zacatek = '<?xml version="1.0" encoding="UTF-8"?><gpx><trk><trkseg>';

var konec = '</trkseg></trk></gpx>';



$(document).on('click', 'a[href*="/event/result/"]', function(e) {

    var hrefValue = $(this).attr('href'); // Získání hodnoty atributu href

    var resultId = hrefValue.match(/result\/(\d+)/)[1]; // Získání čísla za slovem "result" a za lomítkem

    var trId =  $(this).closest('tr').attr('id');

    e.preventDefault(); // Zabraňte výchozímu chování

    $.getJSON($(this).attr('href'), function(response) {
        var str = "";
        for (var key in response) {
            if (response.hasOwnProperty(key)) {
                var value = response[key];
                str += '<tr id="dynamic_result_indiviual_'+ key +'" class="bg-red-100">';
                str += '<td class="border" colspan="4"></td>';
                str += '<td class="border px-2">'+response[key].date+'<a class="test_tr underline text-blue-700" href="/result/'+ resultId +'/map">mapa</a></td>'
                str += '<td class="border text-center">'+response[key].pace+'</td>';
                str += '<td class="border text-center">'+response[key].finish_time+'</td>';
                str += '<td class="border text-center"></td>';
                str += '</tr>';





            }
        }

        if($(window).width() < 640)
        {
           $("#result_table_sm #"+trId).after(str);

        }
        else
        {
            $("#result_table #"+trId).after(str);

        }

    }).fail(function(xhr) {
        // Zpracování chyb
        console.error(xhr);
    });
  });










});
