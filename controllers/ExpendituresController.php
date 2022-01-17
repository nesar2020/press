<?php 

/**
 * Expenditures Page Controller
 * @category  Controller
 */
class ExpendituresController extends SecureController{
	
	/**
     * Load Record Action 
     * $arg1 Field Name
     * $arg2 Field Value 
     * $param $arg1 string
     * $param $arg1 string
     * @return View
     */
	function index($fieldname = null , $fieldvalue = null)
    {
        $db = $this->GetModel();
        $startdate = "";
        $enddate = "";
        $name = "";
        if (!empty($_GET['e_date']) AND !empty($_GET['e_date'])) {
            $vals = explode('to', str_replace(' ', '', $_GET['e_date']));
            $startdate = $vals[0];
            $enddate = $vals[1];
            $name = $_GET['name'];
            $limit = "";
        }elseif (!empty($_GET['e_date'])){
            $vals = explode('to', str_replace(' ', '', $_GET['e_date']));
            $startdate = $vals[0];
            $enddate = $vals[1];
            $limit = "";
        }elseif (!empty($_GET['name'])){
            $name = $_GET['name'];
            $limit = "";
        }else{
            $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        }

        if(!empty($_GET['name'])){
            $name = $_GET['name'];
            $limit="";
        }else{
            $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        }
		
		$fields = array('id', 'name',	'amount','currency', 	'description', 	'ex_date');

		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
            $db->orWhere('name',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			//$db->orWhere('ex_date',"%$text%",'LIKE');
		}

		
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}else{
            $db->orderBy('id','DESC');
        }

        if(!empty($_GET['e_date']) AND !empty($_GET['name'])){
            $db->where("expenditures.ex_date BETWEEN '$startdate' AND '$enddate'");
            $db->where('name',$name);
            $db->orderBy("ex_date","DESC");
        }elseif(!empty($_GET['e_date'])){
            $db->where("expenditures.ex_date BETWEEN '$startdate' AND '$enddate'");
            $db->orderBy("ex_date","DESC");
        }elseif(!empty($_GET['name'])){
            $db->where('name',$name);
            $db->orderBy("ex_date","DESC");
        }
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('expenditures_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('expenditures_list_title');
		$this->view->render('expenditures/list.php' , $data ,'main_layout.php');
		
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
						$options = array('table' => 'expenditures', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'expenditures' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'expenditures/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 'name',	'amount', 	'description', 	'ex_date' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'expenditures', $fields );

		if(!empty($record)){
			
			
			
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('expenditures/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('expenditures/view.php' , $record , 'main_layout.php');
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
				'ex_date' => 'required',
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
				
				$rec_id = $db->insert( 'expenditures' , $modeldata );
				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("expenditures");
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
		$this->view->render('expenditures/add.php' ,null,'main_layout.php');
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
				'ex_date' => 'required',
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
				
				$bool = $db->update('expenditures',$modeldata);
				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("expenditures");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','name','amount','currency','description','ex_date');
		$db->where('id' , $rec_id);
		$data = $db->getOne('expenditures',$fields);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('expenditures/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('expenditures/edit.php' , $data , 'main_layout.php');
		}
	}

    function del( $rec_ids = null ){
        $db = $this->GetModel();
        $arr_id = explode( ',', $rec_ids );
        foreach( $arr_id as $rec_id ){
            $db->where('id' , $rec_id,"=",'OR');
        }
        $bool = $db->delete( 'expenditures' );
        if($bool){
            set_flash_msg("حذف موفقانه انجام شد.",'success');
        }
        else{
            if($db->getLastError()){
                set_flash_msg($db->getLastError(),'danger');
            }
            else{
                set_flash_msg("خطأ درحذف",'danger');
            }
        }
        redirect_to_page("expenditures");
    }


}
