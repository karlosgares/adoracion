function goto(obj,section) {
	$('.section').hide();
	$('#section-' + section).show();
	/*
	document.getElementsByClassName("current")[0].className='';
	obj.parentNode.className='current'; */
}

function AbrirModal(tipo) {
	$('.modal-title').hide();
	$('#modal-title-' + tipo).show();
	$('#tiponota').val(tipo);
	$('#textonota').val('');
	$('#modal-nota').modal('show');
}

function action_SaveNota(path) {
	if ($('#textonota').val() == "") return;

	$.ajax({
		  url: path,
		  method: 'POST',
		  data: {'tiponota': $('#tiponota').val(),'textonota': $('#textonota').val()}
		}).done(function(res) { 
		 	if (res['ok']) {
		 		$('#modal-nota').modal('hide');
		 	}
		}
	);	
}


function getNoticia(url, id) {
	$('.ajax-loader-noticia').show();
	$('#list-noticias li.list-group-item-success').removeClass('list-group-item-success');
	$('#noticia_' + id).addClass('list-group-item-success');
	
	$.ajax({
		  url: url,
		  method: 'POST',
		  data: {'id':id},
		}).done(function(res) { 
		 	$('#noticia-detalle').html(res['html'])
		 	$('.ajax-loader-noticia').hide();
	
		}
	);
}