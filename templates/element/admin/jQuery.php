<link rel="stylesheet" href="<?php e($this->Url->build('/admin/css/sweet-alert.css'));?>"/>
<script type="text/javascript" src="<?php e($this->Url->build('/admin/js/sweet-alert.min.js'));?>"></script>
<script>
/******change status*****/
function changeStatus(model,dataToken,currentStatus,divID){
	if(dataToken != "" && currentStatus != ""){
		$('#statusBtn_'+divID).html('...');
		var current_status = $('#current_status'+divID).val();
		var url ='';
		$.ajax({
			type: 'POST',
			url: '<?php e($this->Url->build('/ajax/changeStatus'));?>',
			data: {model:model, dataToken:dataToken, currentStatus:current_status},
			success: function(msg){
				if(msg == 'Success'){
					var url = $('#paginatUrl').val();
					if( $("tbody .pagination li.active").length > 0 ){
						var page = parseInt($("tbody .pagination li.active").text());
						url += "?page="+page;
						$( "#replaceHtml" ).load( url );
					}else{
						searchData();
					}
				}
			},error: function(ts){
				$('#error500').modal('show');
			}
		});
	}
}

/******change verify status*****/
function changeVerifyStatus(model,dataToken,currentStatus,divID){
	if(dataToken != "" && currentStatus != ""){
		$('#statusBtn_'+divID).html('...');
		var current_status = $('#current_status'+divID).val();
		var url ='';
		$.ajax({
			type: 'POST',
			url: '<?php e($this->Url->build('/ajax/changeVerifyStatus'));?>',
			data: {model:model, dataToken:dataToken, currentStatus:current_status},
			success: function(msg){
				if(msg == 'Success'){
					var url = $('#paginatUrl').val();
					if( $("tbody .pagination li.active").length > 0 ){
						var page = parseInt($("tbody .pagination li.active").text());
						url += "?page="+page;
						$( "#replaceHtml" ).load( url );
					}else{
						searchData();
					}
				}
			},error: function(ts){
				$('#error500').modal('show');
			}
		});
	}
}
/******shorting ajax********/
$(document).ready(function(){
	$('#replaceHtml').on('click', '.pagination li a', function(){
		var url = $(this).attr("href");
		$('#replaceHtml').load(url);
		return false;
	});

});
/******Reset form********/
function resetFilterForm(){
	$('#searchForm')[0].reset();
		$('.searchOptions, .searchbuttons').hide();
  		searchData();
 	}

/*************Save Ordering***************/
function saveOrder(rowId,order,model,currVal){
	if(rowId != '' && order != '' && model != '' && currVal != '' && $.isNumeric(rowId) && $.isNumeric(order) && $.isNumeric(currVal)){			
		$.ajax({
			type:'POST',
			url:'<?php e($this->Url->build('/ajax/updateOrder'));?>', 
			async:false,
			data:{id:rowId,prev:order,curval:currVal,modal:model},
			success: function(response){
				searchData();
			},error: function(ts){
				$('#error500').modal('show');
			}							
		});
	}else{
		searchData();	
	}	
}
/**************delete record*****************/
function deleteRecord(model,rowId,permission){
	if(permission == 0){
		swal({
			title: "Do you want to delete this record?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			cancelButtonText: "No",
			confirmButtonText: 'Yes',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm){
			if (isConfirm){
			  swal("Deleted!", "", "success");
			  $.ajax({
					type: 'POST',
					url: '<?php e($this->Url->build('/ajax/deleteRecord'));?>',
					data: {model:model, rowId:rowId},
					success: function(msg){ 
						searchData();
					},error: function(ts) { 
						$('#error500').modal('show');
					}
				})
			} else {
			  swal("Cancelled", "", "error");
			}
		});
	}else{
		swal({
			title: "Cannot Delete This Record",
			text: "",
			timer: 2000,
			showConfirmButton: false
		});
	}
}
</script>