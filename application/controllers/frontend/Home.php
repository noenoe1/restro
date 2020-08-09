<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Front End Controller
 */
class Home extends FE_Controller 
{

	/**
	 * constructs required variables
	 */
	function __construct()
	{
		parent::__construct( NO_AUTH_CONTROL, 'HOME' );

	}

	/**
	 *  Home Page
	 */
	function home()
	{
		$this->load_template( 'home' );
	}

	/**
	 *  Home Search
	 */
	function reservation()
	{
		$logged_in_user = $this->ps_auth->get_user_info();
		if ( !isset( $logged_in_user->user_id )) {
			$this->load_template( 'login' );
		} else {
			if ( $this->is_POST() ) {
				// resv_date
			   	if ( $this->has_data( 'resv_date' )) {
					$data['resv_date'] = $this->get_data( 'resv_date' );

				}

			   	// resv_time
			   	if ( $this->has_data( 'resv_time' )) {
					$data['resv_time'] = $this->get_data( 'resv_time' );
				}

				// party_size
			   	if ( $this->has_data( 'party_size' )) {
					$data['note'] = $this->get_data( 'party_size' );
				}

				// shop_id
			   	if ( $this->has_data( 'shop_id' )) {
					$data['shop_id'] = $this->get_data( 'shop_id' );
				}
				$data['status_id'] = 1;
				// set timezone
				if($id == "") {
					//save
					$data['added'] = date("Y-m-d H:i:s");

				} 
				//save item
				if ( ! $this->Reservation->save( $data, $id )) {
				// if there is an error in inserting user data,	

					// rollback the transaction
					$this->db->trans_rollback();

					// set error message
					$this->data['error'] = get_msg( 'err_model' );
					
					return;
				}

				/** 
				 * Check Transactions 
				 */

				// commit the transaction
				if ( ! $this->check_trans()) {
		        	
					// set flash error message
					$this->set_flash_msg( 'error', get_msg( 'err_model' ));
				} else {

					if ( $id ) {
					// if user id is not false, show success_add message
						
						$this->set_flash_msg( 'success', get_msg( 'success_resv_edit' ));
					} else {
					// if user id is false, show success_edit message

						$this->set_flash_msg( 'success', get_msg( 'success_resv_add' ));
					}
				}
				redirect( site_url( ) . '/home');
			}
		}
	}

