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
    $seleccionadoPendiente = "";
    $seleccionadoProceso = "";
    $seleccionadoTerminado = "";
    
    $estadoPendiente = FtMesaAyuda::ESTADO_PENDIENTE;
    $estadoProceso = FtMesaAyuda::ESTADO_PROCESO;
    $estadoTerminado = FtMesaAyuda::ESTADO_TERMINADO;
    
    $BusquedaComponente = new BusquedaComponente($datos['idbusqueda_componente']);
    $nombre_componente = $BusquedaComponente->nombre;
    $idBusquedaComponente = $datos['idbusqueda_componente'];
    
    $params1=http_build_query([
      'idbusqueda_componente'=>$idBusquedaComponente,
      'variable_busqueda'=>'{"estado":"' . $estadoPendiente . '"}'
    ]);
    $url1 = "views/buzones/grilla.php?".$params1;
    
    $params2=http_build_query([
      'idbusqueda_componente'=>$idBusquedaComponente,
      'variable_busqueda'=>'{"estado":"' . $estadoProceso . '"}'
    ]);
    $url2 = "views/buzones/grilla.php?".$params2;
    
    $params3=http_build_query([
      'idbusqueda_componente'=>$idBusquedaComponente,
      'variable_busqueda'=>'{"estado":"' . $estadoTerminado . '"}'
    ]);
    $url3= "views/buzones/grilla.php?".$params3;
    
    $estadoSeleccionado = @json_decode($_REQUEST["variable_busqueda"],true)['estado'];
    if($estadoSeleccionado == $estadoPendiente){
        $seleccionadoPendiente = 'selected';
    } else if($estadoSeleccionado == $estadoProceso){
        $seleccionadoProceso = 'selected';
    } else if($estadoSeleccionado == $estadoTerminado){
        $seleccionadoTerminado = 'selected';
    }

    $cadena_acciones = "<select id='opciones_tickets' class='pull-left btn btn-lg'>";
    //$cadena_acciones .= "<option value=''>Acciones...</option>";

    $cadena_acciones .= "<option value='{$estadoPendiente}' ruta='{$url1}' {$seleccionadoPendiente}>Por asignar</option>";
    $cadena_acciones .= "<option value='{$estadoProceso}' ruta='{$url2}' {$seleccionadoProceso}>En proceso</option>";
    $cadena_acciones .= "<option value='{$estadoTerminado}' ruta='{$url3}' {$seleccionadoTerminado}>Terminados</option>";

    $cadena_acciones .= "</select>";

    return $cadena_acciones;
}
?>

<script data-baseurl='<?= $rootPath ?>'>
    $(function() {
        $.getScript($('script[data-baseurl]').data('baseurl') + 'views/modules/mesa_ayuda/views/reportes/mesa_ayuda.js');
    });
</script>