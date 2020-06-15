
var table;

$(document).ready(function() {

 table = $('#myTable').DataTable( {
    		"ajax":'main/loadData',
    		"order" : []
		}).ajax.reload(); 
/*setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );*/

getBalance();
	
$("#addModalButton").on('click', function(){
		getBalance();

		$("#addRecordForm")[0].reset();
		$(".form-group").removeClass('has-error');
		$(".text-danger").remove();

	$("#addRecordForm").submit(function(e){
	//Отключение стандартной отправки формы
	e.preventDefault();
	$(".text-danger").remove();

	var $form = $(this);
	var description = $("#description").val();
	var operation = $("#operation").val();
    var amount = $("#amount").val();
    var balance = $("#balance").val();

	var url = $form.attr('action');

	if(description == ""){
				$("#description").closest('.form-group').addClass('has-error');
				$("#description").after('<p class="text-danger">Поле описания не может быть пустым</p>');
			}else {
				$("#description").closest('.form-group').removeClass('has-error');
			}

	if(operation == ""){
				$("#operation").closest('.form-group').addClass('has-error');
				$("#operation").after('<p class="text-danger">Операция не может быть пустой</p>');
			}else {
				$("#operation").closest('.form-group').removeClass('has-error');
			}
	if(description && operation ){

		var posting = $.post(
				url,
			{
				description : description,
				operation : operation,
				amount : amount,
				balance : balance
			},
				testFunc
			);

	 	}
	
 			$("#addRecordForm")[0].reset();
 		
 			$("#addModal").modal('hide');

 			table.ajax.reload(null,false);
 			getBalance();
 		
		});
	});

});

function testFunc(data){
	getBalance();
	 //table.ajax.reload( null, false );
	alert(data);
}

function deleteRecord(id = null){

	if(id)
	{
										
		$("#removeButton").on('click', function() {
			//$("#deleteRecordModal").modal('hide');
			table.ajax.reload(null,false);
			
			$.ajax({
				url: 'main/deleteRecord',
				type: 'post',
				data: {recordId : id},
				dataType: 'json',
				success: function(response){
					if(response.success == true){
						$(".removeMessages").html('<div class="alert alert-success" role="alert">'+
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
							+response.messages+
							'</div>');


							table.ajax.reload(null,false);

							
							

						$("#deleteRecordModal").modal('hide');
						
						getBalance();
						

					}else{
						//alert(response.messages);
						$(".removeMessages").html('<div class="alert alert-warning" role="alert">'+
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
							+response.messages+
							'</div>');

						table.ajax.reload(null,false);
						$("#deleteRecordModal").modal('hide');
					}
				}

			});

		});
		//table.ajax.reload(null,false);
	}else{
		alert("Please, reload page.");
		table.ajax.reload(null,false);
	}
	
	
	
}

function getBalance(){
	$.ajax({
		url: 'user/getUserBalance',
		dataType:'json',
		success: function(response){
			 $("#balance").val(response.balance) ;
			 $("#editBalance").val(response.balance) ;
		}
	});
}

function editRecord(id = null){
	getBalance();
	if(id){
		
										

		$(".form-group").removeClass('has-error');
		$(".text-danger").remove();
		$('.editMessages').html('');

		$("#editId").remove();
		$("#oldAmount").remove();
		$("#authorId").remove();
		$("#editOperationOld").remove();

		

		$.ajax({
			url: 'main/selectRecord',
			type: 'post',
			data: { recordId : id },
			dataType : 'json',
			success: function(response){

					$("#editDescription").val(response.description);
					$("#editAmount").val(response.amount);
					$(".editFooter").append('<input type="hidden" name="editId" id="editId" value="'+response.id+'" />');
					$(".editFooter").append('<input type="hidden" name="oldAmount" id="oldAmount" value="'+response.amount+'" />');
					$(".editFooter").append('<input type="hidden" name="authorId" id="authorId" value="'+response.id_author+'" />');
					
					$(".editFooter").append('<input type="hidden" name="editOperationOldValue" id="editOperationOldValue" value="'+response.operation+'" />');

					$(".editBody").append(
						response.operation == "profit" ? 
						('<input type="text" disabled="true" class="form-control col-sm-12 text-center" name="editOperationOld" id="editOperationOld" value="Доходы" />') :
						('<input type="text" disabled="true" class="form-control col-sm-12 text-center" name="editOperationOld" id="editOperationOld" value="Затраты" />')

						);


					getBalance();

					$("#updateRecordForm").unbind('submit').bind('submit', function(){
						
						$(".text-danger").remove();

						var formEdit = $(this);
						var editId = response.id;
						$("#editOperationOldValue").val(response.operation);
						$("#authorId").val(response.id_author);
						var editDescription = $("#editDescription").val();
					    var editAmount = $("#editAmount").val();
						var url = formEdit.attr('action');

						if(editDescription == ""){
									$("#editDescription").closest('.form-group').addClass('has-error');
									$("#editDescription").after('<p class="text-danger">Поле описания не может быть пустым</p>');
								}else {
									$("#editDescription").closest('.form-group').removeClass('has-error');
								}

						
						if(editDescription ){

							$.ajax({
								url: 'main/updateRecord',
								type: 'post',
								data: formEdit.serialize(),
								dataType: 'json',
								success: function(response){
									if(response.success == true){

										$(".editMessages").html('<div class="alert alert-success" role="alert">'+
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
										+response.messages+
										'</div>');

										table.ajax.reload(null,false);
										getBalance();
										
										
										$("#editRecordModal").modal('hide');
										

									}else{

										$(".editMessages").html('<div class="alert alert-warning" role="alert">'+
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
										+response.messages+
										'</div>');

									}
								}
							});
						}
						return false;
					});
			}

		});
	}else{
		alert('Ошибка, перезагрузите страницу.');
	}
}