	function get_all_products( $cat_id )
    {
    	

    	if($cat_id == "000") {
    		$conds['cat_id'] = "";
    		$products = $this->Product->get_all_by($conds, 10)->result();
    	} else {
    		$conds['cat_id'] = $cat_id;
    		$products = $this->Product->get_all_by($conds)->result();
    	}

    	
    	
    	if ( !empty( $products )) foreach( $products as $prd ) {

    		$length = 120; 
            $description = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $prd->description);
            $prd->readmore = $description;
			$conds = array( 'img_type' => 'product', 'img_parent_id' => $prd->id );
			
			$images = $this->Image->get_all_by( $conds )->result();

			$prd->img = img_url( $images[0]->img_path );
		}	
		echo json_encode($products);
    }

    /**
	 * LOgin Page
	 */
	function userlogin() {
		
		if ( $this->ps_auth->is_logged_in() ) {
		 // if the user is already logged in, redirect to respective url
			
			$this->redirect_url();
		}

		if ( $this->is_POST() ) {
		// if the user is not yet logged in, authenticate url or load the login form view

			if ( $this->is_valid_login_input()) {
			// if valid input,

				// if request is post method, login and redirect
				$user_email = $this->get_data( 'user_email' );
				$user_password = $this->get_data( 'user_password' );

				if ( ! $this->ps_auth->login( $user_email, $user_password )) {
				// if credentail is wrong, redirect to login
				
					$this->set_flash_msg( 'error', 'error_login' );
					redirect( 'userlogin' );
				} else {
					$conds['user_email'] = $user_email;
					$role_id = $this->User->get_one_by($conds)->role_id;
					if ($role_id != '4') {
						// if credential is correct, redirect to respective url

						$this->redirect_url();
					} else {
						$this->set_flash_msg('error',"You don't have access to admin panel.");
					}
				}
			}

			$this->load_template( 'home' );
			header("Refresh:0");
		} else {
			// load login form 
			$this->load_template( 'login' );
		}
		
	}

	/**
	 * redirects to the respective urls based on user action
	 * 
	 */
	function redirect_url()
	{
		/* different urls based on user credential */
		$admin_url = site_url( 'home' );
		$login_url = site_url( 'userlogin ');
		$frontend_url = site_url();

		if ( null !== $this->session->userdata( 'source_url' )) {
		// if coming from existing url
			
			$source_url = $this->session->userdata( 'source_url' );
			$this->session->unset_userdata( 'source_url' );
			redirect( $source_url );		

		} else if ( !$this->ps_auth->is_logged_in() ) {
		// if user is not yet logged in, redirect to login
		
			redirect( $login_url );
		} else if ( $this->ps_auth->is_frontend_user() ) {
		// if the logged in user is frontend user, 

			redirect( $frontend_url );
		} else if ( $this->ps_auth->is_system_user() ) {
		// if the logged in user is system user, redirect to admin
			
			redirect( $admin_url );
		} else {
		// if the logged in user is not frontend user, redirect to dashbaord
			
			//$this->goto_approved_cities();
		}
	}

	/**
	 * Determines if valid input
	 */
	function is_valid_login_input() {

		$validation = array(
			array(
				'field' => 'user_email',
				'label' => get_msg( 'email' ),
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'user_password',
				'label' => get_msg( 'password' ),
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules( $validation );

		if ( $this->form_validation->run() == FALSE ) {
		// if there is an error in validating, 
			
			$this->session->set_flashdata('error', validation_errors());
			return false;
		}

		return true;
	}

	/**
	 * Logout from the system
	 */
	function userlogout() {
		// logout 
		$this->ps_auth->logout();

		// redirect
		$this->redirect_url();
	}

	/**
	 * Contact Us from the system
	 */
	function contactus() {
		if ( $this->is_POST() ) {
			//name
		   	if ( $this->has_data( 'name' )) {
				$data['name'] = $this->get_data( 'name' );

			}

		   	// email
		   	if ( $this->has_data( 'email' )) {
				$data['email'] = $this->get_data( 'email' );
			}

			// phone
		   	if ( $this->has_data( 'phone' )) {
				$data['phone'] = $this->get_data( 'phone' );
			}

			// message
		   	if ( $this->has_data( 'message' )) {
				$data['message'] = $this->get_data( 'message' );
			}

			// set timezone

			if($contact_id == "") {
				//save
				$data['added_date'] = date("Y-m-d H:i:s");

			}
			// print_r($data);die;
			//save item
			if ( ! $this->Contact->save( $data, $id )) {
			// if there is an error in inserting user data,	

				// rollback the transaction
				$this->db->trans_rollback();

				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return;
			}

			
			/** 
			 * Check Transactions 
			 */

			// commit the transaction
			if ( ! $this->check_trans()) {
	        	
				// set flash error message
				$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			} else {

				if ( $contact_id ) {
				// if user id is not false, show success_add message
					
					$this->set_flash_msg( 'success', get_msg( 'success_contact_edit' ));
				} else {
				// if user id is false, show success_edit message

					$this->set_flash_msg( 'success', get_msg( 'success_contact_add' ));
				}
			}

		}
		$this->load_template( 'contact' );
	}

	/**
	 *  Restaurant Page
	 */
	function restaurant($id)
	{
		$this->data['shop_id'] = $id;
		$this->load_template( 'restaurant' );
	}

	/**
	 * Search Result Page
	 */
	function search()
	{
		// condition with search term
		if($this->input->post('submit') != NULL ){
			if ($this->get_data('cat_id') != '' || $this->get_data('cat_id') != '0') {
				$conds['cat_id'] = $this->get_data('cat_id');
				$this->session->set_userdata(array("cat_id" => $this->input->post('cat_id')));
				$this->data['cat_id'] = $this->session->userdata('cat_id');
				$this->data['selected_cat_id'] = $this->input->post('cat_id');
			}

			if ($this->get_data('sub_cat_id') != '' || $this->get_data('sub_cat_id') != '0') {
				$conds['sub_cat_id'] = $this->get_data('sub_cat_id');
				$this->session->set_userdata(array("sub_cat_id" => $this->input->post('sub_cat_id')));
				$this->data['sub_cat_id'] = $this->session->userdata('sub_cat_id');
			}
		} else {
			if($this->session->userdata('cat_id') != NULL){
				$conds['cat_id'] = $this->session->userdata('cat_id');
				$this->data['cat_id'] = $this->session->userdata('cat_id');
				$this->data['selected_cat_id'] = $this->session->userdata('cat_id');
			}

			if($this->session->userdata('sub_cat_id') != NULL){
				$conds['sub_cat_id'] = $this->session->userdata('sub_cat_id');
				$this->data['sub_cat_id'] = $this->session->userdata('sub_cat_id');
			}
		}
		$conds['status'] = 1;
		$this->data['products'] = $this->Product->get_all_by( $conds, $limit, $offset );
		$this->load_template( 'restaurant' );
	}

	function get_all_sub_categories( $cat_id )
	{
		$conds['cat_id'] = $cat_id;
		$sub_categories = $this->Subcategory->get_all_by($conds);
		echo json_encode($sub_categories->result());
	}

	/**
	 * Blog Page
	 */
	function blog($page=1)
	{
		$total = $this->Feed->count_all_by( array( 'no_publish_filter' => 1 ) );
	 	$pag = $this->config->item( 'blog_display_limit' );
	 	$noofpage = ceil($total/$pag);
	 	$conds['status'] = 1;
	 	$offset = (($page-1)*$pag);
	 	$limit = $pag;
		$blogs = $this->Feed->get_all_by( array( 'no_publish_filter' => 1 ), $limit, $offset );
		$this->data['blogs'] = $blogs;
		$this->load_template('blog');
	}

	/**
	 * Blog Detail Page
	 */
	function blogdetail($id)
	{
		// load blog
		$this->data['blog'] = $this->Feed->get_one( $id );
		$this->load_template('blogdetail');
	}

	/**
	 * Food Detail Page
	 */
	function food_detail($id)
	{
		// load blog
		$this->data['product'] = $this->Product->get_one( $id );
		$this->load_template('food_detail');
	}

}