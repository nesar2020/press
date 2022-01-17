
<?php
$comp_model = new SharedController;

//Page Data Information from Controller
$data = $this->view_data;

//$rec_id = $data['__tableprimarykey'];
$page_id = Router::$page_id; //Page id from url

$view_title = $this->view_title;

$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;

?>

<section class="page">
    
    <?php
    if( $show_header == true ){
    ?>
    
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            
            <div class="row ">
                
                <div class="col-12 comp-grid">
                    <h3 class="record-title"><?php print_lang('btn_view'); ?></h3>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <?php
    }
    ?>
    
    <div  class="">
        <div class="container">
            
            <div class="row ">
                
                <div class="col-md-12 comp-grid">
                    
                    <div  class="card animated fadeIn">
                        <?php 
                        $this :: display_page_errors(); 
                        
                        if(!empty($data)){
                        ?>
                        <div class="page-records ">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody>
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_cpayments_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['cpayments']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_flex_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['flex']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_ctp_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['ctp']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_paper_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['paper']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_expend_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['expend']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_supp_pay_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['supp_pay']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_supp_pur_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['supp_pur']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_emp_pay_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['emp_pay']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('all_calc_list_emp_sal_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['emp_sal']; ?> </td>
                                    </tr>
                                    
                                    
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3">
                            
                            
                            
                            
                            <button class="btn btn-sm btn-primary export-btn">
                                <i class="material-icons">save</i> <?php print_lang('list_export_btn_text'); ?>
                            </button>
                            
                            
                        </div>
                        <?php
                        }
                        else{
                        ?>
                        <!-- Empty Record Message -->
                        <div class="text-muted panel-body">
                            <h3><i class="material-icons">block</i> <?php print_lang('txt_page_record_not_found'); ?></h3>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    
</section>
