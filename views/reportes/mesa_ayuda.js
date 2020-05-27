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
          if(response.success){
          	elemento.parent().parent().hide();
          }
          
        	let typeMessage = "success";

          if (!response.success) {
              typeMessage = "error";
          }
          top.notification({
              type: typeMessage,
              message: response.message
          });
          
        }
    	});
    });
}); //FIN IF documento.ready
