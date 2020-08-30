<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>My Profile</h4>
            <a href="<?php echo site_url('home'); ?>">Home</a>
            <a class="active" href="#">My Profile</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->
<!--==============User Information =================-->
<div class="container" style="padding: 30px 20px 0px 20px;">
	<div class="card-body">
        <div class="row">
        	<h4><?php echo $user_info->user_name; ?></h4>
        	<hr>
         	<div class="col-md-4">
         		<div class="form-group">
	         		<?php 
			            $user_profile_photo = $user_info->user_profile_photo;
			            if($user_profile_photo!= '') {
			        ?>
			        <img width="300px" src=" <?php echo $this->ps_image->upload_url . $user_profile_photo; ?>">
			        <?php } else { ?>
			        <img width="300px" src=" <?php echo $this->ps_image->upload_url . 'no_image.png'; ?>" style="width: 50px;">
					<?php } ?>
				</div>
         	</div>

         	<div class="col-md-4">
         		<div class="form-group">
					<label>
						User Name :
						
						<?php echo $user_info->user_name; ?>
					</label><br>
					<label>
						Phone :
						
						<?php echo $user_info->user_phone; ?>
					</label>
				</div>
         	</div>

         	<div class="col-md-4">
         		<div class="form-group">
					<label>
						Email :
						
						<?php echo $user_info->user_email; ?>
					</label><br>
					<label>
						Address :
						
						<?php echo $user_info->user_address; ?>
					</label>
				</div>
         	</div>
        </div>
    </div>
</div>
<!--==============End User Information =================-->
<!--================ Order history =================-->
<section class="service_area">
    <div class="container">
    	<h3>Order History</h3>
    	
        <div class="row" id="orderslider">
 
			<?php
				$conds['no_publish_filter'] = 1;
				$transactions = $this->Transactionheader->get_all_by( $conds );
				foreach ($transactions->result() as $trans) {
			?>
		        <div class="col-md-3 col-sm-6 slider">
		            <div class="service_item">
		                <img src="<?php echo $this->ps_image->upload_url.'img/basket.png'; ?>" alt="" width="50px">
		                <h3>Trans Code : <?php echo $trans->trans_code; ?></h3>
		                <p>Total Amount : <?php echo $trans->sub_total_amount; ?><br>
		                Status : <?php echo $this->Transactionstatus->get_one($trans->trans_status_id)->title; ?><br>
		                Restaurant : <?php echo $this->Shop->get_one($trans->shop_id)->name; ?></p>
		                <a class="read_mor_btn" href="#">View Details</a>
		            </div>
		        </div>
			<?php } ?>
				      
		</div>

		<h3>Reservation History</h3>
    	
        <div class="row" id="resvslider">
 
			<?php
				$conds['no_publish_filter'] = 1;
				$reservations = $this->Reservation->get_all_by( $conds );
				foreach ($reservations->result() as $res) {
			?>
		        <div class="col-md-3 col-sm-6 slider">
		            <div class="service_item">
		                <img src="<?php echo $this->ps_image->upload_url.'img/reservation.png'; ?>" alt="" width="50px">
		                <h3>Status : <?php echo $res->status_id; ?></h3>
		                <p>
		                <i class="fa fa-calendar" aria-hidden="true" style="font-size: 20px;"></i> <?php echo $res->resv_date; ?><br>
		               	<i class="fa fa-clock-o" aria-hidden="true" style="font-size: 20px;"></i> <?php echo $res->resv_time; ?><br>
		                <i class="fa fa-users" aria-hidden="true" style="font-size: 20px;"></i> Party Side <?php echo $res->note; ?>
		            	</p>
		                <a class="read_mor_btn" href="#">View Details</a>
		            </div>
		        </div>
			<?php } ?>
				      
		</div>
    </div>
</section>
<!--================End Order history =================-->
<!--================ Order history =================-->