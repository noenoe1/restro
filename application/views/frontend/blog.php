<!--================Banner Area =================-->
<section class="banner_area">
    <div class="container">
        <div class="banner_content">
            <h4>Blog List</h4>
            <a href="#">Home</a>
            <a href="blog.html">Blog</a>
            <a class="active" href="blog.html">List</a>
        </div>
    </div>
</section>
<!--================End Banner Area =================-->

<!--================Blog List Area =================-->
<section class="blog_list_area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <?php
                        foreach($blogs->result() as $blog) {
                        $conds = array( 'img_type' => 'feed', 'img_parent_id' => $blog->id );
                        $images = $this->Image->get_all_by( $conds )->result();
                    ?>
                    <article class="blog_list_item row m0">
                        <div class="col-md-6">
                            <div class="blog_list_img">
                                <img src="<?php echo $this->ps_image->upload_url . $images[0]->img_path; ?>" alt="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog_list_content">
                                <?php 
                                    $added_date = $blog->added_date;
                                    $dt = strtotime($added_date);
                                    $day = date("d", $dt);
                                    $month = date("M",$dt);
                                    $year = date("Y",$dt);
                                ?>
                                <h3><?php echo $blog->name; ?></h3>
                                <h6>Posted by <a href="#"><?php echo $this->User->get_one($blog->added_user_id)->user_name; ?></a>  at 
                                    <?php echo $day." ".$month.", ".$year; ?></h6>
                                <p>
                                    <?php
                                        $length = 120; 
                                        $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $blog->description);
                                          
                                        echo $text;
                                    ?>
                                </p>
                                <div class="pull-left">
                                    <a class="event_btn" href="<?php echo site_url('blogdetail/').$blog->id; ?>">READ MORE</a>
                                </div>
                               
                            </div>
                        </div>
                    </article>
                    <?php } ?>
                </div>
                <nav aria-label="Page navigation" class="blog_pagination">
                    <ul class="pagination">
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
        </div>
    </div>
</section>
<!--================End Blog List Area =================-->