<?php 

/**
 * Suppliers Page Controller
 * @category  Controller
 */
class SuppliersController extends SecureController{
	
	/**
     * Load Record Action 
     * $arg1 Field Name
     * $arg2 Field Value 
     * $param $arg1 string
     * $param $arg1 string
     * @return View
     */
	function index($fieldname = null , $fieldvalue = null){
	
		$db = $this->GetModel();
		
		$fields = array('id', 	'office_name', 	'phone', 	'address', 	'bank_type', 	'account_no');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('office_name',"%$text%",'LIKE');
			$db->orWhere('phone',"%$text%",'LIKE');
			$db->orWhere('address',"%$text%",'LIKE');
			$db->orWhere('bank_type',"%$text%",'LIKE');
			$db->orWhere('account_no',"%$text%",'LIKE');
		}

		
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('suppliers', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('suppliers_list_title');
		$this->view->render('suppliers/list.php' , $data ,'main_layout.php');
		
	}
	
	
	/**
     * Load csv|json data
     * @return data
     */
	function import_data(){
		if(!empty($_FILES['file'])){
			$finfo = pathinfo($_FILES['file']['name']);
			$ext = strtolower($finfo['extension']);
			if(!in_array($ext , array('csv','json'))){
				set_flash_msg(get_lang('prompt_file_format_not_supported'),'danger');
			}
			else{
				
			$file_path = $_FILES['file']['tmp_name'];

				if(!empty($file_path)){
					$db = $this->GetModel();
					if($ext == 'csv'){
						$options = array('table' => 'suppliers', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'suppliers' , false );
					}
					if($db->getLastError()){
						set_flash_msg($db->getLastError(),'danger');
					}
					else{
						set_flash_msg(get_lang('prompt_data_imported'),'success');
					}
				}
				else{
					set_flash_msg(get_lang('prompt_error_uploading_file'),'success');
				}
			}
		}
		else{
			set_flash_msg(get_lang('prompt_no_file_selected'),'warning');
		}
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'suppliers/list');
		redirect_to_page($list_page);
	}

	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		$fields = array( 'id','office_name','phone','address','bank_type','account_no','putotal','patotal','rtotal');

		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}

		$record = $db->getOne( 'supp_result_vu', $fields );
        // orders and payments total
        $fields2 = array("sum(amount_num*price) as total","currency","s_id");
        $db->Where('s_id',$rec_id);
        $db->groupBy("currency");
        $total_purchases= $db->get('supp_purchases_vu',null,$fields2);

        $fields3 = array("sum(amount) as total","currency","s_id");
        $db->Where('s_id',$rec_id);
        $db->groupBy("currency");
        $total_payments= $db->get('supp_payments_vu',null,$fields3);

		if(!empty($record)){
            /*
		    // supplier payaments
            $payfields = array('id', 	'amount','currency','sup_date', 	'description', 	's_id');
            $db->where('s_id' , $rec_id);
            $db->orderBy("sup_date","DESC");
            $pay_records=$db->get('supp_payments_vu', null, $payfields);

            // supplier payaments
            $purfields = array('id','amount','bill_no','amount_num','price','currency','material_type','s_id');
            $db->where('s_id' , $rec_id);
            $db->orderBy("id","DESC");
            $pur_records=$db->get('supp_purchases_vu', null, $purfields);
            */

            $data = new stdClass;
            $data->total_purchases = $total_purchases;
            $data->total_payments = $total_payments;
            //$data->pay_records = $pay_records;
            //$data->pur_records = $pur_records;
            $data->record = $record;


            $this->view->page_title =get_lang('btn_view');
			$this->view->render('suppliers/view.php' , $data ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('suppliers/view.php' , $record , 'main_layout.php');
		}
	}


	/**
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View
     */
	function add(){
		if(is_post_request()){
			$modeldata = transform_request_data($_POST);

			$rules_array = array(
				
				'office_name' => 'required',
				'phone' => 'required',
				'address' => 'required',
				'bank_type' => 'required',
				'account_no' => 'required',
			);
			
			$is_valid = GUMP::is_valid($modeldata, $rules_array);
			
			if( $is_valid !== true) {
				if(is_array($is_valid)){
					foreach($is_valid as  $error_msg){
						$this->view->page_error[] = $error_msg;
					}
				}
				else{
					$this->view->page_error[] = $is_valid;
				}
			}

			
			
			
			
			
			if( empty($this->view->page_error) ){
				$db = $this->GetModel();
				
				$rec_id = $db->insert( 'suppliers' , $modeldata );
				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("suppliers");
					return;
				}
				else{
					if($db->getLastError()){
						$this->view->page_error[] = $db->getLastError();
					}
					else{
						$this->view->page_error[] = get_lang('prompt_error_inserting_record');
					}
				}
			}
		}
		$this->view->page_title =get_lang('txt_add_page_title');
		$this->view->render('suppliers/add.php' ,null,'main_layout.php');
	}


	/**
     * Edit Record Action 
     * If Not $_POST Request, Display Edit Record Form View
     * @return View
     */
	function edit($rec_id=null){
		$db = $this->GetModel();
		if(is_post_request()){
			$modeldata = transform_request_data($_POST);
			
			
			
			
			$rules_array = array(
				
				'office_name' => 'required',
				'phone' => 'required',
				'address' => 'required',
				'bank_type' => 'required',
				'account_no' => 'required',
			);
			
			$is_valid = GUMP::is_valid($modeldata, $rules_array);
			
			if( $is_valid !== true) {
				if(is_array($is_valid)){
					foreach($is_valid as  $error_msg){
						$this->view->page_error[] = $error_msg;
					}
				}
				else{
					$this->view->page_error[] = $is_valid;
				}
			}

			
			 
			
			
			if(empty($this->view->page_error)){
				
				$db->where('id' , $rec_id);
				
				$bool = $db->update('suppliers',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("suppliers");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','office_name','phone','address','bank_type','account_no');
		$db->where('id' , $rec_id);
		$data = $db->getOne('suppliers',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('suppliers/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('suppliers/edit.php' , $data , 'main_layout.php');
		}
	}
	



}
