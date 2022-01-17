<?php 

/**
 * Paper_Orders_Vu Page Controller
 * @category  Controller
 */
class Paper_Orders_VuController extends SecureController{
	
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
		
		$fields = array('description','po_date','plate_type','plate_no','plate_price','total','cid');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			$db->orWhere('po_date',"%$text%",'LIKE');
			$db->orWhere('plate_type',"%$text%",'LIKE');
			$db->orWhere('plate_no',"%$text%",'LIKE');
			$db->orWhere('plate_price',"%$text%",'LIKE');
			$db->orWhere('total',"%$text%",'LIKE');
			$db->orWhere('cid',"%$text%",'LIKE');
		}

		
		if(!empty($this->orderby)){
			$db->orderBy($this->orderby,$this->ordertype);
		}
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('paper_orders_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('paper_orders_vu_list_title');
		$this->view->render('paper_orders_vu/list.php' , $data ,'main_layout.php');
		
	}
	
	
	

// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table

}
