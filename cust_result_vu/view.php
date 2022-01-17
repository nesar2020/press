
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
                                        <th class="title"> <?php print_lang('ctp_orders_list_id_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['id']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('customers_view_cust_name_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['cust_name']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('customers_view_office_name_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['office_name']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('customers_view_phone_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['phone']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('customers_view_email_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['email']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('customers_view_address_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['address']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('ctp_orders_vu_view_total_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['total']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('cust_result_vu_view_pay_total_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['pay_total']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('cust_result_vu_view_rtotal_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['rtotal']; ?> </td>
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
