<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deliboys Controller
 */
class Deliboys extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'DELIBOYS' );
	}

	/**
	 * List down the deliboys
	 */
	function index() {
		// no publish filter
		
		$conds = array( 'role_id' => 5 , 'status' => 0);
		// get rows count
		$this->data['rows_count'] = $this->User->count_deli_boy( $conds );

		// get deliboys
		$deliboys = $this->User->get_deli_boy( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		$this->data['deliboys'] = $deliboys;

		// load index logic
		parent::deliboyslist();
	}

	/**
	 * Searches for the first match.
	 */
	function search() {
		

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'cat_search' );
		// condition with search term
		if($this->input->post('submit') != NULL ){
		
			// condition with search term
			$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )) );
			
			if($this->input->post('searchterm') != "") {
				$conds['searchterm'] = $this->input->post('searchterm');
				$this->data['searchterm'] = $this->input->post('searchterm');
				$this->session->set_userdata(array("searchterm" => $this->input->post('searchterm')));
			} else {
				
				$this->session->set_userdata(array("searchterm" => NULL));
			}

			if($this->input->post('deliboy_status') != "") {
				$conds['deliboy_status'] = $this->input->post('deliboy_status');
				$this->data['deliboy_status'] = $this->input->post('deliboy_status');
				$this->session->set_userdata(array("deliboy_status" => $this->input->post('deliboy_status')));
			} else {
				
				$this->session->set_userdata(array("deliboy_status" => NULL));
			}
		} else {
			//read from session value
			if($this->session->userdata('searchterm') != NULL){
				//echo "7";die;
				$conds['searchterm'] = $this->session->userdata('searchterm');
				$this->data['searchterm'] = $this->session->userdata('searchterm');
			}

			if($this->session->userdata('deliboy_status') != NULL){
				
				$this->data['deliboy_status'] = $this->session->userdata('deliboy_status');
				$conds['deliboy_status'] = $this->session->userdata('deliboy_status');

			} 
			
		}
		$conds['role_id'] = 5;
		$conds['status'] = 0;
		
		// pagination
		$this->data['rows_count'] = $this->User->count_deli_boy( $conds );

		// search data

		$this->data['deliboys'] = $this->User->get_deli_boy( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );
		
		// load add list
		parent::deliboysearch();
	}


	/**
	 * Update the existing one
	 */
	function edit( $id ) {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'deliboy_edit' );

		// load user
		$deliboy = $this->User->get_one( $id );

		$this->data['deliboy'] = $deliboy;

		// call the parent edit logic
		parent::deliboysedit( $id );
	}

	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save wallpaper
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id = false ) {
		


		// start the transaction
		$this->db->trans_start();
		
		/** 
		 * Insert Wallpaper Records 
		 */
		$data = array();

		// prepare user_name
		if ( $this->has_data( 'user_name' )) {
			$data['user_name'] = $this->get_data( 'user_name' );
		}



		// user_email
		if ( $this->has_data( 'user_email' )) {
			$data['user_email'] = $this->get_data( 'user_email' );
		}

		// prepare user_phone
		if ( $this->has_data( 'user_phone' )) {
			$data['user_phone'] = $this->get_data( 'user_phone' );
		}

		// if 'is published' is checked,
		if ( $this->has_data( 'deliboy_is_published' )) {
			
			$data['status'] = $this->get_data( 'deliboy_is_published' );
		} 

		// save pending wallpaper
		if ( ! $this->User->save( $data, $id )) {
		// if there is an error in inserting user data,	

			// rollback the transaction
			$this->db->trans_rollback();

			// set error message
			$this->data['error'] = get_msg( 'err_model' );
			
			return;
		}
		//get inserted wallpaper id
		$id = ( !$id )? $data['user_id']: $id ;

		//// Start - Send Noti /////
		if($data['status'] == 1) {
			//approve so change status to publish (1)
			$message = get_msg( 'approve_message_1' ) . $data['user_name'] . get_msg( 'approve_message_2' );
		} else {
			//reject so change status to reject (3)
			$message = get_msg( 'reject_message_1' ) . $data['user_name'] . get_msg( 'reject_message_2' );
		}

		$error_msg = "";
		$success_device_log = "";

		// $added_user_id = $this->User->get_one($id)->added_user_id;
		$user_device_token = $this->User->get_one($id)->device_token;
		$user_name = $this->User->get_one($id)->user_name;
		
		//echo $user_device_token; die;

		if($user_device_token != "") {
			$devices[] = $user_device_token;
			
			$device_ids = array();
			if ( count( $devices ) > 0 ) {
				

				for($i=0; $i < count($devices); $i++) {
					$device_ids[] = $devices[0];
				}

			}

			$status = $this->send_android_fcm( $device_ids, array( "message" => $message ));
			
		}

		//// End - Send Noti /////



		/** 
		 * Check Transactions 
		 */

		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {


			if ( !$status ) {
				$error_msg .= get_msg( 'noti_sent_fail' );
				$this->set_flash_msg( 'error', get_msg( 'noti_sent_fail' ) );
			}


			if ( $status ) {
				$this->set_flash_msg( 'success', get_msg( 'noti_sent_success' ) . $user_name );
			}

		}

		redirect( $this->module_site_url());
	}

	/**
	* Sending Message From FCM For Android
	*/
	function send_android_fcm( $registatoin_ids, $message) 
    {
    	//Google cloud messaging GCM-API url
    	$url = 'https://fcm.googleapis.com/fcm/send';
    	$fields = array(
    	    'registration_ids' => $registatoin_ids,
    	    'data' => $message,
    	);
    	// Update your Google Cloud Messaging API Key
    	//define("GOOGLE_API_KEY", "AIzaSyCCwa8O4IeMG-r_M9EJI_ZqyybIawbufgg");
    	$fcm_api_key = $this->Backend_config->get_one('be1')->fcm_api_key;
    	define("GOOGLE_API_KEY", $fcm_api_key);  	
    		
    	$headers = array(
    	    'Authorization: key=' . GOOGLE_API_KEY,
    	    'Content-Type: application/json'
    	);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    	$result = curl_exec($ch);				
    	if ($result === FALSE) {
    	    die('Curl failed: ' . curl_error($ch));
    	}
    	curl_close($ch);
    	
    	return $result;
    }

	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) {
		
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
	function is_valid_name( $name, $wallpaper_id = 0 )
	{		
		
		return true;
	}

	
	/**
	 * Delete the user
	 */
	function delete( $user_id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		// delete categories and images
		if ( !$this->ps_delete->delete_user( $user_id )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			//$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success_deliboy_delete' ));
		}
		
		redirect( $this->module_site_url());
	}
	
	
}