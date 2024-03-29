<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class Images extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Image' );
	}

	function upload_post()
	{
		
		$platform_name = $this->post('platform_name');
		if ( !$platform_name ) {
			$this->custom_response( get_msg('Required Platform') ) ;
		}
		
		$user_id = $this->post('user_id');

		if($platform_name == "ios") {
			
			
			if ( !$user_id ) {
				$this->custom_response( get_msg('Required User ID') );
			}
			
			$uploaddir = 'uploads/';
			
			$path_parts = pathinfo( $_FILES['pic']['name'] );
			$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
			
			if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploaddir . $filename)) {
			   $user_data = array( 'user_profile_photo' => $filename );
				   if ( $this->User->save( $user_data, $user_id ) ) {
					   	
					   	$user = $this->User->get_one( $user_id );

					   	$this->ps_adapter->convert_user( $user );
					   	
					   	$this->custom_response( $user );
				   } else {
					   	$this->error_response( get_msg('file_na') );
				   }
			   
			} else {
			   $this->error_response( get_msg('file_na') );
				
			}
			
		} else {
			
			$uploaddir = 'uploads/';
			
			$path_parts = pathinfo( $_FILES['file']['name'] );
			$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
			
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {
			   $user_data = array( 'user_profile_photo' => $filename );
				   if ( $this->User->save( $user_data, $user_id ) ) {

					   	$user = $this->User->get_one( $user_id );

					   	$this->ps_adapter->convert_user( $user );
					   	
					   	$this->custom_response( $user );

				   } else {
					   	$this->error_response( get_msg('file_na') );
				   }
			   
			} else {
			   $this->error_response( get_msg('file_na') );
				
			}
		}
		
	}

	function upload_shop_post()
	{
		
		$shop_rate_id = $this->post('shop_rate_id');
		$files = $this->post('file');
		$img_id = $this->post('img_id');

		if ($img_id == "") {
			
			// upload images
			$upload_data = $this->ps_image->upload( $_FILES );
			foreach ( $upload_data as $upload ) {
			   	$shop_img_data = array( 
				   	'img_parent_id'=> $shop_rate_id,
					'img_path' => $upload['file_name'],
					'img_type' => "shop_rate",
					'img_width'=> $upload['image_width'],
					'img_height'=> $upload['image_height']
			   	);
			}
		   if ( $this->Image->save( $shop_img_data, $img_id ) ) {
		   		$conds['img_path'] = $shop_img_data['img_path'];
		   		$img_id = $this->Image->get_one_by($conds)->img_id;
			   	$image = $this->Image->get_one( $img_id );

			   	$this->ps_adapter->convert_image( $image );
			   	
			   	$this->custom_response( $image );
		   } else {
			   	$this->error_response( get_msg('file_na') );
		   }
			
			   
		} else {
			
			// upload images
			$upload_data = $this->ps_image->upload( $_FILES );

			foreach ( $upload_data as $upload ) {
			   	$shop_img_data = array( 
			   		'img_id' => $img_id,
				   	'img_parent_id'=> $shop_rate_id,
					'img_path' => $upload['file_name'],
					'img_width'=> $upload['image_width'],
					'img_height'=> $upload['image_height']
			   	);
			}
		   	if ( $this->Image->save( $shop_img_data, $img_id ) ) {
			   	
			   	$image = $this->Image->get_one( $img_id );

			   	$this->ps_adapter->convert_image( $image );
			   	
			   	$this->custom_response( $image );
		   	} else {
			   	$this->error_response( get_msg('file_na') );
		   	}
		
		}
	}
}