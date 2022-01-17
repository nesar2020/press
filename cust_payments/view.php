
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
                                        <th class="title"> <?php print_lang('emp_payments_list_amount_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['amount']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('cust_payments_list_cust_date_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['cust_date']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('ctp_orders_view_description_label'); ?> :</th>
                                        <td class="value"> <?php echo $data['description']; ?> </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                        <th class="title"> <?php print_lang('cust_payments_list_cust_id_title'); ?> :</th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("customers/View/id/$data[cust_id]") ?>">
                                                <?php echo $data['cust_id']; ?>  <i class="material-icons">visibility</i>
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3">
                            
                            
                            <a class="btn btn-sm btn-info"  href="<?php print_link("cust_payments/edit/$data[id]"); ?>">
                                <i class="material-icons">edit</i> <?php print_lang('btn_edit'); ?>
                            </a>
                            
                            
                            
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
