<script>

	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>

	function jqvalidate() {
		
		$('#additional-form').validate({
			rules:{
				name:{
					blankCheck : "",
					minlength: 3,
					remote: "<?php echo $module_site_url .'/ajx_exists/'.@$add->id; ?>"
					
				}
			},
			messages:{
				name:{
					blankCheck : "<?php echo get_msg( 'err_food_add_name' ) ;?>",
					minlength: "<?php echo get_msg( 'err_food_add_len' ) ;?>",
					remote: "<?php echo get_msg( 'err_food_add_exist' ) ;?>."
				}
			}

		});

		// custom validation
		jQuery.validator.addMethod("blankCheck",function( value, element ) {
			
			   if(value == "") {
			    	return false;
			   } else {
			    	return true;
			   }
		})
		

	}
	

	<?php endif; ?>

	function runAfterJQ() {
		$('input[name="price"]').keyup(function(e)
	                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});
	}
</script>