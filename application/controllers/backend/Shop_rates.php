<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shop_rates Controller
 */
class Shop_rates extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'RESTAURANT_RATES' );
	}

	/**
	 * List down the registered users
	 */
	function index() {
		
		// no publish filter
		$conds['no_publish_filter'] = 1;

		// get rows count
		$this->data['rows_count'] = $this->Shop_rate->count_all_by( $conds );

		// get ratings
		$this->data['shopratings'] = $this->Shop_rate->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}


	/**
 	* Update the existing one
	*/
	function edit( $id ) {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'shop_rate_view' );

		// load user
		$this->data['shoprating'] = $this->Shop_rate->get_one( $id );

		// call the parent edit logic
		parent::edit( $id );
	}

	/**
	 * Show Gallery
	 *
	 * @param      <type>  $id     The identifier
	 */
	function gallery( $id ) {
		// breadcrumb urls
		$edit_item = get_msg('prd_edit');

		$this->data['action_title'] = array( 
			array( 'url' => 'edit/'. $id, 'label' => $edit_item ), 
			array( 'label' => get_msg( 'item_gallery' ))
		);
		
		$_SESSION['parent_id'] = $id;
		$_SESSION['type'] = 'item';
    	    	
    	$this->load_gallery();
    }
}