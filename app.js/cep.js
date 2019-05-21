//JavaScript/JQuery Document
// Scripts jQuery comums a todo o sistema
$(function(){
		
		/*
		 * SCRIPT PARA SETAR MÁSCARAS AO CAMPOS DOS FORMULÁRIOS, UTILIZA O
		 * PLUGIN JQUERY MASK
		 */
		function getMask($str){
			var ini = $str.indexOf('mask_')+5;
			var fim = $str.indexOf('_mask');
			return $str.slice(ini,fim);
		}
		
		function setMask()
		{		
			$("input").each(function(i, ele){
				var classe = $(ele).attr('class');
				
				if(classe != null){
					
					if(classe.indexOf('mask_')>0)
					{
						/*
						 * Coloca as devidas mascaras nos campos adequados.
						 */
						
						$(ele).mask(getMask(classe));
						
						/*
						 * Coloca o datepiker do jQuery UI para campos de data
						 */
						if(getMask(classe)=='99/99/9999')
						{
						    $(ele).datepicker({
						        	dateFormat: 'dd/mm/yy',
						        	dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
						        	dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
						        	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
						        	monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
						        	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
						        	showOn: "button",
						        	buttonImageOnly: true,
						        	buttonImage: "app.images/calendar.png"
						        	
						    });
						    $("#ui-datepicker-div").css("font-size","12px");
						}
					}
				}
				
			});
		}
		setMask();
		       
		$("#f_cep").on("blur", function(){
			var cep = $("#f_cep").val().replace('.', '').replace('-', '');
			if (cep != "") { 
				
				jQuery.support.cors = true;
				$('#f_logradouro').addClass('load_ajax');
			    $.ajax({

			    	url: 'https://viacep.com.br/ws/'+cep+'/json/?callback=cep' ,
				    
			    	dataType: "jsonp",					    
				    
				    error: function() {	
				    	$('#f_logradouro').removeClass('load_ajax');
				    },
				    
				    success: function(cep) {
				    	$('#f_logradouro').removeClass('load_ajax');
			    		$('#f_logradouro').val(cep.logradouro);
			    		$('#f_cidade').val(cep.localidade);
			    		$('#f_uf').val(cep.uf);
			    		$('#f_bairro').val(cep.bairro);
			    		
				    }
			    });
			}
		});		

});