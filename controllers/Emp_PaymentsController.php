<?php 

/**
 * Emp_Payments Page Controller
 * @category  Controller
 */
class Emp_PaymentsController extends SecureController{
	
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
		
		$fields = array('id', 	'amount','currency', 	'description', 	'emp_id', 	'p_date','emp_name');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			$db->orWhere('emp_id',"%$text%",'LIKE');
			$db->orWhere('p_date',"%$text%",'LIKE');
			$db->orWhere('emp_name',"%$text%",'LIKE');

		}
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('emp_payments_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);

		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('emp_payments_list_title');
		$this->view->render('emp_payments/list.php' , $data ,'main_layout.php');
		
	}
	
	function list_id($empid){

		$db = $this->GetModel();

		$fields = array('id', 	'amount','currency', 	'description', 	'emp_id', 	'p_date','emp_name');

		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->where('emp_id',$empid);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or amount LIKE ? or description LIKE ? 
            or p_date LIKE ? or emp_name LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%"));
        }

        $db->orderBy('id',"DESC");
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('emp_payments_vu', $limit, $fields);

		$data = new stdClass;

		$data->records = $records;
        $data->empid = $empid;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);

		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('emp_payments_list_title');
		$this->view->render('emp_payments/list_id.php' , $data ,'main_layout.php');

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
						$options = array('table' => 'emp_payments', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'emp_payments' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'emp_payments/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 	'amount', 	'description', 	'emp_id', 	'p_date' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'emp_payments', $fields );

		if(!empty($record)){
			
			
			
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('emp_payments/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('emp_payments/view.php' , $record , 'main_layout.php');
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
				
				'amount' => 'required|numeric',
				'description' => 'required',
				'emp_id' => 'required|numeric',
				'p_date' => 'required',
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
				
				$rec_id = $db->insert( 'emp_payments' , $modeldata );
				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("emp_payments");
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
		$this->view->render('emp_payments/add.php' ,null,'main_layout.php');
	}

    /**
     * Add new payment to employees
     */
	function paywithid($custid)
    {
        if (empty($custid)) {
            redirect_to_page("employees/list");
        } else {
            if (is_post_request()) {
                $modeldata = transform_request_data($_POST);


                $rules_array = array(

                    'amount' => 'required|numeric',
                    'description' => 'required',
                    'emp_id' => 'required|numeric',
                    'p_date' => 'required',
                );

                $is_valid = GUMP::is_valid($modeldata, $rules_array);

                if ($is_valid !== true) {
                    if (is_array($is_valid)) {
                        foreach ($is_valid as $error_msg) {
                            $this->view->page_error[] = $error_msg;
                        }
                    } else {
                        $this->view->page_error[] = $is_valid;
                    }
                }


                if (empty($this->view->page_error)) {
                    $db = $this->GetModel();

                    $rec_id = $db->insert('emp_payments', $modeldata);
                    if (!empty($rec_id)) {


                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'), 'success');
                        redirect_to_page("emp_payments");
                        return;
                    } else {
                        if ($db->getLastError()) {
                            $this->view->page_error[] = $db->getLastError();
                        } else {
                            $this->view->page_error[] = get_lang('prompt_error_inserting_record');
                        }
                    }
                }
            }
            $this->view->page_title = get_lang('txt_add_page_title');
            $this->view->render('emp_payments/paywithid.php', $custid, 'main_layout.php');
        }
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
				
				'amount' => 'required|numeric',
				'description' => 'required',
				'emp_id' => 'required|numeric',
				'p_date' => 'required',
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
				
				$bool = $db->update('emp_payments',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("emp_payments/list_id/".$_POST['emp_id']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','amount','description','emp_id','p_date');
		$db->where('id' , $rec_id);
		$data = $db->getOne('emp_payments',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('emp_payments/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('emp_payments/edit.php' , $data , 'main_layout.php');
		}
	}
	



}
