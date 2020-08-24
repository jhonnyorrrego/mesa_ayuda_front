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

use Doctrine\DBAL\Types\Type;
use Saia\core\DatabaseConnection;
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
    
    $nombreComponentePendiente = 'tickets_pendientes';
    $nombreComponenteProceso = 'tickets_proceso';
    $nombreComponenteTerminado = 'tickets_terminado';
    
    $estadoPendiente = FtMesaAyuda::ESTADO_PENDIENTE;
    $estadoProceso = FtMesaAyuda::ESTADO_PROCESO;
    $estadoTerminado = FtMesaAyuda::ESTADO_TERMINADO;
    
    $BusquedaComponente = new BusquedaComponente($datos['idbusqueda_componente']);
    $nombreComponente = $BusquedaComponente -> nombre;
    $idBusquedaComponente = $datos['idbusqueda_componente'];
    
    if($nombreComponente == $nombreComponentePendiente){
      $estadoSeleccionado = $estadoPendiente;
    } else if($nombreComponente == $nombreComponenteProceso){
      $estadoSeleccionado = $estadoProceso;
    } else if($nombreComponente == $nombreComponenteTerminado){
      $estadoSeleccionado = $estadoTerminado;
    }
    
    $componentePendiente = DatabaseConnection::getQueryBuilder()
        ->select('idbusqueda_componente')
        ->from('busqueda_componente')
        ->where('nombre = :nombre_componente')
        ->setParameter(':nombre_componente',$nombreComponentePendiente)
        ->execute()->fetchAll();
    $params1=http_build_query([
      'idbusqueda_componente'=>$componentePendiente[0]["idbusqueda_componente"]/*,
      'variable_busqueda'=>'{"estado":"' . $estadoPendiente . '"}'*/
    ]);
    $url1 = "views/buzones/grilla.php?".$params1;
    
    $componenteProceso = DatabaseConnection::getQueryBuilder()
        ->select('idbusqueda_componente')
        ->from('busqueda_componente')
        ->where('nombre = :nombre_componente')
        ->setParameter(':nombre_componente',$nombreComponenteProceso)
        ->execute()->fetchAll();
    $params2=http_build_query([
      'idbusqueda_componente'=>$componenteProceso[0]["idbusqueda_componente"]
    ]);
    $url2 = "views/buzones/grilla.php?".$params2;
    
    $componenteTerminado = DatabaseConnection::getQueryBuilder()
        ->select('idbusqueda_componente')
        ->from('busqueda_componente')
        ->where('nombre = :nombre_componente')
        ->setParameter(':nombre_componente',$nombreComponenteTerminado)
        ->execute()->fetchAll();
    $params3=http_build_query([
      'idbusqueda_componente'=>$componenteTerminado[0]["idbusqueda_componente"]
    ]);
    $url3= "views/buzones/grilla.php?".$params3;
    
    //$estadoSeleccionado = @json_decode($_REQUEST["variable_busqueda"],true)['estado'];
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
<style>
.dropdown2:hover>.dropdown-menu {
  display: block;
}

.dropdown2>.dropdown-toggle:active {
  /*Without this, clicking will make it sticky*/
    pointer-events: none;
}
</style>
<script data-baseurl='<?= $rootPath ?>'>
    $(function() {
        $.getScript($('script[data-baseurl]').data('baseurl') + 'views/modules/mesa_ayuda/views/reportes/mesa_ayuda.js');
    });
</script>