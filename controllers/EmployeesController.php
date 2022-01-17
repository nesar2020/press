<?php 

/**
 * Employees Page Controller
 * @category  Controller
 */
class EmployeesController extends SecureController{
	
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
		
		$fields = array('id', 	'emp_name', 	'f_name', 	'phone', 	'address', 	'start_date', 	'job','salary');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        // only list employee with active status
        $db->where('status',1);

        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or emp_name LIKE ? or f_name LIKE ? 
            or phone LIKE ? or start_date LIKE ? or address LIKE ? or address LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"));
        }

		
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('employees', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('employees_list_title');
		$this->view->render('employees/list.php' , $data ,'main_layout.php');
		
	}
	
	function list_deactivate(){

		$db = $this->GetModel();

		$fields = array('id', 	'emp_name', 	'f_name', 	'phone', 	'address', 	'start_date', 	'job','salary');

		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        // only list employee with active status
        $db->where('status',0);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or emp_name LIKE ? or f_name LIKE ? 
            or phone LIKE ? or start_date LIKE ? or address LIKE ? or address LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"));
        }


			$db->orderBy('id',"DESC");

		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('employees', $limit, $fields);

		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);



		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('employees_list_title');
		$this->view->render('employees/list_deactivate.php' , $data ,'main_layout.php');

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
						$options = array('table' => 'employees', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'employees' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'employees/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
		$db = $this->GetModel();
        $fields = array('id','emp_name','f_name','phone','salary',
            'address','start_date','job','stotal','ptotal','rtotal');

		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}

		$record = $db->getOne( 'emp_result_vu', $fields );

		if(!empty($record)){

            /* employee pay
            $payfields = array('id', 	'amount', 	'description', 	'emp_id', 	'p_date');
            $db->where('emp_id' , $rec_id);
            $db->orderBy("p_date","DESC");
            $pay_records=$db->get('emp_payments', null, $payfields);
            */
            $data = new stdClass;

            //$data->pay_records = $pay_records;
            //$data->sal_records = $sal_records;
            //$data->emp_sal_dates = $emp_sal_dates;
            $data->record = $record;


            $this->view->page_title =get_lang('btn_view');
			$this->view->render('employees/view.php' , $data ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('employees/view.php' , $record , 'main_layout.php');
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
				'emp_name' => 'required',
				'f_name' => 'required',
				'phone' => 'required',
				'address' => 'required',
				'start_date' => 'required',
				'job' => 'required',
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
				
				$rec_id = $db->insert( 'employees' , $modeldata );
				if(!empty($rec_id)){

					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("employees");
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
		$this->view->render('employees/add.php' ,null,'main_layout.php');
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
				
				'emp_name' => 'required',
				'f_name' => 'required',
				'phone' => 'required',
				'address' => 'required',
				'start_date' => 'required',
				'job' => 'required',
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
				
				$bool = $db->update('employees',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("employees");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','emp_name','f_name','phone','address','start_date','job','salary','status');
		$db->where('id' , $rec_id);
		$data = $db->getOne('employees',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('employees/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('employees/edit.php' , $data , 'main_layout.php');
		}
	}


    function payall($month=null){

        $db = $this->GetModel();

        if(!empty($month)){
            $fields = array('s_date');
            $db->where('s_date' , $month);
            $date_records = $db->get('emp_salaries', null, $fields);
            if(empty($date_records)){
                $emp_fields = array('id','salary');
                $db->where('status' , 1);
                $employees = $db->get('employees', null, $emp_fields);
                if(!empty($employees)){
                    $emp_inserts=array();
                    foreach($employees as $emp){
                        $emp_one=array(
                            'amount'=>$emp['salary'],
                            's_date'=>$month,
                            'description'=>"$month معاش ماه ",
                            'emp_id'=>$emp['id']
                        );
                        $emp_inserts[]=$emp_one;
                    }

                    $rec_id = $db->insertMulti( 'emp_salaries' , $emp_inserts );
                    if($rec_id){
                        set_flash_msg("معاش تمام کارمندان اضافه شد.",'success');
                    }
                    redirect_to_page("employees/payall");
                }else{
                    redirect_to_page("employees/payall");
                }

            }else{
                redirect_to_page("employees/payall");
            }
        }else{
            // to get total salaries of all employees
            $tot_sal_fields = array('sum(salary) as total_sal');
            $tot_sal_records = $db->get('employees', null, $tot_sal_fields);

            // months from salaries table
            $fields = array('sum(amount)as tot, s_date');

            //page filter command
            $tc = $db->withTotalCount();
            $db->groupBy('s_date');
            $records = $db->get('emp_salaries', null, $fields);

            $data = new stdClass;

            $data->records = $records;
            $data->record_count = count($records);
            $data->total_records = intval($tc->totalCount);
            $data->tot_sal = $tot_sal_records;

            if($db->getLastError()){
                $this->view->page_error = $db->getLastError();
            }
            $this->view->page_title =get_lang('employees_list_title');
            $this->view->render('employees/payall.php' , $data ,'main_layout.php');
        }
    }



}
