<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transactions Controller
 */
class Transactions extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'TRANSACTIONS' );

		// load the mail library
		$this->load->library( 'PS_Mail' );
	}

	/**
	 * List down the registered users
	 */
	function index() {
		
		// no publish filter
		$conds['no_publish_filter'] = 1;

		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];

		$conds['shop_id'] = $shop_id;

		// get rows count
		$this->data['rows_count'] = $this->Transactionheader->count_all_by( $conds );

		// get transactions
		$this->data['transactions'] = $this->Transactionheader->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}
	/**
	 * Searches for the first match.
	 */
	function search($status_id = 0) {
		

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'trans_search' );
		
		// condition with search term
		if($this->input->post('submit') != NULL ){

			$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )));

			// condition passing date
			$conds['date'] = $this->input->post( 'date' );

			// no publish filter
			$conds['no_publish_filter'] = 1;

			if($this->input->post('searchterm') != "") {
				$conds['searchterm'] = $this->input->post('searchterm');
				$this->data['searchterm'] = $this->input->post('searchterm');
				$this->session->set_userdata(array("searchterm" => $this->input->post('searchterm')));
			} else {
				
				$this->session->set_userdata(array("searchterm" => NULL));
			}

			if($this->input->post('date') != "") {
				$conds['date'] = $this->input->post('date');
				$this->data['date'] = $this->input->post('date');
				$this->session->set_userdata(array("date" => $this->input->post('date')));
			} else {
				
				$this->session->set_userdata(array("date" => NULL));
			}
		
			if($this->input->post('trans_status_id') != "") {
				$conds['trans_status_id'] = $this->input->post('trans_status_id');
				$this->data['trans_status_id'] = $this->input->post('trans_status_id');
				$this->session->set_userdata(array("trans_status_id" => $this->input->post('trans_status_id')));
			} else {
				
				$this->session->set_userdata(array("trans_status_id" => NULL));
			}
		
		
		} else {
			//$conds['no_publish_filter'] = 1;
			
			if($this->session->userdata('trans_status_id') != NULL){
				
				$this->data['trans_status_id'] = $this->session->userdata('trans_status_id');
				$conds['trans_status_id'] = $this->session->userdata('trans_status_id');

			} 

			//read from session value
			if($this->session->userdata('searchterm') != NULL){
				//echo "7";die;
				$conds['searchterm'] = $this->session->userdata('searchterm');
				$this->data['searchterm'] = $this->session->userdata('searchterm');
			}
			if($this->session->userdata('date') != NULL){
				$conds['date'] = $this->session->userdata('date');
				$this->data['date'] = $this->session->userdata('date');
			}


		}
			$selected_shop_id = $this->session->userdata('selected_shop_id');
			$shop_id = $selected_shop_id['shop_id'];

			$conds['shop_id'] = $shop_id;
			// pagination
			$this->data['rows_count'] = $this->Transactionheader->count_all_by( $conds );

			// search data
			$this->data['transactions'] = $this->Transactionheader->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

			$this->data['selected_shop_id'] = $shop_id;
			
			// load add list
			parent::search();
		}

	/**
	* Update the existing one
	*/
	function edit( $id ) {

		// load user
		$this->data['transaction'] = $this->Transactionheader->get_one( $id );

		redirect(site_url('admin/transactions/'));
	}

	/**
	 	* Update the existing one
		*/
	function update() {
		

		$id = $this->input->post('trans_header_id');
		$status_id = $this->input->post('trans_status_id');
		$payment_id = $this->get_data('payment_status_id');
		$delivery_boy_id = $this->get_data( 'delivery_boy_id' );
		$user_id = $this->Transactionheader->get_one( $id )->user_id;


		//get device token from user
		$device_token = $this->User->get_one($user_id)->device_token;

		
		$device_tokens[] = $device_token;
		if ($status_id != "" && $delivery_boy_id == 0 && $payment_id ==0) {
			$title = $this->Transactionstatus->get_one($status_id)->title;
			$message = "Order status has been changed to " . $title;

			$status = $this->send_notification_flutter( $device_tokens, $message, $id );
			

			if ( !$status ) $error_msg .= "Fail to push all android devices <br/>";
		} elseif($status_id == 0 && $delivery_boy_id != "" && $payment_id ==0) {
			//get device token from user
			$delivery_device_token = $this->Deliveryboy->get_one($delivery_boy_id)->device_token;
			
			$message = "You have the order to deliver.";

			$status = $this->send_notification_flutter( $device_tokens, $message, $id );

			if ( !$status ) $error_msg .= "Fail to push all android devices <br/>";
		} elseif($status_id == 0 && $delivery_boy_id == 0 && $payment_id != "") {
			$title = $this->Transactionstatus->get_one($payment_id)->title;
			$message = "Your order payment status is " . $title;

			$status = $this->send_notification_flutter( $device_tokens, $message, $id );


			if ( !$status ) $error_msg .= "Fail to push all android devices <br/>";
		}
		

		// load user
		$this->data['transaction'] = $this->Transactionheader->get_one( $id );
		
		

		parent::status_edit($id,$status_id,$payment_id,$delivery_boy_id);
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
    	define("GOOGLE_API_KEY", $this->Backend_config->get_one('be1')->fcm_api_key);  	
    		
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
	* Sending Message For Flutter App
	*/
    function send_notification_flutter( $registatoin_ids, $message, $trans_header_id) 
    {

    	// get ci instance
		$CI =& get_instance();

		//Google cloud messaging GCM-API url
    	$url = 'https://fcm.googleapis.com/fcm/send';
		
    	$message = $message;
    	$trans_header_id = $trans_header_id;

    

    	// - Testing Start
		$noti_arr = array(
    		'title' => get_msg('site_name'),
    		'body' => $message,
    		'message' => $message,
	    	'sound'=> 'default'
    	);
    	// - Testing End


    	$fields = array(
    		'notification' => $noti_arr,
    	    'registration_ids' => $registatoin_ids,
    	    'data' => array(
    	    	'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
    	    	'message' => $message,
    	    	'trans_header_id' => $trans_header_id
    	    )

    	);

    	

    	// Update your Google Cloud Messaging API Key
    	//define("GOOGLE_API_KEY", "AIzaSyAzKBPuzGuR0nlvY0AxPrXsEMBuRUxO4WE");
    	$fcm_api_key = $CI->Backend_config->get_one('be1')->fcm_api_key;
    	define("GOOGLE_API_KEY", $fcm_api_key);
    	//define("GOOGLE_API_KEY", $this->config->item( 'fcm_api_key' ));  	
    	
    	//print_r(GOOGLE_API_KEY); die;
    	//print_r($fields); die;
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
	* View transaction Detail
	*/
	function detail($id)
	{
		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'trans_detail' );

		$detail = $this->Transactionheader->get_one( $id );
		$this->data['transaction'] = $detail;

		$this->load_detail( $this->data );
	}
	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save attribute
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id  = false, $status_id = 0, $payment_id = 0, $delivery_boy_id = 0 ) {
		// save Transaction

		$data['trans_status_id'] = $status_id;
		$data['payment_status_id'] = $payment_id;
		$data['delivery_boy_id'] = $delivery_boy_id;
		$data['updated_date'] = date("Y-m-d H:i:s");

		if ( ! $this->Transactionheader->save( $data, $id )) {
			// if there is an error in inserting user data,	
				
				// rollback the transaction
			$this->db->trans_rollback();

				// set error message
			$this->data['error'] = get_msg( 'err_model' );
				
			return;
			}
			// commit the transaction
			if ( ! $this->check_trans()) {
	        	
				// set flash error message
				$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			} else {

				if ( $id ) {
				// if user id is not false, show success_add message
					
					$this->set_flash_msg( 'success', get_msg( 'success_trans_edit' ));
				}
			}


			redirect(site_url() . "/admin/transactions/detail/" . $id);
	}

	function filter_from_dashboard($status_id) {
		
		$this->session->set_userdata("trans_status_id", $status_id);

		redirect(site_url() . "/admin/transactions/search");

	}

	/**
	 * Delete the record
	 * 1) delete category
	 * 2) delete image from folder and table
	 * 3) check transactions
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );

		// delete categories and images
		$enable_trigger = true; 
		
		if ( !$this->ps_delete->delete_transaction( $id, $enable_trigger )) {

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
        	
			$this->set_flash_msg( 'success', get_msg( 'success_trans_delete' ));
		}
		
		redirect( $this->module_site_url());
	}

}