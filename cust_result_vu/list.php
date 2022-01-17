
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
                    <h3 class="record-title"><?php print_lang('cust_result_vu_list_title'); ?></h3>
                    
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
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link('cust_result_vu') ?>"><?php echo $field_name ?></a></li>
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
                                        <a class="text-capitalize" href="<?php print_link('cust_result_vu') ?>">&laquo; <?php print_lang('link_back'); ?></a>
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
                            <div id="cust_result_vu-list-records">
                                <?php $this :: display_page_errors(); ?>
                                
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                
                                                <th class="td-sno">#</th>
                                                <th > <?php print_lang('customers_list_cust_name_title'); ?></th>
                                                <th > <?php print_lang('customers_list_office_name_title'); ?></th>
                                                <th > <?php print_lang('customers_list_phone_title'); ?></th>
                                                <th > <?php print_lang('customers_list_email_title'); ?></th>
                                                <th > <?php print_lang('customers_list_address_title'); ?></th>
                                                <th > <?php print_lang('cust_result_vu_list_total_title'); ?></th>
                                                <th > <?php print_lang('cust_result_vu_list_pay_total_title'); ?></th>
                                                <th > <?php print_lang('cust_result_vu_list_rtotal_title'); ?></th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                            $counter = 0;
                                            foreach($records as $data){
                                            $counter++;
                                            ?>
                                            <tr>
                                                
                                                <th class="td-sno"><?php echo $counter; ?></th>
                                                <td> <?php echo $data['cust_name']; ?> </td>
                                                <td> <?php echo $data['office_name']; ?> </td>
                                                <td> <?php echo $data['phone']; ?> </td>
                                                <td> <?php echo $data['email']; ?> </td>
                                                <td> <?php echo $data['address']; ?> </td>
                                                <td> <?php echo $data['total']; ?> </td>
                                                <td> <?php echo $data['pay_total']; ?> </td>
                                                <td> <?php echo $data['rtotal']; ?> </td>
                                                
                                                
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
                                        
                                        
                                        <button class="btn btn-sm btn-primary export-btn"><i class="material-icons">save</i> <?php print_lang('list_export_btn_text'); ?></button>
                                        
                                        
                                    </div>
                                    
                                    <?php
                                    if( $show_pagination == true ){
                                    $pager = new Pagination($total_records,$record_count);
                                    $pager->page_name='cust_result_vu';
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
    