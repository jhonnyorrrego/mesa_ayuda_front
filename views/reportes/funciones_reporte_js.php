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

use Saia\models\busqueda\BusquedaComponente;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

include_once $rootPath . "app/vendor/autoload.php";
include_once $rootPath . "views/assets/librerias.php";

echo select2();

function opciones_tickets($datos)
{
    $estadoPendiente = FtMesaAyuda::ESTADO_PENDIENTE;
    $estadoProceso = FtMesaAyuda::ESTADO_PROCESO;
    $estadoTerminado = FtMesaAyuda::ESTADO_TERMINADO;
    
    $BusquedaComponente = new BusquedaComponente($datos['idbusqueda_componente']);
    $nombre_componente = $BusquedaComponente->nombre;

    $cadena_acciones = "<select id='opciones_tickets' class='pull-left btn btn-lg'>";
    //$cadena_acciones .= "<option value=''>Acciones...</option>";

    $cadena_acciones .= "<option value='{$estadoPendiente}'>Por asignar</option>";
    $cadena_acciones .= "<option value='{$estadoProceso}'>En proceso</option>";
    $cadena_acciones .= "<option value='{$estadoTerminado}'>Terminados</option>";

    $cadena_acciones .= "</select>";

    return $cadena_acciones;
}
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