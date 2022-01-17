<?php 

/**
 * Ctp_Orders Page Controller
 * @category  Controller
 */
class Ctp_OrdersController extends SecureController{
	
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
		
		$fields = array('id','description','date','plate_type','plate_no','plate_price',
            'currency','u_id','cust_name','total','pay');
		
		$limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
		
		if(!empty($this->search)){
			$text = $this->search;
			
			$db->orWhere('id',"%$text%",'LIKE');
			$db->orWhere('description',"%$text%",'LIKE');
			//$db->orWhere('date',"%$text%",'LIKE');
			$db->orWhere('plate_type',"%$text%",'LIKE');
			$db->orWhere('plate_no',"%$text%",'LIKE');
			$db->orWhere('plate_price',"%$text%",'LIKE');
			$db->orWhere('u_id',"%$text%",'LIKE');
			$db->orWhere('cust_name',"%$text%",'LIKE');
        }


			$db->orderBy("id","DESC");
		
		
		
		
		if( !empty($fieldname) ){
			$db->where($fieldname , $fieldvalue);
		}
		//page filter command
		$tc = $db->withTotalCount();
		$records = $db->get('ctp_orders_vu', $limit, $fields);
		
		$data = new stdClass;

		$data->records = $records;
		$data->record_count = count($records);
		$data->total_records = intval($tc->totalCount);
		
		
		
		if($db->getLastError()){
			$this->view->page_error = $db->getLastError();
		}
		$this->view->page_title =get_lang('ctp_orders_list_title');
		$this->view->render('ctp_orders/list.php' , $data ,'main_layout.php');
		
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
						$options = array('table' => 'ctp_orders', 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData( $file_path , $options , false );
					}
					else{
						$data = $db->loadJsonData( $file_path, 'ctp_orders' , false );
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
		
		$list_page = (!empty($_POST['redirect']) ? $_POST['redirect'] : 'ctp_orders/list');
		redirect_to_page($list_page);
	}
	

	


