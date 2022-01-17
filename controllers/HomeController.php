<?php 

/**
 * Home Page Controller
 * @category  Controller
 */
class HomeController extends SecureController{
	/**
     * Index Action
     * @return View
     */
	function index(){
        $db = $this->GetModel();
        $startdate ="";
        $enddate = "";
        $c_records=array();
        $f_records=array();
        $p_records=array();
        $pay_records=array();

        if(!empty($_GET['e_date'])){
            $vals = explode('to' , str_replace(' ' , '' , $_GET['e_date']));
            $startdate = $vals[0];
            $enddate = $vals[1];
            $limit="";
        }
        //get flex orders
        if(!empty($_GET['e_date']) AND isset($_GET['flex'])){
            $db->where("flex_orders_vu.fo_date BETWEEN '$startdate' AND '$enddate'");
            $ffields = array('description', 	'fo_date', 	'material_type', 	'f_length', 	'f_width', 	'f_price',
                'f_number', 	'sq_meter', 	'pp_item', 	'total', 'currency',	'cid','cust_name');
            $db->orderBy("fo_date","DESC");
            $f_records = $db->get('flex_orders_vu',$limit,$ffields);
        }


        //get ctp orders
        if(!empty($_GET['e_date'])AND isset($_GET['ctp'])){
            $db->where("ctp_orders_vu.date BETWEEN '$startdate' AND '$enddate'");
            $cfields = array('description','date','plate_type','plate_no','plate_price','total','currency','u_id','cust_name');
            $db->orderBy("date","DESC");
            $c_records = $db->get('ctp_orders_vu',$limit,$cfields);
        }


        //get paper orders
        if(!empty($_GET['e_date'])AND isset($_GET['paper'])){
            $db->where("paper_orders_vu.po_date BETWEEN '$startdate' AND '$enddate'");
            $pfields = array('description','po_date','plate_type','plate_no','plate_price','total', 'currency','cid','cust_name');
            $db->orderBy("po_date","DESC");
            $p_records = $db->get('paper_orders_vu',$limit,$pfields);
        }
        //get paper orders
        if(!empty($_GET['e_date'])AND isset($_GET['pay'])){
            $db->where("cust_date BETWEEN '$startdate' AND '$enddate'");
            $payfields = array('id', 'amount', 'currency', 'cust_date', 'description', 'cust_id', 'cust_name');
            $db->orderBy("cust_date","DESC");
            $pay_records = $db->get('cust_payments_vu',$limit,$payfields);
        }


        $data = new stdClass;
        $data->f_records = $f_records;
        $data->p_records = $p_records;
        $data->c_records = $c_records;
        $data->pay_records = $pay_records;

        if($db->getLastError()){
            $this->view->page_error = $db->getLastError();
        }

		$this->view->render("home/index.php" , $data , "main_layout.php");

	}

	function backup(){
        /*
		$db = new DBBackup();
        $db->init(array(
            'driver' => DB_TYPE,
            'host' => DB_HOST,
            'user' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'database' => DB_NAME
        ));
        $backup = $db->backup();
        $data = array();
        if(!$backup['error']){
            // If there isn't errors, show the content
            // The backup will be at $var['msg']
            // You can do everything you want to. Like save in a file.
            $file_name='assets/backup/backup'.date('Y_m_d_his').'.sql';
            $fp = fopen($file_name, 'a+');
            fwrite($fp, $backup['msg']);
            fclose($fp);
            $data['file']=SITE_ADDR.$file_name;
            $this->view->render('home/backup.php' , $data , 'main_layout.php');
        } else {
            $data['error']='An error has ocurred.';
            $this->view->render('home/backup.php' , $data , 'main_layout.php');
        }
		*/
		
		$this->view->render('home/backup.php' , null , 'main_layout.php');
    }
}
