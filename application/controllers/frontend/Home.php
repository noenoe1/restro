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
				$this->load_template( 'home' );
			}
		}
	}

}
