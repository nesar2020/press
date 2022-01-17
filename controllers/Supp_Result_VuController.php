<?php 

/**
 * Supp_Result_Vu Page Controller
 * @category  Controller
 */
class Supp_Result_VuController extends SecureController{
	
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
		
		$fields = array('office_name', 	'phone', 	'address', 	'bank_type', 	'account_no', 	'putotal', 	'patotal', 	'rtotal');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('office_name',"%$text%",'LIKE');
			$db->orWhere('phone',"%$text%",'LIKE');
			$db->orWhere('address',"%$text%",'LIKE');
			$db->orWhere('bank_type',"%$text%",'LIKE');
			$db->orWhere('account_no',"%$text%",'LIKE');
			$db->orWhere('putotal',"%$text%",'LIKE');
			$db->orWhere('patotal',"%$text%",'LIKE');
			$db->orWhere('rtotal',"%$text%",'LIKE');
		}

		
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('supp_result_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('supp_result_vu_list_title');
		$this->view->render('supp_result_vu/list.php' , $data ,'main_layout.php');
		
	}
	
	
	

// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table

}
