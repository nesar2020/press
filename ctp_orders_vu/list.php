
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
                    <h3 class="record-title"><?php print_lang('ctp_orders_vu_list_title'); ?></h3>
                    
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
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link('ctp_orders_vu') ?>"><?php echo $field_name ?></a></li>
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
                                        <a class="text-capitalize" href="<?php print_link('ctp_orders_vu') ?>">&laquo; <?php print_lang('link_back'); ?></a>
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
                            <div id="ctp_orders_vu-list-records">
                                <?php $this :: display_page_errors(); ?>
                                
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                
                                                <th class="td-sno">#</th>
                                                <th > <?php print_lang('ctp_orders_list_description_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_date_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_plate_type_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_plate_no_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_plate_price_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_total_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_vu_list_u_id_title'); ?></th>
                                                
                                                
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
                                                <td> <?php echo $data['description']; ?> </td>
                                                <td> <?php echo $data['date']; ?> </td>
                                                <td> <?php echo $data['plate_type']; ?> </td>
                                                <td> <?php echo $data['plate_no']; ?> </td>
                                                <td> <?php echo $data['plate_price']; ?> </td>
                                                <td> <?php echo $data['total']; ?> </td>
                                                <td>
                                                    <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("customers/View/id/$data[u_id]") ?>">
                                                        <?php echo $data['u_id']; ?>  <i class="material-icons">visibility</i>
                                                    </a>
                                                </td>
                                                
                                                
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
                                    $pager->page_name='ctp_orders_vu';
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
    