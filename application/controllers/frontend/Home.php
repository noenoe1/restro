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
    	$conds['cat_id'] = $cat_id;
    	$products = $this->Product->get_all_by($conds)->result();
    	if ( !empty( $products )) foreach( $products as $prd ) {


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

}
