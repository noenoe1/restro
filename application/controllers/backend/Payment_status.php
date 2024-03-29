<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment_status Controller
 */
class Payment_status extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'PAYMENT_STATUS' );
	}

	/**
	 * List down the registered users
	 */
	function index() {
		
		// get rows count
		$this->data['rows_count'] = $this->Paymentstatus->count_all_by( $conds );
		
		// get paymentstatus
		$this->data['paymentstatus'] = $this->Paymentstatus->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::paymentlist();
	}

	/**
	 * Searches for the first match.
	 */
	function search() {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'pay_status_search' );
		
		// condition with search term
		$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )) );
	
		// pagination
		$this->data['rows_count'] = $this->Paymentstatus->count_all_by( $conds );

		// search data paymentstatus
		$this->data['paymentstatus'] = $this->Paymentstatus->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );
		
		// load add list
		parent::paymentsearch();
	}

	/**
	 * Create new one
	 */
	function add() {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'pay_status_add' );

		// call the core add logic
		parent::paymentadd();
	}

	/**
	 * Update the existing one
	 */
	function edit( $id ) {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'pay_status_edit' );

		// load user
		$this->data['pay_status'] = $this->Paymentstatus->get_one( $id );

		// call the parent edit logic
		parent::paymentedit( $id );
	}

	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save language
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id = false ) {
		// start the transaction
		$this->db->trans_start();
		
		/** 
		 * Insert Language Records 
		 */
		$data = array();

		// prepare id
		if ( $this->has_data( 'id' )) {
			$data['id'] = $this->get_data( 'id' );
		}

		// prepare title
		if ( $this->has_data( 'title' )) {
			$data['title'] = $this->get_data( 'title' );
		}


		// save language
		if ( ! $this->Paymentstatus->save( $data, $id )) {
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
				
				$this->set_flash_msg( 'success', get_msg( 'success_pay_status_edit' ));
			} else {
			// if user id is false, show success_edit message

				$this->set_flash_msg( 'success', get_msg( 'success_pay_status_add' ));
			}
		}

		redirect( $this->module_site_url());
	}

	/**
	 * Delete
	 *
	 * @param      integer  $category_id  The category identifier
	 */
	function delete( $id = 0 )
	{
		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		// delete categories and images
		$enable_trigger = false; 

		// delete languages and images
		if ( !$this->ps_delete->delete_payment_status( $id, $enable_trigger )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success_pay_status_delete' ));
		}
		
		redirect( $this->module_site_url());

	}

	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) {
		
		$rule = 'required|callback_is_valid_name['. $id  .']';

		$this->form_validation->set_rules( 'title', get_msg( 'name' ), $rule);

		if ( $this->form_validation->run() == FALSE ) {
		// if there is an error in validating,

			return false;
		}

		return true;
	}

	/**
	 * Determines if valid name.
	 *
	 * @param      <type>   $name  The  name
	 * @param      integer  $id     The  identifier
	 *
	 * @return     boolean  True if valid name, False otherwise.
	 */
	function is_valid_name( $name, $id = 0 )
	{		
	
		$conds['title'] = $name;
		
	 	if( $id != "") {
	 		// echo "bbbb";die;
			if ( strtolower( $this->Paymentstatus->get_one( $id )->title ) == strtolower( $name )) {
			// if the name is existing name for that user id,
				return true;
			} 
		} else {
			// echo "aaaa";die;
			if ( $this->Paymentstatus->exists( ($conds ))) {

			// if the name is existed in the system,
				$this->form_validation->set_message('is_valid_name', get_msg( 'err_dup_name' ));
				return false;
			}
		}
	
		
		return true;
	}

	/**
	 * Check language name via ajax
	 *
	 * @param      boolean  $cat_id  The cat identifier
	 */
	function ajx_exists( $id = false )
	{
		// get language name

		$name = $_REQUEST['title'];

		if ( $this->is_valid_name( $name, $id )) {

		// if the language name is valid,
			
			echo "true";
		} else {
		// if invalid language name,
			
			echo "false";
		}
	}

}
?>