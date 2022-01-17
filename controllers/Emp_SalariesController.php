<?php

/**
 * Emp_Salaries Page Controller
 * @category  Controller
 */
class Emp_SalariesController extends SecureController{
	
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
		
		$fields = array('id', 	'amount','currency', 	'description', 	'emp_id', 	's_date','emp_name');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			$db->orWhere('emp_id',"%$text%",'LIKE');
			$db->orWhere('s_date',"%$text%",'LIKE');
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
		$records = $db->get('emp_salaries_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('emp_salaries_list_title');
		$this->view->render('emp_salaries/list.php' , $data ,'main_layout.php');
		
	}

	// list on employee salaries
	function list_id($empid){
		$db = $this->GetModel();
		$fields = array('id', 	'amount','currency', 	'description', 	'emp_id','s_date','emp_name');
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->where('emp_id',$empid);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or amount LIKE ? or description LIKE ? 
            or s_date LIKE ? or emp_name LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%"));
        }

        $db->orderBy('id',"DESC");

		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('emp_salaries_vu', $limit, $fields);

        $fieldsing = array('id','emp_name','f_name','phone','salary',
            'address','start_date','job','stotal','ptotal','rtotal');
		$db->where('id' , $empid);
        $record = $db->getOne( 'emp_result_vu', $fieldsing );


        //employee salaries
        $sal_date_fields = array('s_date','amount');
        $db->where('emp_id' , $empid);
        $emp_sal_dates = $db->get('emp_salaries', null, $sal_date_fields);
        // employee sal
        $salfields = array('id','amount', 	'description', 	'emp_id', 	's_date');
        $db->where('emp_id' , $empid);
        $db->orderBy("s_date","DESC");
        $sal_records=$db->get('emp_salaries', null, $salfields);

		$data = new stdClass;

		$data->records = $records;
		$data->sal_records = $sal_records;
		$data->emp_sal_dates = $emp_sal_dates;
		$data->empid = $empid;
        $data->record = $record;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);

		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('emp_salaries_list_title');
		$this->view->render('emp_salaries/list_id.php' , $data ,'main_layout.php');
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
						$options = array('table' => 'emp_salaries', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'emp_salaries' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'emp_salaries/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 	'amount', 	'description', 	'emp_id', 	's_date' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'emp_salaries', $fields );

		if(!empty($record)){
			
			
			
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('emp_salaries/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('emp_salaries/view.php' , $record , 'main_layout.php');
		}
	}


	/**
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View

	function add(){
		if(is_post_request()){
			$modeldata = transform_request_data($_POST);

			$rules_array = array(
				'description' => 'required',
				'emp_id' => 'required|numeric',
				's_date' => 'required',
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
				
				$rec_id = $db->insert( 'emp_salaries' , $modeldata );
				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("emp_salaries");
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
		$this->view->render('emp_salaries/add.php' ,null,'main_layout.php');
	}
     */

    /**
     * Add New salary with employee id
     */
	function salarywithid($custid,$month=null)
    {
        $db = $this->GetModel();
        if (empty($custid)) {
            redirect_to_page("employees/list");
        } else {
            $fields = array('id','salary');
            $db->where('id' , $custid);
            $emp = $db->getOne('employees',$fields);
            $data['emp']=$emp;
            $data['custid']=$custid;
            $data['month']=$month;


            if (is_post_request()) {
                $modeldata = transform_request_data($_POST);

                $rules_array = array(
                    'description' => 'required',
                    'emp_id' => 'required|numeric',
                    's_date' => 'required',
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
                    $fields_emp = array( 'id', 	'amount', 	'description', 	'emp_id', 	's_date' );
                    $db->where('s_date' , $modeldata['s_date']);
                    $db->where('emp_id' , $modeldata['emp_id']);
                    $emp = $db->getOne('emp_salaries',$fields_emp);

                    if(empty($emp)){
                        $rec_id = $db->insert('emp_salaries', $modeldata);
                    }

                    if (!empty($rec_id)) {

                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'), 'success');
                        redirect_to_page("employees/list");
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
            $this->view->render('emp_salaries/salarywithid.php', $data, 'main_layout.php');
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
				's_date' => 'required',
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
				
				$bool = $db->update('emp_salaries',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("emp_salaries/list_id/".$_POST['emp_id']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','amount','description','emp_id','s_date');
		$db->where('id' , $rec_id);
		$data = $db->getOne('emp_salaries',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('emp_salaries/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('emp_salaries/edit.php' , $data , 'main_layout.php');
		}
	}
	



}
