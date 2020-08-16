<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Front End Controller
 */
class Guestajax extends Ajax_Controller {

	/**
	 * Construct
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->library( "PS_Auth" );
		$this->load->library( "PS_Widget" );
		$this->load->library( "PS_Mail" );
	}

	function add_cart_products()
	{
		$data = array();
		$this->set_data( $data, 'product_id' );
		$this->set_data( $data, 'qty' );
		$this->set_data( $data, 'session_id' );
		if ( !$this->Productcart->save( $data )) {
		// if an error in saving contact,

			$this->error_response( get_msg( 'err_model' ));
		}

		$this->success_response( get_msg( 'success_save_basket' ));
	}

}