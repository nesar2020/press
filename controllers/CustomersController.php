<?php 

/**
 * Customers Page Controller
 * @category  Controller
 */
class CustomersController extends SecureController{
	
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
		
		$fields = array('id', 	'cust_name', 	'office_name', 	'phone', 	'email', 	'address');

		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)


        $db->Where('id',1,'!=');
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or cust_name LIKE ? or office_name LIKE ? 
            or phone LIKE ? or email LIKE ? or address LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"));
        }

		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command

		$tc = $db->withTotalCount();
		$records = $db->get('customers', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('customers_list_title');
		$this->view->render('customers/list.php' , $data ,'main_layout.php');
		
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
						$options = array('table' => 'customers', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'customers' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'customers/list');
		redirect_to_page($list_page);
	}


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
        $fields = array( 'id', 	'cust_name', 	'office_name', 	'phone', 	'email',
            'address', 	'total', 	'pay_total', 	'rtotal');
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		//$record = $db->getOne( 'customers', $fields );
        $record = $db->getOne( 'cust_result_vu', $fields );

		// orders and payments total
        $fields2 = array("ptotal as total","currency","cid");
        $db->Where('cid',$rec_id);
        $db->groupBy("currency");
        $total_paper= $db->get('paper_cust_vu',null,$fields2);

        $fields3 = array("ftotal as total","currency","cid");
        $db->Where('cid',$rec_id);
        $db->groupBy("currency");
        $total_flex= $db->get('flex_cust_vu',null,$fields3);

        $fields4 = array("ctotal as total","currency","u_id as cid");
        $db->Where('u_id',$rec_id);
        $db->groupBy("currency");
        $total_ctp= $db->get('ctp_cust_vu',null,$fields4);

        $fields5 = array("cptotal as total","currency","cust_id as cid");
        $db->Where('cust_id',$rec_id);
        $db->groupBy("currency");
        $total_pay= $db->get('pay_cust_vu',null,$fields5);

		if(!empty($record)){

		    $data = new stdClass;

            $data->record = $record;
            $data->total_paper = $total_paper;
            $data->total_flex = $total_flex;
            $data->total_ctp = $total_ctp;
            $data->total_pay = $total_pay;

			$this->view->page_title =get_lang('btn_view');
			$this->view->render('customers/view.php' , $data ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('customers/view.php' , $record , 'main_layout.php');
		}
	}


    /**
     * View different orders
     * @return View
     */
    function list_d(){

        $db = $this->GetModel();
        //$fields = array( 'id', 	'cust_name', 	'office_name', 	'phone', 	'email', 	'address' );
        $fields = array( 'id', 	'cust_name', 	'office_name', 	'phone', 	'email', 	'address', 	'total', 	'pay_total', 	'rtotal');

        $db->where('id' , 1);

        //$record = $db->getOne( 'customers', $fields );
        $record = $db->getOne( 'cust_result_vu', $fields );

        // orders and payments total
        $fields2 = array("ptotal as total","currency","cid");
        $db->Where('cid',1);
        $db->groupBy("currency");
        $total_paper= $db->get('paper_cust_vu',null,$fields2);

        $fields3 = array("ftotal as total","currency","cid");
        $db->Where('cid',1);
        $db->groupBy("currency");
        $total_flex= $db->get('flex_cust_vu',null,$fields3);

        $fields4 = array("ctotal as total","currency","u_id as cid");
        $db->Where('u_id',1);
        $db->groupBy("currency");
        $total_ctp= $db->get('ctp_cust_vu',null,$fields4);

        $fields5 = array("cptotal as total","currency","cust_id as cid");
        $db->Where('cust_id',1);
        $db->groupBy("currency");
        $total_pay= $db->get('pay_cust_vu',null,$fields5);

        if(!empty($record)){

            $data = new stdClass;
            $data->record = $record;
            $data->total_paper = $total_paper;
            $data->total_flex = $total_flex;
            $data->total_ctp = $total_ctp;
            $data->total_pay = $total_pay;

            $this->view->page_title =get_lang('btn_view');
            $this->view->render('customers/list_d.php' , $data ,'main_layout.php');
        }
        else{
            if($db->getLastError()){
                $this->view->page_error = $db->getLastError();
            }
            else{
                $this->view->page_error = get_lang('prompt_record_not_found');
            }
            $this->view->render('customers/list_d.php' , $record , 'main_layout.php');
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
				
				'cust_name' => 'required',
				'office_name' => 'required',
				'phone' => 'required',
				'email' => 'valid_email',
				'address' => 'required',
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
				
				$rec_id = $db->insert( 'customers' , $modeldata );
				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("customers");
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
		$this->view->render('customers/add.php' ,null,'main_layout.php');
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
				
				'cust_name' => 'required',
				'office_name' => 'required',
				'phone' => 'required',
				'email' => 'valid_email',
				'address' => 'required',
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
				
				$bool = $db->update('customers',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("customers");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','cust_name','office_name','phone','email','address');
		$db->where('id' , $rec_id);
		$data = $db->getOne('customers',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('customers/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('customers/edit.php' , $data , 'main_layout.php');
		}
	}
	



}
