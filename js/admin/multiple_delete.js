
/**
 * Function to delete the user
 *
 * @param null
 *
 * @return void
 */
$(document).on('click', '.delete_user', function(e){ 
	e.stopImmediatePropagation();
	url = $(this).attr('href');
	bootbox.confirm("Do you want to delete this element ?",
	function(result){
		if(result){
			window.location.replace(url);
		}
	});
	e.preventDefault();
});

/**
 * Function to change status
 *
 * @param null
 *
 * @return void
 */
$(document).on('click', '.status_user', function(e){ 
	e.stopImmediatePropagation();
	url = $(this).attr('href');
	bootbox.confirm("Do you want to change the status ?",
	function(result){
		if(result){
			window.location.replace(url);
		}
	});
	e.preventDefault();
});

$(function(){
	
	/**
	 * function to perform diffrent actions
	 * Check uncheck main checkbox
	 */
	$('.checkAllUser').click(function(e){
	
		$('#powerwidgets').find(':checkbox').prop('checked', $(this).prop("checked"));
		
		if($('.checkAllUser:checked').length > 0){
			$('#powerwidgets').find('.items-inner').addClass('highlightBox');
		}else{
			$('#powerwidgets').find('.items-inner').removeClass('highlightBox');
		} 
		 
		
		$('.checkAllUser').css('outline-color', '');
		$('.checkAllUser').css('outline-style', '');
		$('.checkAllUser').css('outline-width', '');
		
		//Perform Action
		var allVals = [];
		$('#powerwidgets').find(':checkbox').each(function() {
			if($(this).is(":checked")){
				allVals.push($(this).val());
			}
		});
		
		if(($('.checkAllUser').prop('checked') == true) && ($('.deleteall').val() != '')){
			$(this).prop('checked',true);
			
			var actionType = $('.deleteall').val();
			
				e.stopImmediatePropagation();
				bootbox.confirm("Do you want to perform this action ?",
				function(result){
					if(result){
						$.ajax({
							url: action_url,
							type: 'post',
							data: { ids: allVals, type : actionType },
							beforeSend: function() { 
								$("#overlay").show();
							},
							success:function(data){
								$("#overlay").hide();
								window.location.href=window.location.href;
							}
						});
					}
				});
			
			e.preventDefault();
		}
		//end Perform action
	});
	
	/**
	* function to perform select and unselect all check box	
	*/
	
	//when particular checkboc is checked
	$('.userCheckBox').click(function(){
	
		$(this).prop('checked', $(this).prop("checked"));
		$(this).closest('.items-inner').toggleClass('highlightBox');
		
		if($('.userCheckBox:checked').length > 0){
			$('.checkAllUser').prop('checked', true);
		}else{
			$('.checkAllUser').prop('checked', false);
		}
	});
	
	
	/**
	* function to perform selected action for  all selected users
	* for change the action 	
	*/ 
	 
	$('.deleteall').change(function(e){
		
		var allVals = [];
		$('#powerwidgets').find(':checkbox').each(function() {
			if($(this).is(":checked")){
				allVals.push($(this).val());
			}
		});
		if($('.checkAllUser').prop('checked') == true){
			var actionType = $(this).val();
			
			e.stopImmediatePropagation();
			
			if($(this).val() != '') {
				bootbox.confirm("Do you want to perform this action ?",
				function(result){
					if(result){
						$.ajax({
							url: action_url,
							type: 'post',
							data: { ids: allVals, type : actionType },
							beforeSend: function() { 
								$("#overlay").show();
							},
							success:function(data){
								$("#overlay").hide();
								window.location.href=window.location.href;	
							}
						});
					}
				});
			}
			
			e.preventDefault();
		}else{
			$('.checkAllUser').css('outline-color', 'red');
			$('.checkAllUser').css('outline-style', 'solid');
			$('.checkAllUser').css('outline-width', 'thin');
		}
	});
	
});
