<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>Contact Us</h4>
            <a href="<?php echo site_url('home'); ?>">Home</a>
            <a class="active" href="#">Contat Us</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->

<!--================Contact Area =================-->
<section class="contact_area">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="contact_details">
                    <h3 class="contact_title">Contact Info</h3>
                    <p>There are many variations of passages of Lorem Ipsum available, but the majori have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a pas of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="media-body">
                            <h4>Addtress</h4>
                            <h5>Freedom Way, Jersey Ctity, NJ 07305, USA</h5>
                        </div>
                    </div>
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="media-body">
                            <h4>Phone</h4>
                            <h5>+88 01911 854 378</h5>
                        </div>
                    </div>
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-envelope-o"></i>
                        </div>
                        <div class="media-body">
                            <h4>Email</h4>
                            <h5>backpiper.com@gmail.com</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row contact_form_area">
                    <h3 class="contact_title">Send Message</h3>
                    <?php flash_msg(); ?>
                    <?php
                      $attributes = array( 'id' => 'contact-form', 'enctype' => 'multipart/form-data', 'class' => 'p-5 bg-white');
                      echo form_open( site_url('contactus'), $attributes);
                    ?>
                        <div class="form-group col-md-12">
                          <input type="text" class="form-control" id="name" name="name" placeholder="Contact Name*">
                        </div>
                       
                        <div class="form-group col-md-12">
                          <input type="email" class="form-control" id="email" name="email" placeholder="Your Email*">
                        </div>
                         <div class="form-group col-md-12">
                          <input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone">
                        </div>
                        <div class="form-group col-md-12">
                          <textarea class="form-control" id="message" name="message" placeholder="Write Message"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <button class="btn btn-default submit_btn" type="submit">Send Message</button>
                         </div>
                    <?php echo form_close(); ?>
                    <div id="success">
                        <p>Your text message sent successfully!</p>
                    </div>
                    <div id="error">
                        <p>Sorry! Message not sent. Something went wrong!!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Contact Area =================-->