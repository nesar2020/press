<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * ctp_orders_u_id_option_list Model Action
     * @return array
     */
	function ctp_orders_u_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , cust_name AS label FROM customers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}
        /**
         * list currencies
         * @return array
         */
        function list_currencies(){
            $db = $this->GetModel();
            $sqltextc = "SELECT DISTINCT id AS value , currency AS label FROM currencies ORDER BY value ASC";
            $arrc = $db->rawQuery($sqltextc);
            return $arrc;
        }

	/**
     * emp_payments_emp_id_option_list Model Action
     * @return array
     */
	function emp_payments_emp_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , emp_name AS label 
FROM employees 
Where status=1 
ORDER BY label ASC 
";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * emp_salaries_emp_id_option_list Model Action
     * @return array
     */
	function emp_salaries_emp_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , emp_name AS label FROM employees Where status=1 ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * flex_orders_cid_option_list Model Action
     * @return array
     */
	function flex_orders_cid_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , cust_name AS label FROM customers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * paper_orders_cid_option_list Model Action
     * @return array
     */
	function paper_orders_cid_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , cust_name AS label FROM customers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * supp_purchases_s_id_option_list Model Action
     * @return array
     */
	function supp_purchases_s_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , office_name AS label FROM suppliers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * cust_payments_cust_id_option_list Model Action
     * @return array
     */
	function cust_payments_cust_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , cust_name AS label FROM customers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

	/**
     * supp_payments_s_id_option_list Model Action
     * @return array
     */
	function supp_payments_s_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT id AS value , office_name AS label FROM suppliers ORDER BY label ASC";
		$arr = $db->rawQuery($sqltext);
		return $arr;
	}

}
