import $ from 'jquery';

$(() => {

      
      $('a.test_tr').on('click', function(e) {
            e.preventDefault();
            $(".dynamickyPridany").remove();

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
            var novyRadek = $('<tr class="dynamickyPridany"><td class="text-center" colspan="7"><div id="m" style="height:400px"></div></td></tr>'); // Vytvoření nového řádku
            $("#result_table #"+trId).after(novyRadek); 

           }


      });

      $(document).on('click', '.dynamickyPridany', function() {
            $(this).remove(); // Odstranění řádku po dokončení animace
      });




var zacatek = '<?xml version="1.0" encoding="UTF-8"?><gpx><trk><trkseg>';

var konec = '</trkseg></trk></gpx>';


});