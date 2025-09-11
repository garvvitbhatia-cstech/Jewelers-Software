var loginRequest;
var blockAccountLink;
var nextPageUrl;
$(document).ready(function(){
    var flag = 0;
	$('#loginSubmit').click(function(e){
		e.preventDefault();
        flag = 0;
		if($.trim($("#username").val()) == ''){
			$('#usernameError').show().html('Please enter your username');
            $("#username").focus();
			flag = 1;
			return false;
		}
		if($.trim($("#password").val()) == ''){
			$('#passwordError').show().html('Please enter your password');
            $("#password").focus();
			flag = 1;
			return false;
		}
		if(flag == 0){
			$.ajax({
				type: 'POST',
				url: loginRequest,
				data: $('#adminLoginForm').serialize(),
				beforeSend:function(){$('#loginSubmit').html('Processing...'); },
				success: function(msg){
					if(msg == "Success"){
						$('#loginSubmit').show().addClass('alert alert-danger').html('Login successfully, Redirecting.....');
						setTimeout(function(){ window.location.href = nextPageUrl; }, 2000);
					}else if(msg == "Mendatory"){
						$('#errorBox').show().addClass('alert alert-danger').html('Username and password cannot be blank.');
                        setTimeout(function(){ $('#errorBox').hide();}, 10000);
						$('#loginSubmit').html('Login to your account');
						return false;
					}else if(msg == "inActive"){
						$('#errorBox').show().addClass('alert alert-danger').html('Your account is inactive, <a href="'+blockAccountLink+'">Click here</a> to contact Site Admin.');
						setTimeout(function(){ $('#errorBox').hide();}, 10000);
						$('#loginSubmit').html('Login to your account');
						return false;
					}else{
						$('#errorBox').show().addClass('alert alert-danger').html('Username and password incorrect');
                        setTimeout(function(){ $('#errorBox').hide();}, 10000);
						$('#loginSubmit').html('Login to your account');
						return false;
					}
				},error: function(ts) {
					$('#loginSubmit').html('Login to your account');
					$('#error500').modal('show');
				}
			});
		}
	});
});
function removeError(id){
	if($('#'+id).val() != '' ){
		$('#'+id+'Error').hide().html('');
	}
}
function showConfirmPassword(){
	if(jQuery('#passEye2').hasClass('fa-eye')){
		jQuery('#password').attr('type','text');
		jQuery('#passEye2').removeClass('fa-eye').addClass('fa-eye-slash');
	}else{
		jQuery('#password').attr('type','password');
		jQuery('#passEye2').removeClass('fa-eye-slash').addClass('fa-eye');
	}
}
