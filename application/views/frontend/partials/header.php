<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/express-favicon.png" type="image/x-icon" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Restro</title>

        <!-- Icon css link -->
        <link href="<?php echo base_url('assets/frontend/vendors/material-icon/css/materialdesignicons.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/css/font-awesome.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/vendors/linears-icon/style.css'); ?>" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?php echo base_url('assets/frontend/css/bootstrap.min.css'); ?>" rel="stylesheet">
        
        <!-- Rev slider css -->
        <link href="<?php echo base_url('assets/frontend/vendors/revolution/css/settings.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/vendors/revolution/css/layers.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/vendors/revolution/css/navigation.css'); ?>" rel="stylesheet">
        
        <!-- Extra plugin css -->
        <link href="<?php echo base_url('assets/frontend/vendors/bootstrap-selector/bootstrap-select.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/vendors/bootatrap-date-time/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/vendors/owl-carousel/assets/owl.carousel.css'); ?>" rel="stylesheet">
        
        <link href="<?php echo base_url('assets/frontend/css/style.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/frontend/css/responsive.css'); ?>" rel="stylesheet">
        <!-- gallery slider -->
        <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <!-- slick -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontend/slick/slick.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontend/slick/slick-theme.css'); ?>">
        <style type="text/css">
    html, body {
      margin: 0;
      padding: 0;
    }

    * {
      box-sizing: border-box;
    }

    .slider {
        width: 50%;
        margin: 100px auto;
    }

    .slick-slide {
      margin: 0px 20px;
    }

    .slick-prev:before,
    .slick-next:before {
      color: black;
    }


    .slick-slide {
      transition: all ease-in-out .3s;
      opacity: .2;
    }
    
    .slick-active {
      opacity: .5;
    }

    .slick-current {
      opacity: 1;
    }
  </style>
    </head>
<body>
    <header class="main_menu_area">
        <nav class="navbar navbar-default">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo site_url('home'); ?>"><img src="<?php echo base_url('uploads/img/logo-1.png'); ?>" alt=""></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                        <li class="dropdown submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url('blog'); ?>">Blog</a></li>
                            </ul>
                        </li>
                        <?php if ( !isset( $user_info )): ?>
                        <li><a href="<?php echo site_url().'/userlogin'; ?>">Login</a></li>
                        <?php else: ?>
                        <li><a href="<?php echo site_url().'/userprofile'; ?>"><i class="fa fa-user"> My Profile</i></a></li>
                        <li><a href="<?php echo site_url().'/userlogout'; ?>">Logout</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo site_url('contactus'); ?>">Contact US</a></li>
                        <li>
                            <a href="<?php echo site_url('basket'); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <div class="bage">
                                <?php
                                    $conds['session_id'] = session_id();
                                    $carts = $this->Productcart->get_all_by($conds);
                                    $result = 0;
                                    foreach ($carts->result() as $cart) {
                                        $result +=$cart->qty;
                                    }
                                    echo $result;
                                ?>
                                
                            </div>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header>