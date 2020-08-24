$(document).ready(function() {
    let baseUrl = $("script[data-baseurl]").data("baseurl");
    $('#opciones_tickets').select2();
    
    $(document).on("change", ".clasificacion", function(){
    	var elemento = $(this);
    	var x_valor = $(this).val();
    	var x_idFtMesaAyuda = $(this).attr("idft_mesa_ayuda");
    	
    	$.ajax({
    		url : `${baseUrl}app/modules/back_mesa_ayuda/reportes/actualizar_clasificacion.php`,
    		type: 'POST',
        dataType: 'json',
        data: {
            token: localStorage.getItem("token"),
            key: localStorage.getItem("key"),
            clasificacion: x_valor,
            idft_mesa_ayuda : x_idFtMesaAyuda
        },
    		success: function(response) {          
        	let typeMessage = "success";

          if (!response.success) {
              typeMessage = "error";
          }
          top.notification({
              type: typeMessage,
              message: response.message
          });
          
          if(response.success){
          	location.reload();
          }
        }
    	});
    });
    
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
    
    $(document).on('change','#opciones_tickets',function(){
    		var valor = $(this).val();
    		var ruta = $('option:selected', this).attr('ruta');
    		
    		window.open(baseUrl + ruta,'_self');
    });
    
    $(document).on('click','.show_task',function(){
      var iddocumento = $(this).attr("iddocumento");
      
      let options = {
            url: `views/tareas/lista_documento.php`,
            params: {
                documentId: iddocumento
            },
            title: 'Tareas del documento',
            size: 'modal-lg',
            buttons: {
                cancel: {
                    label: 'Cerrar',
                    class: 'btn btn-danger'
                }
            },
            afterHide: function () {
                findCounters();
            }
        };
        top.topModal(options);
    });
    
    $(document).on('click','.reclasificar',function(){
      var iddocumento = $(this).attr("iddocumento");
      
      $(".capaReclasifica" + iddocumento).show();
    });
}); //FIN IF documento.ready
