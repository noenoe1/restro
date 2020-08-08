<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>Blog Details</h4>
            <a href="#">Home</a>
            <a href="<?php echo site_url('blog'); ?>">Blog</a>
            <a class="active" href="#">Details</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->
<!--================Blog List Area =================-->
<section class="blog_list_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row m0">
                    <div class="blog_details_inner">
                        <div class="blog_details_content">
                            <?php

                              $conds = array( 'img_type' => 'feed', 'img_parent_id' => $blog->id );
                              
                              $images = $this->Image->get_all_by( $conds )->result();

                              $added_date = $blog->added_date;
                              $dt = strtotime($added_date);
                              $day = date("d", $dt);
                              $month = date("M",$dt);
                              $year = date("Y",$dt);

                            ?>
                                

                            <h3><?php echo $blog->name; ?></h3>
                            <h4>Posted by <a href="#"><?php echo $this->User->get_one($blog->added_user_id)->user_name; ?></a>  at 
                                    <?php echo $day." ".$month.", ".$year; ?></h4>
                            <p><?php echo $blog->description; ?></p>
                            <center>
                                <img src="<?php echo $this->ps_image->upload_url . $images[0]->img_path; ?>" alt="">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Blog List Area =================-->