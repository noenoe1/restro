<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for productcart table
 */
class Productcart extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'rt_cart_products', 'id', 'cart' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{

		// session_id condition
		if ( isset( $conds['session_id'] )) {
			$this->db->where( 'session_id', $conds['session_id'] );
		}

		// product_id condition
		if ( isset( $conds['product_id'] )) {
			$this->db->where( 'product_id', $conds['product_id'] );
		}

		// user_id condition
		if ( isset( $conds['user_id'] )) {
			$this->db->where( 'user_id', $conds['user_id'] );
		}

		// qty condition
		if ( isset( $conds['qty'] )) {
			$this->db->where( 'qty', $conds['qty'] );
		}

	}
}