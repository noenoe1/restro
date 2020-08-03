<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>LOGIN Form</h4>
            <a href="#">Home</a>
            <a class="active" href="table.html">Login Form</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->
<!--================Booking Table Area =================-->
<section class="booking_table_area booking_area_white">
    <div class="container">
        <div class="s_black_title">
            <h3>Login</h3>
            <h2>Form</h2>
        </div>
        <div class="login-page">
            <div class="form1">
            <?php
              $attributes = array( 'id' => 'login-form', 'enctype' => 'multipart/form-data');
                echo form_open( site_url('userlogin'), $attributes);
            ?>
            <?php flash_msg(); ?>
            <input type="email" placeholder="<?php echo get_msg( 'user_email' ); ?>" name='user_email' value="<?php echo set_value( 'email' ); ?>">
            <input type="password" placeholder="<?php echo get_msg( 'user_password' ); ?>" name='user_password' value="<?php echo set_value( 'password' ); ?>">
            

            <p>No account yet? <a href="<?php echo site_url() . '/register';?>">Register</a> | <a href="<?php echo site_url() . '/reset_password';?>">Forgot Password?</a></p>
               
            <button type="submit"><?php echo get_msg( 'sign_in' ); ?></button>
            

        <?php echo form_close(); ?>  
           
    </div>
</section>
<!--================End Booking Table Area =================-->