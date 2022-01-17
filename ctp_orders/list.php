
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
                    <h3 class="record-title"><?php echo "لیست فرمایشات ctp"; ?></h3>
                    
                </div>
                
                <div class="col-sm-3 comp-grid">
                    
                    <a  class="btn btn btn-primary btn-block" href="<?php print_link("ctp_orders/add") ?>">
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
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link('ctp_orders') ?>"><?php echo $field_name ?></a></li>
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
                                        <a class="text-capitalize" href="<?php print_link('ctp_orders') ?>">&laquo; <?php print_lang('link_back'); ?></a>
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
                            <div id="ctp_orders-list-records">
                                <?php $this :: display_page_errors(); ?>
                                
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                <th > <?php print_lang('ctp_orders_list_description_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_list_date_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_list_plate_type_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_list_plate_no_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_list_plate_price_title'); ?></th>
                                                <th > <?php echo "قیمت مجموعی"; ?></th>
                                                <th > <?php echo "ارز"; ?></th>
                                                <th > <?php echo "رسید"; ?></th>
                                                <th > <?php echo "باقی"; ?></th>
                                                <th > <?php print_lang('ctp_orders_list_u_id_title'); ?></th>


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

                                                    <td> <?php echo $data['description']; ?> </td>
                                                    <td> <?php echo $data['date']; ?> </td>
                                                    <td> <?php echo $data['plate_type']; ?> </td>
                                                    <td> <?php echo $data['plate_no']; ?> </td>
                                                    <td> <?php echo $data['plate_price']; ?> </td>
                                                    <td> <?php echo ($data['total']); ?> </td>
                                                    <td> <?php echo $data['currency']; ?> </td>
                                                    <td> <?php echo ($data['pay']); ?> </td>
                                                    <td> <?php echo ($data['total']-$data['pay']); ?> </td>
                                                    <td>
                                                        <a size="sm" class="btn btn-sm btn-primary _page-modal" href="<?php //print_link("customers/View/id/$data[u_id]") ?>">
                                                            <?php echo $data['cust_name']; ?>
                                                        </a>
                                                    </td>
                                                    
                                                    
                                                    <th class="td-btn">
                                                        <a class="btn btn-sm btn-info " title="<?php print_lang('btn_edit_tooltip'); ?>" href="<?php print_link('ctp_orders/edit/'.$data['id']); ?>">
                                                            <i class="material-icons">edit</i> <?php //print_lang('btn_edit'); ?>
                                                        </a>

                                                        <a class="btn btn-sm btn-danger recordDeletePromptAction"
                                                           title="<?php print_lang('btn_delete_tooltip'); ?>"
                                                           href="<?php print_link('ctp_orders/del/'.$data['id']); ?>" data-prompt-msg="">
                                                            <i class="material-icons">clear</i>
                                                            <?php print_lang('btn_delete'); ?>
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
                                            
                                            <button data-prompt-msg="Are you sure you want to delete these records" data-url="<?php print_link("ctp_orders/delete/{sel_ids}"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                <i class="material-icons">clear</i> <?php print_lang('btn_delete_selected'); ?>
                                            </button>
                                            
                                            
                                            <button class="btn btn-sm btn-primary export-btn"><i class="material-icons">save</i> <?php print_lang('list_export_btn_text'); ?></button>
                                            
                                            
                                            <?php Html :: import_form('ctp_orders/import_data' , get_lang('list_import_btn_text'), 'CSV , JSON'); ?>
                                            
                                        </div>
                                        
                                        <?php
                                        if( $show_pagination == true ){
                                        $pager = new Pagination($total_records,$record_count);
                                        $pager->page_name='ctp_orders';
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
        