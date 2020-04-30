$(function() {
    let params = $('#clasificacion_script').data('params');
    let id = params.id;
    let table = params.table;
    $('#modal_title').html('Adicionar clasificación');

    $('#btn_success').on('click', function() {
        $('#clasificacion_form').trigger('submit');
    });
    
    $("#cod_padre").change(function(){
    	var x_valor = $(this).val();
    	if(x_valor == 0){
    		$("#input").show();
    	} else if(x_valor > 0){
    		$("#input").hide();
    		$("#responsables").val('');
    		$("#responsables_json").val('');
    		$("#responsables").trigger('change');
    	}
    });
    
    $('#responsables').select2({
        minimumInputLength: 3,
        language: 'es',
        ajax: {
            url: `${params.baseUrl}app/funcionario/autocompletar.php`,
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term,
                    key: localStorage.getItem('key'),
                    token: localStorage.getItem('token'),
                    identificator: 'funcionario_codigo'
                };
            },
            processResults: function(response) {
                return response.success ? { results: response.data } : {};
            }
        }
    });
    
    $(document).off('change','select[name="responsables"]')
    .on('change','select[name="responsables"]',function (event){    	
        //let option = $('option[data-idsql]:selected',event.target);
        
        var options = $('#responsables').select2('data');
        let json = new Array();
        $.each(options, function(index, value){          
          json[index] = {id : value.id.toString(), nombre : value.text};
        });
        
        $('input[name="responsables_json"]').val(JSON.stringify(json));
    });
    
    /*(function () {
     		var responsables 
		    // create the option and append to Select2
		    var option = new Option(data.full_name, data.id, true, true);
		    studentSelect.append(option).trigger('change');
		
		    // manually trigger the `select2:select` event
		    studentSelect.trigger({
		        type: 'select2:select',
		        params: {
		            data: data
		        }
		    });
		});*/

    if (id != '') {
        $('#modal_title').html('Editar clasificación');
        $.post(
            `${params.baseUrl}app/cf/acciones.php`,
            {
                key: localStorage.getItem('key'),
                token: localStorage.getItem('token'),
                id: id,
                type: 'edit',
                table: table
            },
            function(response) {
                if (response.success) {
                    fillForm(response.data);
                    
                    seleccionarOpcionesResponsables(response.data);
                } else {
                    top.notification({
                        type: 'error',
                        message: response.message
                    });
                }
            },
            'json'
        );
    }

    function fillForm(data) {
        for (let attribute in data) {
            let e = $(`[name='${attribute}']`);
            if (e.length && attribute != 'estado') {
                e.val(data[attribute]).trigger('change');
                //$(`[name='${attribute}']`).attr('disabled', true); Jorge pide habilitar la edicion
            } else if (attribute == 'estado') {
                $(`[name='estado'][value=${data.estado}]`).prop(
                    'checked',
                    true
                );
            }
        }
    }
    
    function seleccionarOpcionesResponsables(data){
    	var responsables = $("#responsables");
    	
    	for (let attribute in data) {
    		if(attribute == 'responsables_json'){
    			let e = $(`[name='${attribute}']`);
    			var arrayJson = JSON.parse(e.val());
    			//let e = $(`[name='${attribute}']`);
    		}
    	}
    	
    	$.each(arrayJson, function(index, value){
    		var option = new Option(value.nombre, data.id, true, true);
    		responsables.append(option).trigger('change');
    	});
    	
    	responsables.trigger({
	        type: 'select2:select'
	    });
    }
    
    function getUsers() {
        let users = $('#responsables').val() || [];
        let nodes = $('#users_tree')
            .fancytree('getTree')
            .getSelectedNodes();

        nodes.forEach(n => {
            users.push(n.key);
        });

        return users;
    }
    
    (function defaultUsers(params) {
        if (parseInt(params.type) == 2) {
            var data = {
                defaultUser: params.userInfo.user,
                key: localStorage.getItem('key'),
                token: localStorage.getItem('token')
            };
        } else if (parseInt(params.type) == 3) {
            var data = {
                documentId: params.documentId,
                key: localStorage.getItem('key'),
                token: localStorage.getItem('token')
            };
        } else {
            return;
        }

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `${params.baseUrl}app/funcionario/autocompletar.php`,
            data: data,
            success: function(response) {
                response.data.forEach(u => {
                    var option = new Option(u.text, u.id, true, true);
                    $('#responsables')
                        .append(option)
                        .trigger('change');
                });
            }
        });
    })(params);
});

$('#clasificacion_form').validate({
    ignore: '',
    rules: {
        nombre: {
            required: true
        }
    },
    messages: {
        nombre: {
            required: 'Campo requerido'
        }
    },
    errorPlacement: function(error, element) {
        let node = element[0];

        if (
            node.tagName == 'SELECT' &&
            node.className.indexOf('select2') !== false
        ) {
            error.addClass('pl-3');
            element.next().append(error);
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form) {
        let params = $('#clasificacion_script').data('params');
        let data = $('#clasificacion_form').serialize();
        
        var x_responsables = $("#responsables").val().join(",");
        
        data =
            data +
            '&responsables=' + x_responsables + 
            '&' +
            $.param({
                key: localStorage.getItem('key'),
                token: localStorage.getItem('token'),
                id: params.id,
                table: params.table
            });

        $.post(
            `${params.baseUrl}app/cf/adicionar.php`,
            data,
            function(response) {
                if (response.success) {
                    top.notification({
                        message: response.message,
                        type: 'success'
                    });
                    top.closeTopModal();
                    top.successModalEvent();
                } else {
                    top.notification({
                        message: response.message,
                        type: 'error',
                        title: 'Error!'
                    });
                }
            },
            'json'
        );
    }
});
