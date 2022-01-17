<?php 
/**
 * Exchanges Page Controller
 * @category  Controller
 */
class ExchangesController extends BaseController{
	/**
     * Load Record Action 
     * $arg1 Field Name
     * $arg2 Field Value 
     * $param $arg1 string
     * $param $arg1 string
     * @return View
     */
	function index($fieldname = null , $fieldvalue = null){

		$this->view->page_title ="تبادله اسعار";
		$this->view->render('exchanges/index.php' ,null ,'main_layout.php');
	}


	function usd2af($fieldname = null , $fieldvalue = null){
		$db = $this->GetModel();
		$fields = array('id', 	'amount', 	'rate', 	'currency','afghani');
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		if(!empty($this->search)){
			$text = $this->search;
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('rate',"%$text%",'LIKE');
			$db->orWhere('currency',"%$text%",'LIKE');
			$db->orWhere('afghani',"%$text%",'LIKE');

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
		$records = $db->get('usd2af', $limit, $fields);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title ="Exchanges";
		$this->view->render('exchanges/usd2af.php' , $data ,'main_layout.php');
	}


	function af2usd($fieldname = null , $fieldvalue = null){
		$db = $this->GetModel();
		$fields = array('id', 	'amount', 	'rate', 	'currency','dollar');
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		if(!empty($this->search)){
			$text = $this->search;
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('rate',"%$text%",'LIKE');
			$db->orWhere('currency',"%$text%",'LIKE');
			$db->orWhere('dollar',"%$text%",'LIKE');

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
		$records = $db->get('af2usd', $limit, $fields);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title ="Exchanges";
		$this->view->render('exchanges/af2usd.php' , $data ,'main_layout.php');
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
				set_flash_msg("فرمت فایل پشتیبانی نمی شود",'danger');
			}
			else{
			$file_path = $_FILES['file']['tmp_name'];
				if(!empty($file_path)){
					$db = $this->GetModel();
					if($ext == 'csv'){
						$options = array('table' => 'exchanges', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'exchanges' , false );
					}
					if($db->getLastError()){
						set_flash_msg($db->getLastError(),'danger');
					}
					else{
						set_flash_msg("رکورد با موفقیت وارد شد",'success');
					}
				}
				else{
					set_flash_msg("خطا در بارگیری فایل",'success');
				}
			}
		}
		else{
			set_flash_msg("فایل دانلود نشده انتخاب نشده است",'warning');
		}
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'exchanges/list');
		redirect_to_page($list_page);
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
				'rate' => 'required|numeric',
				'currency' => 'required|numeric',
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
				$rec_id = $db->insert( 'exchanges' , $modeldata );
				if(!empty($rec_id)){
					set_flash_msg("تبادله موافقانه اضافه شد.",'success');
					redirect_to_page("exchanges");
					return;
				}
				else{
					if($db->getLastError()){
						$this->view->page_error[] = $db->getLastError();
					}
					else{
						$this->view->page_error[] = "خطا در ثبت تبادله";
					}
				}
			}
		}
		$this->view->page_title ="تبادله جدید";
		$this->view->render('exchanges/add.php' ,null,'main_layout.php');
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
				'rate' => 'required|numeric',
				'currency' => 'required|numeric',
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
				$bool = $db->update('exchanges',$modeldata);
				if($bool){
					set_flash_msg(" با موفقیت به روز شد",'success');
					redirect_to_page("exchanges");
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}
		$fields = array('id','amount','rate','currency');
		$db->where('id' , $rec_id);
		$data = $db->getOne('exchanges',$fields);
		$this->view->page_title ="ویرایش";
		if(!empty($data)){
			$this->view->render('exchanges/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = "رکورد یافت نشد";
			}
			$this->view->render('exchanges/edit.php' , $data , 'main_layout.php');
		}
	}
	/**
     * Delete Record Action 
     * @return View
     */
	function delete( $rec_ids = null ){
		$db = $this->GetModel();
		$arr_id = explode( ',', $rec_ids );
		foreach( $arr_id as $rec_id ){
			$db->where('id' , $rec_id,"=",'OR');
		}
		$bool = $db->delete( 'exchanges' );
		if($bool){
			set_flash_msg("رکورد حذف شد",'success');
		}
		else{
			if($db->getLastError()){
				set_flash_msg($db->getLastError(),'danger');
			}
			else{
				set_flash_msg("خطا هنگام حذف رکورد لطفا مطمئن شوید که وارد به سیستم  استید",'danger');
			}
		}
		redirect_to_page("exchanges");
	}
}
