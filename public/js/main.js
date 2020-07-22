jQuery( document ).ready( function() {
	
	$('.close').click(function(e){
		e.preventDefault();
		$(this).parent().css('display','none');
	});
	
	
});



function errorCheck(obj){
	
	$('#loginForm').find('.main_error').css('display','none');
	$('#loginForm').find('.main_error').text('');
	
	$('#EmailForm').find('.main_error').css('display','none');
	$('#EmailForm').find('.main_error').text('');
	
	$('#passwordReset').find('.main_error').css('display','none');
	$('#passwordReset').find('.main_error').text('');
	
	
	var errorClass = $(obj).attr('data-error_class');
	$("."+errorClass).css('display','none');
	$("."+errorClass).text('');
	
	$("."+errorClass).css('display','none');
	$("."+errorClass).text('');
	
}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}