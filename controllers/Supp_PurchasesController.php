<?php 

/**
 * Supp_Purchases Page Controller
 * @category  Controller
 */
class Supp_PurchasesController extends SecureController{
	
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
		
		$fields = array('id', 	'amount', 	'bill_no', 	'amount_num','price','currency','material_type','s_id','office_name');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('amount',"%$text%",'LIKE');
			$db->orWhere('bill_no',"%$text%",'LIKE');
			$db->orWhere('amount_num',"%$text%",'LIKE');
			$db->orWhere('price',"%$text%",'LIKE');
			$db->orWhere('material_type',"%$text%",'LIKE');
			$db->orWhere('s_id',"%$text%",'LIKE');
			$db->orWhere('office_name',"%$text%",'LIKE');

		}

        $db->orderBy("id","DESC");

		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('supp_purchases_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('supp_purchases_list_title');
		$this->view->render('supp_purchases/list.php' , $data ,'main_layout.php');
		
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
						$options = array('table' => 'supp_purchases', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'supp_purchases' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'supp_purchases/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 	'amount', 	'bill_no', 	'amount_num', 	'price', 	'material_type', 	's_id' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'supp_purchases', $fields );

		if(!empty($record)){
			
			
			
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('supp_purchases/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('supp_purchases/view.php' , $record , 'main_layout.php');
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
            $order_pay=$_POST['amount_pay'];
            $order_date=$_POST['sup_date'];
            $order_pay_array=array();
            $order_pay_array['amount']=$order_pay;
            $order_pay_array['sup_date']=$order_date;
            $order_pay_array['description']="خریداری - ".$_POST['material_type'];
            $order_pay_array['s_id']=$_POST['s_id'];;
            // remove amount value
            unset($_POST['amount_pay']);
            unset($_POST['sup_date']);

			$modeldata = transform_request_data($_POST);

			$rules_array = array(
				
				'amount' => 'required',
				'bill_no' => 'required',
				'amount_num' => 'required|numeric',
				'price' => 'required|numeric',
				'material_type' => 'required',
				's_id' => 'required|numeric',
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
                    $rec_id = $db->insert('supp_purchases', $modeldata);
                }else{
                    //if amount is not empty add order and customer payment
                    $db->startTransaction();
                    $rec_id = $db->insert('supp_purchases', $modeldata);
                    $order_pay_array['pur_id']=$rec_id;
                    $order_add_id = $db->insert('supp_payments', $order_pay_array);
                    if(!empty($rec_id) AND !empty($order_add_id)){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }

                }

				if(!empty($rec_id)){
					
					
					set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
					redirect_to_page("supp_purchases");
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
		$this->view->render('supp_purchases/add.php' ,null,'main_layout.php');
	}

    /**
     * Add new supplier purchase
     */
	function purchasewithid($custid,$page=null)
    {
        if (empty($custid)) {
            redirect_to_page("suppliers/list");
        } else {
            if (is_post_request()) {
                //get payment for order
                $order_pay=$_POST['amount_pay'];
                $order_date=$_POST['sup_date'];
                $order_pay_array=array();
                $order_pay_array['amount']=$order_pay;
                $order_pay_array['sup_date']=$order_date;
                $order_pay_array['description']="خریداری - ".$_POST['material_type'];
                $order_pay_array['s_id']=$custid;
                $order_pay_array['currency']=$_POST['currency_pay'];
                // remove amount value
                unset($_POST['amount_pay']);
                unset($_POST['sup_date']);
                unset($_POST['currency_pay']);


                $modeldata = transform_request_data($_POST);


                $rules_array = array(

                    'amount' => 'required',
                    'bill_no' => 'required',
                    'amount_num' => 'required|numeric',
                    'price' => 'required|numeric',
                    'material_type' => 'required',
                    's_id' => 'required|numeric',
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
                        $rec_id = $db->insert('supp_purchases', $modeldata);
                    }else{
                        //if amount is not empty add order and customer payment
                        $db->startTransaction();
                        $rec_id = $db->insert('supp_purchases', $modeldata);
                        $order_pay_array['pur_id']=$rec_id;
                        $order_add_id = $db->insert('supp_payments', $order_pay_array);
                        if(!empty($rec_id) AND !empty($order_add_id)){
                            $db->commit();
                        }else{
                            $db->rollback();
                        }

                    }

                    if (!empty($rec_id)) {


                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'), 'success');
                        if(isset($page)){
                            redirect_to_page("supp_purchases/$page/$custid");
                        }else{
                            redirect_to_page("supp_purchases");
                        }
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
            $this->view->render('supp_purchases/purchasewithid.php', $custid, 'main_layout.php');
        }
    }


	/**
     * Edit Record Action 
     * If Not $_POST Request, Display Edit Record Form View
     * @return View
     */
	function edit($rec_id=null,$supp_id=null){
		$db = $this->GetModel();
		if(is_post_request()){
            //get payment for order
            if(!empty($_POST['pay_id'])){
                $pay_id=$_POST['pay_id'];
            }

            $pay_amount=$_POST['amount_pay'];
            $order_date=$_POST['sup_date'];
            $order_pay_array=array();
            $order_pay_array['amount']=$pay_amount;
            $order_pay_array['sup_date']=$order_date;
            $order_pay_array['description']="خریداری - ".$_POST['material_type'];
            $order_pay_array['s_id']=$_POST['s_id'];
            $order_pay_array['currency']=$_POST['currency_pay'];


            // remove amount value
            unset($_POST['pay_id']);
            unset($_POST['sup_date']);
            unset($_POST['amount_pay']);
            unset($_POST['currency_pay']);


			$modeldata = transform_request_data($_POST);
			
			$rules_array = array(
				
				'amount' => 'required',
				'bill_no' => 'required',
				'amount_num' => 'required|numeric',
				'price' => 'required|numeric',
				'material_type' => 'required',
				's_id' => 'required|numeric',
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

                if(empty($pay_id)AND !empty($pay_amount)){
                    $db->startTransaction();
                    $db->where('id' , $rec_id);
                    $bool = $db->update('supp_purchases',$modeldata);

                    $order_pay_array['pur_id']=$rec_id;
                    $order_add_id = $db->insert('supp_payments', $order_pay_array);
                    if($bool AND !empty($order_add_id)){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }
                }else{
                    $db->startTransaction();
                    $db->where('id' , $rec_id);
                    $bool = $db->update('supp_purchases',$modeldata);


                    $db->where('id' , $pay_id);
                    $bool_pay = $db->update('supp_payments',$order_pay_array);

                    if($bool AND $bool_pay){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }
                }

				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
                    if(isset($supp_id)){
                        redirect_to_page("supp_purchases/supp_list/$supp_id");
                    }else{
                        redirect_to_page("supp_purchases");
                    }
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','amount','bill_no','amount_num','sp_date','price','currency','material_type','s_id');
		$db->where('id' , $rec_id);
		$data['data'] = $db->getOne('supp_purchases',$fields);

        //get order payment
        $fields_pay = array('id','amount','currency','sup_date');
        $db->where("pur_id=$rec_id");
        $data['data_pay'] = $db->getOne('supp_payments',$fields_pay);
		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('supp_purchases/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('supp_purchases/edit.php' , $data , 'main_layout.php');
		}
	}

    function supp_list($supp_id=null){

        $db = $this->GetModel();

        $fields = array('id', 	'amount', 	'bill_no', 	'amount_num','sp_date','price','currency',
            'material_type','s_id','office_name');

        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        if(!empty($this->search)){
            $text = $this->search;

            $db->orWhere('id',"%$text%",'LIKE');
            $db->orWhere('amount',"%$text%",'LIKE');
            $db->orWhere('bill_no',"%$text%",'LIKE');
            $db->orWhere('amount_num',"%$text%",'LIKE');
            $db->orWhere('price',"%$text%",'LIKE');
            $db->orWhere('material_type',"%$text%",'LIKE');
            $db->orWhere('s_id',"%$text%",'LIKE');
            $db->orWhere('office_name',"%$text%",'LIKE');

        }
        $db->where('s_id' , $supp_id);
        $db->orderBy("id","DESC");

        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('supp_purchases_vu', $limit, $fields);

        $data = new stdClass;
        $data->supp_id = $supp_id;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);



        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title =get_lang('supp_purchases_list_title');
        $this->view->render('supp_purchases/supp_list.php' , $data ,'main_layout.php');

    }



}
