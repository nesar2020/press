<?php 

/**
 * All_Calc Page Controller
 * @category  Controller
 */
class All_CalcController extends SecureController{
	
	/**
     * Load Record Action 
     * $arg1 Field Name
     * $arg2 Field Value 
     * $param $arg1 string
     * $param $arg1 string
     * @return View
     */
	function index(){
	
		$db = $this->GetModel();
		
		//$fields = array('cpayments', 	'flex', 	'ctp', 	'paper', 	'expend', 	'supp_pay', 	'supp_pur', 	'emp_pay', 	'emp_sal');
		//page filter command
		//$tc = $db->withTotalCount();
		//$records = $db->get('all_calc', null, $fields);

        $fields = array("sum(amount) as total","currency");
        $db->groupBy("currency");
        $cpayments= $db->get('cust_payments_vu',null,$fields);

        $fields2 = array("sum(total) as total","currency");
        $db->groupBy("currency");
        $flex= $db->get('flex_orders_vu',null,$fields2);

        $fields3 = array("sum(total) as total","currency");
        $db->groupBy("currency");
        $ctp= $db->get('ctp_orders_vu',null,$fields3);

        $fields4 = array("sum(total) as total","currency");
        $db->groupBy("currency");
        $paper= $db->get('paper_orders_vu',null,$fields4);

        $fields5 = array("sum(amount) as total","currency");
        $db->groupBy("currency");
        $expend= $db->get('expenditures_vu',null,$fields5);

        $fields6 = array("sum(amount) as total","currency");
        $db->groupBy("currency");
        $supp_pay= $db->get('supp_payments_vu',null,$fields6);

        $fields7 = array("sum(amount_num*price) as total","currency");
        $db->groupBy("currency");
        $supp_pur= $db->get('supp_purchases_vu',null,$fields7);

        $fields8 = array("sum(amount) as total","currency");
        $db->groupBy("currency");
        $emp_pay= $db->get('emp_payments_vu',null,$fields8);

        $fields9 = array("sum(amount) as total","currency");
        $db->groupBy("currency");
        $emp_sal= $db->get('emp_salaries_vu',null,$fields9);

        //exchanges af to usd
        $fields10 = array("sum(amount) as afghani","sum(dollar) as dollar");
        $af2usd= $db->getOne('af2usd',$fields10);

        // exchanges usd to af
        $fields11 = array("sum(amount) as dollar","sum(afghani) afghani");
        $usd2af= $db->getOne('usd2af',$fields11);


		$data = new stdClass;

        $data->cpayments = $cpayments;
		$data->flex = $flex;
		$data->ctp = $ctp;
		$data->paper = $paper;
		$data->expend = $expend;
		$data->supp_pay = $supp_pay;
		$data->supp_pur = $supp_pur;
		$data->emp_pay = $emp_pay;
		$data->emp_sal = $emp_sal;
        $data->af2usd = $af2usd;
		$data->usd2af = $usd2af;

        //$data->records = $records;
		//$data->record_count = count($records);
		//$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('all_calc_list_title');
		$this->view->render('all_calc/list.php' , $data ,'main_layout.php');
		
	}
	
	
	



}
