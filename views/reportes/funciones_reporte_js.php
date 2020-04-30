<?php
$max_salida = 6;
$rootPath = $ruta = "";
while ($max_salida > 0) {
    if (is_file($ruta . "sw.js")) {
        $rootPath = $ruta;
    }
    $ruta .= "../";
    $max_salida--;
}

include_once $rootPath . "app/vendor/autoload.php";
include_once $rootPath . "views/assets/librerias.php";

echo select2();

?>

<script data-baseurl='<?= $rootPath ?>'>
    $(function() {
        $.getScript($('script[data-baseurl]').data('baseurl') + 'views/modules/mesa_ayuda/views/reportes/mesa_ayuda.js');
        
        $(document).on('click','.crear_tarea',function(){
          var iddocumento = $(this).attr("iddocumento");
          let options = {
              url: `views/tareas/crear.php`,
              title: 'Tarea',
              params: {
                  documentId: iddocumento,
                  className: 'Saia\\MesaAyuda\\formatos\\mesa_ayuda\\FtMesaAyudaTarea'
              },
              centerAlign: false,
              size: "modal-lg",
              buttons: {},
              afterHide: function() {
                  $('#table').bootstrapTable("refresh");
              }
          };

          top.topModal(options);
        });
    });
</script>