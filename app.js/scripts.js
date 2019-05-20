//JavaScript/JQuery Document
// Scripts jQuery comums a todo o sistema
$(function(){
		
		/*
		 * Efeitos hover nos icones do index(menu principal)
		 */
		$(".icones").hover(function(){
			$(this).css('background-color','#FF3');
		},function(){
			$(this).css('background-color','#FFF');
		});

		/*
		 * SCRIPT PARA PAGINAÇÃO DE DADOS PELO COMBOBOX DE PAGINAÇÃO E COMBOBOX
		 * DE ORDENAÇÃO
		 */
		$("#comboPag").change(function(){
			if($(this).val() != 0)
			{
				$(location).attr('href',$(this).val());
			}
		});
		
		$("#comboOrderBy").change(function(){
			if($(this).val() != 0)
			{
				$(location).attr('href',$(this).val());
			}
		});
		
		
		/*
		 * SCRIPT PARA FECHAR OS DIALGOS DE MESAGEM
		 */
		
		$("#btnDialogClose").click(function(){
			$(".tmessage").hide();
			$(".tquestion").hide();
		});
		
		
		/*
		 * SCRIPT PARA SETAR MÁSCARAS AO CAMPOS DOS FORMULÁRIOS,
		 * UTILIZA O PLUGIN JQUERY MASK
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
		/*
		 * Fim da rotina de máscara para campos
		 */
		
		
		/*
		 * Script para modificar a cor de fundo da celula de status na tabela de Ordens de Serviços
		 */
		$("table.tdatagrid_table_os tr td:contains('Aberta')").css("background-color","Red").css("color","white");
		$("table.tdatagrid_table_os tr td:contains('Em Serviço')").css("background-color","Orange").css("color","white");
		$("table.tdatagrid_table_os tr td:contains('Finalizada')").css("background-color","Green").css("color","white");
		
		$("table.tdatagrid_table tr td:contains('Aberta')").css("background-color","red").css("color","white");
		$("table.tdatagrid_table tr td:contains('Em Serviço')").css("background-color","Orange").css("color","white");
		$("table.tdatagrid_table tr td:contains('Finalizada')").css("background-color","Green").css("color","white");
		
		$("table.tdatagrid_table_os tr td:contains('Não Avaliada')").css("background-color","white").css("color","black");
		$("table.tdatagrid_table_os tr td:contains('Ruim')").css("background-color","DarkRed").css("color","white");
		$("table.tdatagrid_table_os tr td:contains('Regula')").css("background-color","Red").css("color","white");
		$("table.tdatagrid_table_os tr td:contains('Bom')").css("background-color","Blue").css("color","white");
		$("table.tdatagrid_table_os tr td:contains('Excelente')").css("background-color","DarkBlue").css("color","white");
		
		
		/*
		 * Rotina para detectar o CAPS LOCK no password
		 */
		$(window).bind("capsOn", function(event) {
	        if ($("#senha:focus").length > 0) {
	            $("#capsWarning").show();
	        }
	    });
	    $(window).bind("capsOff capsUnknown", function(event) {
	        $("#capsWarning").hide();
	    });
	    $("#senha").bind("focusout", function(event) {
	        $("#capsWarning").hide();
	    });
	    $("#senha").bind("focusin", function(event) {
	        if ($(window).capslockstate("state") === true) {
	            $("#capsWarning").show();
	        }
	    });

	    /* 
	    * Initialize the capslockstate plugin.
	    * Monitoring is happening at the window level.
	    */
	    $(window).capslockstate();
	    $("#capsWarning").css("float","right").css("margin-rigth","-100px");
	    
});

var tempo = new Number();
// Tempo em segundos

tempo = 0;


function startCronometro(){
	
	//Pega a parte inteira das horas
	var hora = parseInt(tempo/(60*60));
	
	//Pega a parte inteira do minutos
	var min = parseInt(tempo/60);
	
	//Calcula os minutos apos 60 segundos
	min = min%(60);
		
	//Calcula os segundos restantes
	var seg = tempo%60;
	
	//Formata o número menor que dez, ex: 08, 07, ...
	if(hora < 10){
		hora = "0"+hora;
	}

	if(min < 10){
		min = "0"+min;
	}
	
	if(seg < 10){
		seg = "0"+seg;
	}
	
	//Cria a variável para formata no estilo hora/cronômetro
	var cronometro = hora + ':' + min + ':' + seg;
	$("#cronometro").val(cronometro);
	
	//Define que a função será executada novamente em 1000ms = 1 segundo
	setTimeout('startCronometro()', 1000);
	
	tempo++;
}

startCronometro();
