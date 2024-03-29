<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>Food Details</h4>
            <a href="#">Home</a>
            <a href="blog.html">Food</a>
            <a class="active" href="blog-details.html">Details</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->
<!--================Food detail Area =================-->
<section class="blog_list_area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row m0">
                            
		            <h3><?php echo $product->name." ( Price : ".$product->original_price.$this->Shop->get_one($product->shop_id)->currency_symbol." )"; ?></h3>
		            <br>
		            <p><?php echo $product->description; ?></p><br>
		            <h3>Highlight Information</h3>
		            <p style="padding-top: 5px;"><?php echo $product->highlight_information; ?></p><br>
		            <h3>Ingredient</h3>
		            <p style="padding-top: 5px;"><?php echo $product->ingredient; ?></p><br>
		            <?php
		            	$conds['product_id'] = $product->id;
		            	$spec = $this->Specification->get_one_by($conds);
		            	if ($spec->id != "") {
		            ?>
		            <h3>Specification</h3>
		            <label><?php echo $spec->name;?> : </label><?php echo $spec->description; ?>
		        	<?php } ?>
		        	<br>
		        	<h3>GALLERY</h3>
                    <div id="gallery_img">

					    <div id="image"></div>

					    <img src="<?php echo $this->ps_image->upload_url .'/images/icons/left.png';?>" id="lefty">

					    <img src="<?php echo $this->ps_image->upload_url .'/images/icons/right.png';?>" id="righty">

					    <img src="<?php echo $this->ps_image->upload_url .'/images/icons/play.png';?>" id="play">

					    <img src="<?php echo $this->ps_image->upload_url .'/images/icons/pause.png';?>" id="pause">

					    <img src="<?php echo $this->ps_image->upload_url .'/images/icons/expand.png';?>" id="expand">

					</div>

	  				<div id="thumbs">
	  					<?php
	                        $conds = array( 'img_type' => 'product', 'img_parent_id' => $product->id );
	                        $images = $this->Image->get_all_by( $conds )->result();
	                        foreach ($images as $img) {
	                    ?>
					    	<div class="thumbs_style"> <img src="<?php echo $this->ps_image->upload_url .$img->img_path;?>"> </div>
					    <?php } ?>

					</div>

                	<h3>About Restaurant</h3>
                	<br>
                	<div class="col-md-4">
                		<?php

                          	$conds = array( 'img_type' => 'shop', 'img_parent_id' => $product->shop_id );
                          
                          	$images = $this->Image->get_all_by( $conds )->result();

                        ?>
                		<img src="<?php echo $this->ps_image->upload_url . $images[0]->img_path; ?>" style="width: 220px;height: 150px;">
                	</div>
                	<div class="col-md-4">
                		<p><?php echo $this->Shop->get_one($product->shop_id)->name; ?></p>
                		<p><?php echo $this->Shop->get_one($product->shop_id)->about_phone1; ?></p>
                		<p><?php echo $this->Shop->get_one($product->shop_id)->address1; ?></p>
                	</div>
                       
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Food detail Area =================-->
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('assets/frontend/js/jquery.nicescroll.min.js'); ?>"></script>
<!-- gallery slider -->
<script src="<?php echo base_url('assets/frontend/js/script.js'); ?>"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>