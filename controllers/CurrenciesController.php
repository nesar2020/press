<?php 
/**
 * Currencies Page Controller
 * @category  Controller
 */
class CurrenciesController extends BaseController{
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
		$fields = array('id', 	'currency');
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		if(!empty($this->search)){
			$text = $this->search;
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('currency',"%$text%",'LIKE');
		}
		if(!empty($this->orderby)){ // when order by request fields (from $_GET param)
			$db->orderBy($this->orderby,$this->ordertype);
		}
		else{
			$db->orderBy('id', ORDER_TYPE);
		}
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('currencies', $limit, $fields);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title ="Currencies";
		$this->view->render('currencies/list.php' , $data ,'main_layout.php');
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
				set_flash_msg("تنسيق ملف غير معتمد",'danger');
			}
			else{
			$file_path = $_FILES['file']['tmp_name'];
				if(!empty($file_path)){
					$db = $this->GetModel();
					if($ext == 'csv'){
						$options = array('table' => 'currencies', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'currencies' , false );
					}
					if($db->getLastError()){
						set_flash_msg($db->getLastError(),'danger');
					}
					else{
						set_flash_msg("البيانات المستوردة بنجاح",'success');
					}
				}
				else{
					set_flash_msg("خطأ في تحميل الملف",'success');
				}
			}
		}
		else{
			set_flash_msg("لم يتم تحديد أي ملف للتحميل",'warning');
		}
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'currencies/list');
		redirect_to_page($list_page);
	}
	/**
     * View Record Action 
     * @return View
     *
	function view( $rec_id = null , $value = null){
		$db = $this->GetModel();
		$fields = array( 'id', 	'currency' );
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		$record = $db->getOne( 'currencies', $fields );
		if(!empty($record)){
			$this->view->page_title ="نمایش";
			$this->view->render('currencies/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = "لم يتم العثور على السجل";
			}
			$this->view->render('currencies/view.php' , $record , 'main_layout.php');
		}
	}
	*
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View
     */
	function add(){
		if(is_post_request()){
			$modeldata = transform_request_data($_POST);
			$rules_array = array(
				'currency' => 'required',
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
				$rec_id = $db->insert( 'currencies' , $modeldata );
				if(!empty($rec_id)){
					set_flash_msg("رکورد موفقانه اضافه شد.",'success');
					redirect_to_page("currencies");
					return;
				}
				else{
					if($db->getLastError()){
						$this->view->page_error[] = $db->getLastError();
					}
					else{
						$this->view->page_error[] = "ثبت با مشکل مواجه شد.";
					}
				}
			}
		}
		$this->view->page_title ="اضف جديد";
		$this->view->render('currencies/add.php' ,null,'main_layout.php');
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
				'currency' => 'required',
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
				$bool = $db->update('currencies',$modeldata);
				if($bool){
					set_flash_msg("رکورد موفقانه اصلاح شد.",'success');
					redirect_to_page("currencies");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}
		$fields = array('id','currency');
		$db->where('id' , $rec_id);
		$data = $db->getOne('currencies',$fields);
		$this->view->page_title ="تصحيح";
		if(!empty($data)){
			$this->view->render('currencies/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = "تصحیح با مشکل مواجه شد.";
			}
			$this->view->render('currencies/edit.php' , $data , 'main_layout.php');
		}
	}
}
