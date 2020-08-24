$(function () {
    let baseUrl = Session.getBaseUrl();
    
    (function init() {
        $('#clasificacion').select2();
        createPicker();
        cargarOpcionesClasificacionBusqueda();
    })();


    $('#clear').on('click', function () {
        $('#initial_date')
            .data('DateTimePicker')
            .clear();
        $('#final_date')
            .data('DateTimePicker')
            .clear();
    });

    $('#btn_success').on('click', function () {
        $.post(`${baseUrl}app/busquedas/procesa_filtro_busqueda.php`,
            $("#formulario_ticket").serialize(),
            function (data) {
                if (data.exito) {
                    top.successModalEvent(data);
                } else {
                    top.notification({
                        message: data.mensaje,
                        type: 'error'
                    });
                }
            },
            'json');
    });

    function createPicker() {
        $('#initial_date,#final_date').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });
        
        $('#initial_date')
            .data('DateTimePicker')
            .defaultDate(null);
        $('#final_date')
            .data('DateTimePicker')
            .defaultDate(null);
    }
    
    function cargarOpcionesClasificacionBusqueda(){
			  $.post(
			      `${baseUrl}app/modules/back_mesa_ayuda/formatos/mesa_ayuda/obtener_opciones_clasificacion.php`,
			      {
			          token: localStorage.getItem('token'),
			          key: localStorage.getItem('key')
			      },
			      function(response) {
			          if (response.success) {
			              $("#clasificacion").html(response.html);
			              $("#clasificacion").select2('destroy');
			              $("#clasificacion").select2();
			          }
			      },
			      "json"
			  );
		}
});