<?php
$max_salida = 10;
$rootPath = $ruta = "";
while ($max_salida > 0) {
    if (is_file($ruta . "sw.js")) {
        $rootPath = $ruta;
    }
    $ruta .= "../";
    $max_salida--;
}

include_once $rootPath . "views/assets/librerias.php";

$baseUrl = "../../";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <div class=" container-fluid px-0">
        <!-- START card -->
        <div class="card card-default mb-0">
            <div class="card-body py-2">
                <form id="formulario_ticket">
                    <div class="form-group form-group-default">
                        <label>Número del Ticket:</label>
                        <input name="bqCampo_a@numero" type="text" class="form-control">
                        <input type="hidden" name="bqCondicional_a@numero" value="like">
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group form-group-default input-group">
                                <div class="form-input-group">
                                    <label>Fecha inicial:</label>
                                    <input type="text" class="form-control" name="bqCampo_fecha_x" id="initial_date">
                                    <input type="hidden" name="bqComparador_fecha_x" value="y" />
                                    
                                    <input name="bqTipo_fecha_x" type="hidden" value="date">
                                </div>
                                <div class="input-group-append ">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group form-group-default input-group">
                                <div class="form-input-group">
                                    <label>Fecha final:</label>
                                    <input type="text" class="form-control" name="fecha_y" id="final_date">
                                </div>
                                <div class="input-group-append ">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-default">
                        <label>Descripción:</label>
                        <input name="bqCampo_b@descripcion" type="text" class="form-control">
                        <input type="hidden" name="bqCondicional_b@descripcion" value="like">
                    </div>
                    
                    <div class="form-group form-group-default form-group-default-select2">
                        <label class="">Clasificación:</label>
                        <select class="full-width" id="clasificacion" name="bqCampo_b@clasificacion">
                            <option value="">Seleccione...</option>
                        </select>
                        <input type="hidden" name="bqCondicional_b@clasificacion" value="=">
                    </div>
                    

                    <div class="form-actions">
                        <input type="hidden" name="bqtipodato" value="date|fecha_x,fecha_y">
                        <input type="hidden" id="variable_busqueda" name="variable_busqueda">
                        <input type="hidden" name="idbusqueda_componente" id="component" value="<?= $_REQUEST['idbusqueda_componente'] ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?= select2() ?>
    <?= dateTimePicker() ?>
    <script src="<?= $baseUrl ?>views/modules/mesa_ayuda/views/reportes/busqueda_avanzada.js" data-baseurl="<?= $baseUrl ?>"></script>
</body>

</html>