	/**
     * View Record Action 
     * @return View
     */
	function view( $rec_id = null , $value = null){
	
		$db = $this->GetModel();
		
		
		$fields = array( 'id', 	'description', 	'date', 	'plate_type', 	'plate_no', 	'plate_price', 	'u_id' );
		
		
		if( !empty($value) ){
			$db->where($rec_id, urldecode($value));
		}
		else{
			$db->where('id' , $rec_id);
		}
		
		  
		$record = $db->getOne( 'ctp_orders', $fields );

		if(!empty($record)){
			
			
			
			$this->view->page_title =get_lang('btn_view');
			$this->view->render('ctp_orders/view.php' , $record ,'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error = $db->getLastError();
			}
			else{
				$this->view->page_error = get_lang('prompt_record_not_found');
			}
			$this->view->render('ctp_orders/view.php' , $record , 'main_layout.php');
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
            $order_pay_array['cust_date']=$_POST['date'];
            $order_pay_array['description']="(ctp) - ".$_POST['description'];
            $order_pay_array['cust_id']=$_POST['u_id'];;
            $order_pay_array['order_type']="ctp";
            // remove amount value
            unset($_POST['amount']);

			$modeldata = transform_request_data($_POST);
			

			$rules_array = array(
				
				'description' => 'required',
				'date' => 'required',
				'plate_type' => 'required',
				'plate_no' => 'required|numeric',
				'plate_price' => 'required|numeric',
				'u_id' => 'required|numeric',
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
                    $rec_id = $db->insert('ctp_orders', $modeldata);
                }else{
                    //if amount is not empty add order and customer payment
                    $db->startTransaction();
                    $rec_id = $db->insert('ctp_orders', $modeldata);
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
					redirect_to_page("ctp_orders");
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
		$this->view->render('ctp_orders/add.php' ,null,'main_layout.php');
	}

    /**
     * add ctp order based on customer id
     */
	function ctpwithid($custid=null){
	    if(empty($custid)){
	        redirect_to_page("customers/list");
        }else{
            if(is_post_request()){

                $modeldata = transform_request_data($_POST);

                $rules_array = array(

                    'description' => 'required',
                    'date' => 'required',
                    'plate_type' => 'required',
                    'plate_no' => 'required|numeric',
                    'plate_price' => 'required|numeric',
                    'u_id' => 'required|numeric',
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
                    $rec_id = $db->insert('ctp_orders', $modeldata);

                    if(!empty($rec_id)){
                        set_flash_msg(get_lang('ctp_orders_add_prompt_after_add'),'success');
                        redirect_to_page("customers/view/".$custid);
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
            $this->view->render('ctp_orders/ctpwithid.php' ,$custid,'main_layout.php');
        }

	}

	/**
     * add ctp order based on customer id for different order
     */
	function ctpwithid_d($custid=null){
	    if(empty($custid)){
	        redirect_to_page("customers/list_d");
        }else{
            if(is_post_request()){
                //get payment for order
                $order_pay=$_POST['amount'];
                $order_pay_array=array();
                $order_pay_array['amount']=$order_pay;
                $order_pay_array['cust_date']=$_POST['date'];
                $order_pay_array['description']="(ctp) - ".$_POST['description'];
                $order_pay_array['cust_id']=$custid;
                $order_pay_array['currency']=$_POST['currency_r'];
                $order_pay_array['order_type']="ctp";
                // remove amount value
                unset($_POST['amount']);
                unset($_POST['currency_r']);

                $modeldata = transform_request_data($_POST);

                $rules_array = array(

                    'description' => 'required',
                    'date' => 'required',
                    'plate_type' => 'required',
                    'plate_no' => 'required|numeric',
                    'plate_price' => 'required|numeric',
                    'u_id' => 'required|numeric',
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
                        $rec_id = $db->insert('ctp_orders', $modeldata);
                    }else{
                        //if amount is not empty add order and customer payment
                        $db->startTransaction();
                        $rec_id = $db->insert('ctp_orders', $modeldata);
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
                        redirect_to_page("ctp_orders/cust_list_d/".$custid);
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
            $this->view->render('ctp_orders/ctpwithid_d.php' ,$custid,'main_layout.php');
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
				'date' => 'required',
				'plate_type' => 'required',
				'plate_no' => 'required|numeric',
				'plate_price' => 'required|numeric',
				'u_id' => 'required|numeric',
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
                    $bool = $db->update('ctp_orders',$modeldata);

				if($bool){
					
					set_flash_msg(get_lang('edit_prompt_after_update'),'success');
					
					redirect_to_page("ctp_orders/cust_list/".$_POST['u_id']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','description','date','plate_type','plate_no','plate_price','currency','u_id');
		$db->where('id' , $rec_id);
		$data['data'] = $db->getOne('ctp_orders',$fields);


		
		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('ctp_orders/edit.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}
			
			$this->view->render('ctp_orders/edit.php' , $data , 'main_layout.php');
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
            $order_pay_array['cust_date']=$_POST['date'];
            $order_pay_array['description']="(ctp) - ".$_POST['description'];
            $order_pay_array['cust_id']=$_POST['u_id'];
            $order_pay_array['currency']=$_POST['currency_r'];


            // remove amount value
            unset($_POST['pay_id']);
            unset($_POST['amount']);
            unset($_POST['currency_r']);


			$modeldata = transform_request_data($_POST);

			$rules_array = array(

				'description' => 'required',
				'date' => 'required',
				'plate_type' => 'required',
				'plate_no' => 'required|numeric',
				'plate_price' => 'required|numeric',
				'u_id' => 'required|numeric',
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
                    $bool = $db->update('ctp_orders',$modeldata);

                    $order_pay_array['order_type']="ctp";
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
                    $bool = $db->update('ctp_orders',$modeldata);


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

					redirect_to_page("ctp_orders/cust_list_d/".$_POST['u_id']);
					return;
				}
				else{
					$this->view->page_error[] = $db->getLastError();
				}
			}
		}

		$fields = array('id','description','date','plate_type','plate_no','plate_price','currency','u_id');
		$db->where('id' , $rec_id);
		$data['data'] = $db->getOne('ctp_orders',$fields);

        //get order payment
        $fields_pay = array('id','amount','currency');
        $db->where("order_type = 'ctp' AND order_id=$rec_id");
        $data['data_pay'] = $db->getOne('cust_payments',$fields_pay);

		$this->view->page_title =get_lang('btn_edit');
		if(!empty($data)){
			$this->view->render('ctp_orders/edit_d.php' , $data, 'main_layout.php');
		}
		else{
			if($db->getLastError()){
				$this->view->page_error[] = $db->getLastError();
			}
			else{
				$this->view->page_error[] = get_lang('prompt_record_not_found');
			}

			$this->view->render('ctp_orders/edit_d.php' , $data , 'main_layout.php');
		}
	}


    /**
     * Delete Record Action
     * @return View
     */
    function del( $rec_ids = null,$page=null,$cid=null ){

        $db = $this->GetModel();

        $db->startTransaction();
        $db->where('id' , $rec_ids);
        $bool = $db->delete( 'ctp_orders' );

        // if records exist delete it
        $db->where('order_id' , $rec_ids);
        $db->where('order_type' , "ctp");
        $fields_pay = array('id');
        $records_pay = $db->get('cust_payments', null, $fields_pay);
        if(!empty($records_pay)){
            $db->where('order_id' , $rec_ids);
            $db->where('order_type' , "ctp");
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
            redirect_to_page("ctp_orders/$page/$cid");
        }else{
            redirect_to_page("ctp_orders/list/");
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

        $fields = array('id','description','date','plate_type','plate_no',
            'plate_price','currency','u_id','cust_name','total','pay');

        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->Where('u_id',$cust_id);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or description LIKE ? or plate_type LIKE ? 
            or plate_no LIKE ? or plate_price LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%"));
            //$db->orWhere('po_date',"%$text%",'LIKE');
        }


        $db->orderBy("id","DESC");

        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('ctp_orders_vu', $limit, $fields);

        $data = new stdClass;

        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        $data->cust_id=$cust_id;

        /* get total of order price and payments
        $fields2 = array('sum(total) as total','sum(pay) as pay','currency');
        $db->Where('u_id',$cust_id);
        $db->groupBy("currency");
        $data->total_pay= $db->get('ctp_orders_vu',null,$fields2);
        */

        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title =get_lang('ctp_orders_list_title');
        $this->view->render('ctp_orders/cust_list.php' , $data ,'main_layout.php');

    }

    function cust_list_d($cust_id){

        $db = $this->GetModel();

        $fields = array('id','description','date','plate_type','plate_no',
            'plate_price','currency','u_id','cust_name','total','pay');

        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)

        $db->Where('u_id',$cust_id);
        if(!empty($this->search)){
            $text = $this->search;
            $db->where ("(id LIKE ? or description LIKE ? or plate_type LIKE ? 
            or plate_no LIKE ? or plate_price LIKE ? )",
                Array("%$text%","%$text%","%$text%","%$text%","%$text%"));
            //$db->orWhere('po_date',"%$text%",'LIKE');
        }

        if(!empty($_GET['rem'])){
            $db->Where("rem",0,'>');
        }

        $db->orderBy("id","DESC");

        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('ctp_orders_vu', $limit, $fields);

        $data = new stdClass;

        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        $data->cust_id=$cust_id;

        /* get total of order price and payments
        $fields2 = array('sum(total) as total','sum(pay) as pay','currency');
        $db->Where('u_id',$cust_id);
        $db->groupBy("currency");
        $data->total_pay= $db->get('ctp_orders_vu',null,$fields2);
        */

        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title =get_lang('ctp_orders_list_title');
        $this->view->render('ctp_orders/cust_list_d.php' , $data ,'main_layout.php');

    }


}
