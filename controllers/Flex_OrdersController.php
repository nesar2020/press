<?php 

/**
 * Flex_Orders Page Controller
 * @category  Controller
 */
class Flex_OrdersController extends SecureController{
	
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
		
		$fields = array('id', 	'description', 	'material_type', 	'f_length', 'f_width', 	'f_price',
            'currency', 'f_number', 'cid', 	'fo_date','cust_name','total','pay');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			$db->orWhere('material_type',"%$text%",'LIKE');
			$db->orWhere('f_length',"%$text%",'LIKE');
			$db->orWhere('f_width',"%$text%",'LIKE');
			$db->orWhere('f_price',"%$text%",'LIKE');
			$db->orWhere('f_number',"%$text%",'LIKE');
			$db->orWhere('cid',"%$text%",'LIKE');
			//$db->orWhere('fo_date',"%$text%",'LIKE');
			$db->orWhere('cust_name',"%$text%",'LIKE');

		}


        $db->orderBy("id","DESC");
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('flex_orders_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('flex_orders_list_title');
		$this->view->render('flex_orders/list.php' , $data ,'main_layout.php');
		
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
						$options = array('table' => 'flex_orders', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'flex_orders' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'flex_orders/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 	'description', 	'material_type', 	'f_length', 	'f_width', 	'f_price', 	'f_number', 	'cid', 	'fo_date' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'flex_orders', $fields );

		if(!empty($record)){
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('flex_orders/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('flex_orders/view.php' , $record , 'main_layout.php');
		}
	}


	/**
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View
     */
	function add(){
		if(is_post_request()){
            //get payment for order
            $order_pay=$_POST['amount'];
            $order_pay_array=array();
            $order_pay_array['amount']=$order_pay;
            $order_pay_array['cust_date']=$_POST['fo_date'];
            $order_pay_array['description']="(فلکس) - ".$_POST['description'];
            $order_pay_array['cust_id']=$_POST['cid'];;
            $order_pay_array['order_type']="flex";
            // remove amount value
            unset($_POST['amount']);

			$modeldata = transform_request_data($_POST);
			
			
			
			$rules_array = array(
				
				'description' => 'required',
				'material_type' => 'required',
				'f_length' => 'required|numeric',
				'f_width' => 'required|numeric',
				'f_price' => 'required|numeric',
				'f_number' => 'required|numeric',
				'cid' => 'required|numeric',
				'fo_date' => 'required',
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
                // if amount is empty just add  order
                if(empty($order_pay)){
                    $rec_id = $db->insert('flex_orders', $modeldata);
                }else{
                    //if amount is not empty add order and customer payment
                    $db->startTransaction();
                    $rec_id = $db->insert('flex_orders', $modeldata);
                    $order_pay_array['order_id']=$rec_id;
                    $order_add_id = $db->insert('cust_payments', $order_pay_array);
                    if(!empty($rec_id) AND !empty($order_add_id)){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }

                }

				if(!empty($rec_id)){

					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("flex_orders");
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
		$this->view->render('flex_orders/add.php' ,null,'main_layout.php');
	}

    /**
     * add flex with cust_id
     */
	function flexwithid($custid=null)
    {
        if (empty($custid)) {
            redirect_to_page("customers/list");
        } else {
            if (is_post_request()) {

                $modeldata = transform_request_data($_POST);

                $rules_array = array(

                    'description' => 'required',
                    'material_type' => 'required',
                    'f_length' => 'required|numeric',
                    'f_width' => 'required|numeric',
                    'f_price' => 'required|numeric',
                    'f_number' => 'required|numeric',
                    'cid' => 'required|numeric',
                    'fo_date' => 'required',
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
                    $rec_id = $db->insert('flex_orders', $modeldata);

                    if (!empty($rec_id)) {

                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'), 'success');
                        redirect_to_page("customers/view/".$custid);
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
            $this->view->render('flex_orders/flexwithid.php', $custid, 'main_layout.php');

        }
    }
    /**
     * add flex with cust_id for different order
     */
	function flexwithid_d($custid=null)
    {
        if (empty($custid)) {
            redirect_to_page("flex_orders/cust_list_d/1");
        } else {
            if (is_post_request()) {
                //get payment for order
                $order_pay=$_POST['amount'];
                $order_pay_array=array();
                $order_pay_array['amount']=$order_pay;
                $order_pay_array['cust_date']=$_POST['fo_date'];
                $order_pay_array['description']="(فلکس) - ".$_POST['description'];
                $order_pay_array['cust_id']=$custid;
                $order_pay_array['currency']=$_POST['currency_r'];
                $order_pay_array['order_type']="flex";
                // remove amount value
                unset($_POST['amount']);
                unset($_POST['currency_r']);

                $modeldata = transform_request_data($_POST);

                $rules_array = array(

                    'description' => 'required',
                    'material_type' => 'required',
                    'f_length' => 'required|numeric',
                    'f_width' => 'required|numeric',
                    'f_price' => 'required|numeric',
                    'f_number' => 'required|numeric',
                    'cid' => 'required|numeric',
                    'fo_date' => 'required',
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

                    // if amount is empty just add  order
                    if(empty($order_pay)){
                        $rec_id = $db->insert('flex_orders', $modeldata);
                    }else{
                        //if amount is not empty add order and customer payment
                        $db->startTransaction();
                        $rec_id = $db->insert('flex_orders', $modeldata);
                        $order_pay_array['order_id']=$rec_id;
                        $order_add_id = $db->insert('cust_payments', $order_pay_array);
                        if(!empty($rec_id) AND !empty($order_add_id)){
                            $db->commit();
                        }else{
                            $db->rollback();
                        }
                    }
                    if (!empty($rec_id)) {

                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'), 'success');
                        redirect_to_page("flex_orders/cust_list_d/".$custid);
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
            $this->view->render('flex_orders/flexwithid_d.php', $custid, 'main_layout.php');

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
				
				'description' => 'required',
				'material_type' => 'required',
				'f_length' => 'required|numeric',
				'f_width' => 'required|numeric',
				'f_price' => 'required|numeric',
				'f_number' => 'required|numeric',
				'cid' => 'required|numeric',
				'fo_date' => 'required',
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
                $bool = $db->update('flex_orders',$modeldata);

				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("flex_orders/cust_list/".$_POST['cid']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','description','material_type','f_length','f_width','f_price','currency','f_number','cid','fo_date');
		$db->where('id' , $rec_id);
		$data['data'] = $db->getOne('flex_orders',$fields);


		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('flex_orders/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('flex_orders/edit.php' , $data , 'main_layout.php');
		}
	}

    /**
     * Edit Record Action of different orders
     * If Not $_POST Request, Display Edit Record Form View
     * @return View
     */
	function edit_d($rec_id=null){
		$db = $this->GetModel();
		if(is_post_request()){
            //get payment for order
            if(!empty($_POST['pay_id'])){
                $pay_id=$_POST['pay_id'];
            }

            $pay_amount=$_POST['amount'];
            $order_pay_array=array();
            $order_pay_array['amount']=$pay_amount;
            $order_pay_array['cust_date']=$_POST['fo_date'];
            $order_pay_array['description']="(فلکس) - ".$_POST['description'];
            $order_pay_array['cust_id']=$_POST['cid'];
            $order_pay_array['currency']=$_POST['currency_r'];

            // remove amount value
            unset($_POST['pay_id']);
		    unset($_POST['amount']);
		    unset($_POST['currency_r']);

			$modeldata = transform_request_data($_POST);

			$rules_array = array(

				'description' => 'required',
				'material_type' => 'required',
				'f_length' => 'required|numeric',
				'f_width' => 'required|numeric',
				'f_price' => 'required|numeric',
				'f_number' => 'required|numeric',
				'cid' => 'required|numeric',
				'fo_date' => 'required',
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
				if(empty($pay_id) AND !empty($pay_amount)){
                    $db->startTransaction();
                    $db->where('id' , $rec_id);
                    $bool = $db->update('flex_orders',$modeldata);

                    $order_pay_array['order_type']="flex";
                    $order_pay_array['order_id']=$rec_id;
                    $order_add_id = $db->insert('cust_payments', $order_pay_array);
                    if($bool AND !empty($order_add_id)){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }
                }else{
                    $db->startTransaction();
                    $db->where('id' , $rec_id);
                    $bool = $db->update('flex_orders',$modeldata);


                    $db->where('id' , $pay_id);
                    $bool_pay = $db->update('cust_payments',$order_pay_array);

                    if($bool AND $bool_pay){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }
                }

				if($bool){

					set_flash_msg(get_lang('edit_prompt_after_update'),'success');

					redirect_to_page("flex_orders/cust_list_d/".$_POST['cid']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','description','material_type','f_length','f_width','f_price','currency','f_number','cid','fo_date');
		$db->where('id' , $rec_id);
		$data['data'] = $db->getOne('flex_orders',$fields);

		//get order payment
        $fields_pay = array('id','amount','currency');
        $db->where("order_type = 'flex' AND order_id=$rec_id");
        $data['data_pay'] = $db->getOne('cust_payments',$fields_pay);

		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('flex_orders/edit_d.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}

			$this->view->render('flex_orders/edit_d.php' , $data , 'main_layout.php');
		}
	}


    /**
     * Delete Record Action
     * @return View
     */
    function del( $rec_ids = null ,$page=null,$cid=null){

        $db = $this->GetModel();

        $db->startTransaction();
        $db->where('id' , $rec_ids);
        $bool = $db->delete( 'flex_orders' );

        // if records exist delete it
        $db->where('order_id' , $rec_ids);
        $db->where('order_type' , "flex");
        $fields_pay = array('id');
        $records_pay = $db->get('cust_payments', null, $fields_pay);
        if(!empty($records_pay)){
            $db->where('order_id' , $rec_ids);
            $db->where('order_type' , "flex");
            $bool2 = $db->delete( 'cust_payments' );
            if($bool2 AND $bool){
                $db->commit();
                set_flash_msg(get_lang('prompt_record_deleted'),'success');
            }else{
                $db->rollback();
                if($db->getLastError()){
                    set_flash_msg($db->getLastError(),'danger');
                }
                else{
                    set_flash_msg(get_lang('prompt_error_deleting_record'),'danger');
                }
            }
        }else{
            if($bool){
                $db->commit();
                set_flash_msg(get_lang('prompt_record_deleted'),'success');
            }else{
                $db->rollback();
                if($db->getLastError()){
                    set_flash_msg($db->getLastError(),'danger');
                }
                else{
                    set_flash_msg(get_lang('prompt_error_deleting_record'),'danger');
                }
            }
        }

        if(isset($page)){
            redirect_to_page("flex_orders/$page/$cid");
        }else{
            redirect_to_page("flex_orders/list/");
        }

    }


    /**
     * Load Record Action
     * $arg2 Field Value
     * $param $arg1 string
     * @return View
     */
    function cust_list($cust_id){

        $db = $this->GetModel();

        $fields = array('id','description','material_type','f_length','f_width','f_price',
            'f_number', 'cid','fo_date','cust_name','total','currency','pay');

        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->where('cid',$cust_id);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or description LIKE ? or material_type LIKE ? 
            or f_length LIKE ? or f_width LIKE ? or f_price LIKE ? or f_number LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"));
            //$db->orWhere('po_date',"%$text%",'LIKE');
        }


        $db->orderBy("id","DESC");

        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('flex_orders_vu', $limit, $fields);


        $data = new stdClass;

        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        /* get total of order price and payments
        $fields2 = array('sum(total) as total','sum(pay) as pay','currency');
        $db->Where('cid',$cust_id);
        $db->groupBy("currency");
        $data->total_pay= $db->get('flex_orders_vu',null,$fields2);
        */
        $data->cust_id=$cust_id;

        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title =get_lang('flex_orders_list_title');
        $this->view->render('flex_orders/cust_list.php' , $data ,'main_layout.php');

    }

    function cust_list_d($cust_id){

        $db = $this->GetModel();

        $fields = array('id','description','material_type','f_length','f_width','f_price',
            'f_number', 'cid','fo_date','cust_name','total','currency','pay');

        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->where('cid',$cust_id);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or description LIKE ? or material_type LIKE ? 
            or f_length LIKE ? or f_width LIKE ? or f_price LIKE ? or f_number LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"));
            //$db->orWhere('po_date',"%$text%",'LIKE');
        }

        if(!empty($_GET['rem'])){
            $db->Where("rem",0,'>');
        }
        $db->orderBy("id","DESC");

        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('flex_orders_vu', $limit, $fields);

        $data = new stdClass;

        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        /* get total of order price and payments
        $fields2 = array('sum(total) as total','sum(pay) as pay','currency');
        $db->Where('cid',$cust_id);
        $db->groupBy("currency");
        $data->total_pay= $db->get('flex_orders_vu',null,$fields2);
        */
        $data->cust_id=$cust_id;

        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title =get_lang('flex_orders_list_title');
        $this->view->render('flex_orders/cust_list_d.php' , $data ,'main_layout.php');
    }



}
