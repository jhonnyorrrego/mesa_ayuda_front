<?php
use Saia\core\DatabaseConnection;

$max_salida = 10;
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

fancyTree();

$rootPath2 = '../../';

if (empty($_REQUEST['id'])) {
    $_REQUEST['id'] = '';
}
if (empty($_REQUEST['parent'])) {
    $_REQUEST['parent'] = '';
}
if (empty($_REQUEST['table'])) {
    $_REQUEST['table'] = '';
}
$params = json_encode([
    'baseUrl' => $rootPath2,
    'id' => $_REQUEST['id'],
    'parent' => $_REQUEST['parent'],
    'table' => $_REQUEST['table']

]);

$clasificaciones = DatabaseConnection::getQueryBuilder()
        ->select('nombre', 'idma_clasificacion', 'cod_padre')
        ->from('ma_clasificacion')
        ->where('estado=1')
        ->andWhere('cod_padre=0')
        ->execute()->fetchAll();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Adicionar clasificaciones</title>
</head>

<body>
    <div class="container-fluid">
        <!-- START card -->
        <div class="card card-default mb-0">
            <div class="card-body py-2">
                <form id="clasificacion_form">
                    <p>Los campos con <span class="text-danger">*</span> son obligatorios</p>
                    <div class="form-group form-group-default required">
                        <label>Nombre:</label>
                        <input name="nombre" type="text" class="form-control" class="required">
                    </div>
                    
                    <div id="capa_cod_padre" class="form-group form-group-default">
                        <label>Categoría superior</label>
                        <div class="my-0">
                            <select class="cod_padre full-width" name="cod_padre" id="cod_padre">
                              <option value="0">Categoría principal</option>
                            <?=
                              $opciones = '';
                              foreach($clasificaciones as $key){
                                $opciones .= "<option value='{$key["idma_clasificacion"]}'>{$key['nombre']}</option>";
                              }
                              
                              echo($opciones);
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div id="capa_cant_dias" class="form-group form-group-default" style="display: none;">
                        <label>Cantidad de días:</label>
                        <input id="cant_dias" name="cant_dias" type="text" class="form-control">
                    </div>
                    
                    <div id="capa_tipo_dias" class="form-group form-group-default" style="display: none;">
                        <label>Tipo de días:</label>
                        <div class="my-0">
                          <select class="full-width" name="tipo_dias" id="tipo_dias">
                            <option value="1">Días calendario</option>
                            <option value="2">Días hábiles</option>
                          </select>
                        </div>
                    </div>
                    
                    <div class="row" id="input">
                        <div class="col-12">
                            <div class="form-group form-group-default">
                                <label>Responsable de la categoría</label>
                                <select class="full-width" data-init-plugin="select2" multiple id="responsables" name="responsables"></select>
                                <input type='hidden' name="responsables_json">
                            </div>
                        </div>
                    </div>
                    
                    <div id="capa_descripcion" class="form-group form-group-default">
                        <label>Descripción:</label>
                        <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="pl-1 mb-0 mt-1">Estado</label>
                        <div class="radio radio-success my-0">
                            <input type="radio" value="1" name="estado" id="activo" checked>
                            <label for="activo">Activo</label>
                            <input type="radio" value="0" name="estado" id="inactivo">
                            <label for="inactivo">Inactivo</label>
                        </div>
                    </div>
                    <input name="id" id="id" type="hidden" class="form-control" value="<?= $_REQUEST['id'] ?>">
                </form>
            </div>
        </div>
    </div>
    <?= select2() ?>
    <?= validate() ?>
    <script id="clasificacion_script" src="<?= $rootPath2 ?>views/modules/mesa_ayuda/views/configuracion/js/clasificacion.js" data-params='<?= $params ?>'>
    </script>
    <script>
    $(document).ready(function(){
      $("#cod_padre,#tipo_dias").select2();
    });
    </script>
</body>

</html>