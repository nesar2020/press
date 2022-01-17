
<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;

$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = Router :: $field_name;
$field_value = Router :: $field_value;

$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;

?>

<section class="page">
    
    <?php
    if( $show_header == true ){
    ?>
    
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            
            <div class="row ">
                
                <div class="col-sm-4 comp-grid">
                    <h3 class="record-title"><?php print_lang('customers_list_title'); ?></h3>
                    
                </div>
                
                <div class="col-sm-3 comp-grid">
                    
                    <a  class="btn btn btn-primary btn-block" href="<?php print_link("customers/add") ?>">
                        <i class="material-icons">add</i>                               
                        <?php print_lang('txt_add_page_title'); ?> 
                    </a>
                    
                </div>
                
                <div class="col-sm-5 comp-grid">
                    
                    <form  class="search" method="get">
                        <div class="input-group">
                            <input value="<?php echo get_query_str_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="<?php print_lang('txt_search'); ?>" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                    
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <?php
                            if(!empty($field_name)){
                            ?>
                            <hr class='sm d-block d-sm-none' />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link('customers') ?>"><?php echo $field_name ?></a></li>
                                    <li  class="breadcrumb-item active text-capitalize"><?php echo urldecode($field_value) ?></li>
                                </ul>
                            </nav>
                            <?php 
                            }
                            elseif(!empty($_GET['search'])){
                            ?>
                            <hr class='sm d-block d-sm-none' />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <li class="breadcrumb-item">
                                        <a class="text-capitalize" href="<?php print_link('customers') ?>">&laquo; <?php print_lang('link_back'); ?></a>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize"><?php print_lang('txt_search'); ?> <strong><?php echo $_GET['search']; ?></strong></li>
                                </ul>
                            </nav>
                            <?php
                            }
                            ?>
                        </div>
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
                            <div id="customers-list-records">
                                <?php $this :: display_page_errors(); ?>
                                
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                    <style>
                                        .tdwidth{
                                            width: 155px !important;
                                            word-break: break-all;
                                        }
                                        .tdsm{
                                            width: 125px !important;
                                            word-break: break-all;
                                        }

                                    </style>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                <!--
                                                <th class="td-sno td-checkbox"><input class="toggle-check-all" type="checkbox" /></th>


                                                <th class="td-sno">#</th>
                                                <th > <?php //print_lang('ctp_orders_list_id_title'); ?></th>
                                                -->
                                                <th > <?php print_lang('customers_list_cust_name_title'); ?></th>
                                                <th > <?php print_lang('customers_list_office_name_title'); ?></th>
                                                <th > <?php print_lang('customers_list_phone_title'); ?></th>
                                                <th > <?php print_lang('customers_list_email_title'); ?></th>
                                                <th > <?php print_lang('customers_list_address_title'); ?></th>
                                                
                                                <th class="td-btn"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                            $counter = 0;
                                            foreach($records as $data){
                                            $counter++;
                                            ?>
                                            <tr>
                                                    <!--
                                                    <th class=" td-checkbox">

                                                    <label>
                                                        <input class="optioncheck" name="optioncheck[]" value="<?php echo $data['id'] ?>" type="checkbox" />
                                                        </label>
                                                    </th>
                                                    
                                                    <th class="td-sno"><?php echo $counter; ?></th>
                                                    <td><a href="<?php print_link('customers/view/' . $data['id']) ?>"><?php echo $data['id']; ?></a></td>
                                                    -->
                                                    <td> <?php echo $data['cust_name']; ?> </td>
                                                    <td> <?php echo $data['office_name']; ?> </td>
                                                    <td class="tdsm"> <?php echo $data['phone']; ?> </td>
                                                    <td class="tdwidth"> <?php echo $data['email']; ?> </td>
                                                    <td class="tdwidth"> <?php echo $data['address']; ?> </td>
                                                    
                                                    
                                                    <th class="td-btn">

                                                        <a class="btn btn-sm btn-primary has-tooltip"  href="<?php print_link('cust_payments/paywithcid/'.$data['id']); ?>">
                                                            رسید
                                                        </a>
                                                        <a class="btn btn-sm btn-primary has-tooltip"  href="<?php print_link('ctp_orders/ctpwithid/'.$data['id']); ?>">
                                                            CTP
                                                        </a>
                                                        <a class="btn btn-sm btn-primary has-tooltip" href="<?php print_link('flex_orders/flexwithid/'.$data['id']); ?>">
                                                            دیجیتل
                                                        </a>
                                                        <a class="btn btn-sm btn-primary has-tooltip"  href="<?php print_link('paper_orders/paperwithid/'.$data['id']); ?>">
                                                            کاغذ
                                                        </a>

                                                        <a class="btn btn-sm btn-outline-primary"  href="<?php print_link("ctp_orders/cust_list/".$data['id']); ?>">
                                                            <i class="material-icons"></i>CTP
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-primary"  href="<?php print_link("flex_orders/cust_list/".$data['id']); ?>">
                                                            <i class="material-icons"></i>دیجیتل
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-primary"  href="<?php print_link("paper_orders/cust_list/".$data['id']); ?>">
                                                            <i class="material-icons"></i>کاغذی
                                                        </a>

                                                        <a class="btn btn-sm btn-outline-primary"  href="<?php print_link("cust_payments/cust_list/".$data['id']); ?>">
                                                            <i class="material-icons"></i>رسیدات
                                                        </a>

                                                        <a class="btn btn-sm btn-success has-tooltip"  href="<?php print_link('customers/view/'.$data['id']); ?>">
                                                            <?php echo "بیلانس" ?>
                                                        </a>
                                                        
                                                        
                                                        <a class="btn btn-sm btn-info has-tooltip"  href="<?php print_link('customers/edit/'.$data['id']); ?>">
                                                            <i class="material-icons">edit</i> <?php //print_lang('btn_edit'); ?>
                                                        </a>
                                                        
                                                        
                                                        
                                                    </th>
                                                </tr>
                                                <?php 
                                                }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                    if( $show_footer == true ){
                                    ?>
                                    <div class="card-footer">
                                        <div class="">  
                                            
                                            <button data-prompt-msg="Are you sure you want to delete these records" data-url="<?php print_link("customers/delete/{sel_ids}"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                <i class="material-icons">clear</i> <?php print_lang('btn_delete_selected'); ?>
                                            </button>
                                            
                                            
                                            <button class="btn btn-sm btn-primary export-btn"><i class="material-icons">save</i> <?php print_lang('list_export_btn_text'); ?></button>
                                            
                                            
                                            <?php Html :: import_form('customers/import_data' , get_lang('list_import_btn_text'), 'CSV , JSON'); ?>
                                            
                                        </div>
                                        
                                        <?php
                                        if( $show_pagination == true ){
                                        $pager = new Pagination($total_records,$record_count);
                                        $pager->page_name='customers';
                                        $pager->show_page_count=true;
                                        $pager->show_record_count=true;
                                        $pager->show_page_limit=true;
                                        $pager->show_page_number_list=true;
                                        $pager->pager_link_range=5;
                                        
                                        $pager->render();
                                        }
                                        ?>
                                        
                                    </div>
                                    <?php
                                    }
                                    }
                                    else{
                                    ?>
                                    <div class="text-muted animated bounce">
                                        <h4><i class="material-icons">block</i> <?php print_lang('ctp_orders_list_empty_record_prompt'); ?></h4>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </section>
        