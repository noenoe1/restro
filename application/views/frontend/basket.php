<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>Basket</h4>
            <a href="<?php echo site_url('home'); ?>">Home</a>
            <a class="active" href="#">Basket</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->
<!--================Cart Area =================-->
<section class="cart_area section_padding">
    <div class="container">
	    <?php
	        $attributes = array( 'id' => 'basket-form', 'enctype' => 'multipart/form-data');
	        echo form_open( site_url('checkout'), $attributes);
	    ?>
	    <div class="cart_inner">
          	<div class="table-responsive">
	            <table class="table">
	              <thead>
	                <tr>
		                <th scope="col">Product</th>
		                <th scope="col">Price</th>
		                <th scope="col">Quantity</th>
		                <th scope="col">Total</th>
	                </tr>
	              </thead>
	              <tbody id="shopping-cat-product">
	                <tr>
	                  <?php $count = 0; $i= 0; foreach($cartlists->result() as $cart): ?>
	                    <?php
	                     
	                      	$conds = array( 'img_type' => 'product', 'img_parent_id' => $cart->product_id );
	                      	$images = $this->Image->get_all_by( $conds )->result();
	                    ?>
	                  	<td>
	                    <div class="media">
	                      <div class="d-flex">
	                        <img src="<?php echo $this->ps_image->upload_thumbnail_url . $images[0]->img_path; ?>" alt="" />
	                      </div>
	                      <div class="media-body">
	                        <p><?php echo $this->Product->get_one($cart->product_id)->name; ?></p>
	                        <input type="hidden" name="shop_id" id="shop_id" value="<?php echo $this->Product->get_one($cart->product_id)->shop_id; ?>">
	                      </div>
	                    </div>
	                  </td>
	                  <td>
	                    <h5><?php echo $this->Product->get_one($cart->product_id)->original_price; ?></h5>
	                  </td>
	                  <td>
	                    <div class="product_count">
	                      <span class="input-number-decrement" onClick="decrement_quantity('<?php echo $cart->id; ?>', '<?php echo $this->Product->get_one($cart->product_id)->original_price; ?>')"> - </span>
	                      <input class="input-number" type="text" id="input-quantity-<?php echo $cart->id ?>" value="<?php echo $cart->qty; ?>" min="0" max="10">
	                      <span class="input-number-increment" onClick="increment_quantity('<?php echo $cart->id; ?>', '<?php echo $this->Product->get_one($cart->product_id)->original_price; ?>')"> + </span>
	                    </div>
	                  </td>
	                  <td>
	                    <h5 id="cart-price-<?php echo $cart->id; ?>">
	                      <?php 
	                        $price = $this->Product->get_one($cart->product_id)->original_price;
	                        $qty = $cart->qty; 
	                        $total_count +=$qty;
	                        $result = $price*$qty;
	                        $subtotal =$subtotal+$result;
	                        echo $result;
	                      ?>
	                    </h5>
	                  </td>
	                </tr>
	               	<?php endforeach; ?>
	                <tr>
		                <td></td>
		                <td></td>
		                <td><h5>Food Subtotal</h5></td>
	                  	<td>
		                    <h5 id="sub_total">$<?php echo $subtotal; ?></h5>
		                    <input type="hidden" name="total_count" id="total_count" value="<?php echo $total_count; ?>">
		                    <input type="hidden" name="subtotal" id="subtotal" value="<?php echo $subtotal; ?>">
		                </td>
	                </tr>
	                <tr>
	                	<td></td>
	                	<td></td>
	                	<td>
	                		<p>Shipping</p>
	                	</td>
	                	<td id="shipping_area">
	                		<?php
	                			$conds['status'] = 1;
	                			$shippings = $this->Shipping_area->get_all_by($conds);
	                			foreach ($shippings->result() as $shp) {
	                		?>
	                		
	                		<label for="<?php echo $shp->area_name; ?>"><?php echo $shp->area_name . "($" . $shp->price .")"; ?></label>
	                		<input type="hidden" name="shipping_price" id="shipping_price" value="<?php echo $shp->price; ?>">
	                		<input type="radio" name="shipping_area" id="<?php echo $shp->area_name; ?>" value="<?php echo $shp->price; ?>"><br>
	                		<?php } ?>
	                	</td>
	                </tr>
	                <tr>
	                	<td></td>
	                	<td></td>
	                	<td>Total</td>
	                	<td id="total_price">$<?php echo $subtotal; ?></td>
	                </tr>
	                <tr class="shipping_area">
		                <td>
		                    <div class="login-group">
		                    	<h3>User Information</h3><br>
			                    <div class="form-group">
			                      	<label>Contact Name : </label>
			                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Please enter user name">
			                    </div>
			                    <div class="form-group">
			                      	<label>Contact Phone : </label>
			                      	<input type="text" class="form-control" id="user_phone" name="user_phone" placeholder="Please enter phone number">
			                    </div>
			                    <div class="form-group">
			                      	<label>Contact Email : </label>
			                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Please enter user email">
			                    </div>
			                    <div class="form-group">
			                      	<label>Contact Address : </label>
			                        <textarea class="form-control" name="user_address" id="user_address"
			                      placeholder="Please enter address" rows="3"></textarea>
			                    </div>
			                    <div class="col-sm-4">
			                    	<button type="submit" style="background-color: red;" name="submit" class="btn btn-default submit_btn">Process To Checkout</button>
			                    </div>
		                    </div>
		                </td>
		                <td></td>
		                <td></td>
		                <td></td>
	                </tr>
	              </tbody>
				</table>
	            
          	</div>
        </div>
	    <?php echo form_close(); ?>
    </div>
</section>
<!--================End Cart Area =================-->
<script src="<?php echo base_url( 'assets/jquery/jquery.min.js' ); ?>"></script>
<script>
	$('#shipping_area input[name="shipping_area"]').on('click', function(){
		var subtotal = $('#subtotal').val();
		var shipping_price = $(this).attr('value');
		var total = subtotal + shipping_price;
		$("#total_price").text("$"+total);
	});
</script>