<?php
	$attributes = array( 'id' => 'deliboy-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>

<div class="container-fluid">
	<div class="col-12" style="padding: 30px 20px 20px 20px;">
		<div class="card earning-widget">
	    	<div class="card-header" style="border-top: 2px solid red;">
	    		<h3 class="card-title"><?php echo get_msg('deliboy_info')?></h3>
			</div>
        	<!-- /.card-header -->	

	        <div class="card-body">
	            <div class="row">
	             	<div class="col-md-6">
	            		<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_name')?></label>

							<?php echo form_input(array(
								'name' => 'user_name',
								'value' => set_value( 'user_name', show_data( @$deliboy->user_name ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_name'),
								'id' => 'name',
								'readonly' => 'readonly'
							)); ?>

						</div>

						<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_email')?></label>

							<?php echo form_input(array(
								'name' => 'user_email',
								'value' => set_value( 'user_email', show_data( @$deliboy->user_email ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_email'),
								'id' => 'user_email',
								'readonly' => 'readonly'
							)); ?>

						</div>

	                </div>

	                <div class="col-md-6">

						<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_phone')?></label>

							<?php echo form_input(array(
								'name' => 'user_phone',
								'value' => set_value( 'user_phone', show_data( @$deliboy->user_phone ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_phone'),
								'id' => 'name',
								'readonly' => 'readonly'
							)); ?>

						</div>

	                </div>
	                <!--  col-md-6  -->

	            </div>
	            <hr>
	            <div class="form-group" style="background-color: #edbbbb; padding: 20px;">
						<label>
							<strong><?php echo get_msg('select_status')?></strong>
						</label>

						<select id="deliboy_is_published" name="deliboy_is_published" class="form-control">
						   <?php if ($deliboy->status == 1) { ?> 
						   		<option value="0">Select</option>
						   		<option value="1" selected>Approved</option>
						   		<option value="3">Reject</option>
						   <?php }else if ($deliboy->status == 2) { ?>
						   		<option value="0" selected>Select</option>
						   		<option value="1">Approved</option>
						   		<option value="3">Reject</option>
						   <?php }else{  ?>
						  		<option value="0">Select</option>
						   		<option value="1">Approved</option>
						   		<option value="3" selected>Rejected</option>
						   <?php } ?>
						</select>
				</div>		
	            <!-- /.row -->
	        </div>

	        <!-- /.card-body -->

			<div class="card-footer">
	            <button type="submit" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_save')?>
				</button>

				<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_cancel')?>
				</a>
	        </div>
       	</div>
    </div>
    <!-- card info -->
</div>
				

	
	

<?php echo form_close(); ?>