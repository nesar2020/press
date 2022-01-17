
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
                
                <div class="col-sm-3 comp-grid">
                    <h3 class="record-title"><?php echo "راپور روزمره"; ?></h3>
                    
                </div>
                
                <div class="col-sm-1 comp-grid no-print" >
                    <div class="">
                        <button class="btn btn-sm btn-primary export-btn"><i class="material-icons">save</i> <?php print_lang('list_export_btn_text'); ?></button>
                    </div>
                </div>

                <div class="col-md-4 no-print">
                    <input id="e_date" class="form-control datepicker"
                           value="<?php echo $this->set_field_value('e_date') ?>"
                           type="datetime" name="e_date"
                           placeholder="<?php print_lang('select_placeholder'); ?>"
                           data-enable-time="" data-date-format="Y-m-d" data-alt-format="M j, Y"
                           data-inline="false" data-no-calendar="false" data-mode="single" />
                </div>
                <div class="col-md-2 no-print">
                    <button id="subbtn" class="btn btn-primary " type="submit">
                        ایجاد راپور
                        <i class="material-icons">send</i>
                    </button>
                </div>
                    
                    <div class="col-md-12 comp-grid no-print">
                        <div class="">
                            <?php
                            if(!empty($field_name)){
                            ?>
                            <hr class='sm d-block d-sm-none' />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link("cust_payments/cust_list/$custid") ?>"><?php echo $field_name ?></a></li>
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
                                        <a class="text-capitalize" href="<?php print_link("cust_payments/cust_list/$custid") ?>">&laquo; <?php print_lang('link_back'); ?></a>
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
                            <div id="cust_payments-list-records">
                                <?php $this :: display_page_errors(); ?>
                                
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                <th > <?php echo "مقدار پول"; ?></th>
                                                <th > <?php echo "ارز"; ?></th>

                                                <th > <?php print_lang('cust_payments_list_cust_date_title'); ?></th>
                                                <th > <?php print_lang('ctp_orders_view_description_label'); ?></th>
                                                <th > <?php print_lang('cust_payments_list_cust_id_title'); ?></th>
                                                
                                                <th class="td-btn no-print"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                            $counter = 0;
                                            foreach($records as $data){
                                            $counter++;
                                            ?>
                                            <tr>
                                                    <td> <?php echo $data['amount']; ?> </td>
                                                    <td> <?php echo $data['currency']; ?> </td>
                                                    <td> <?php echo $data['cust_date']; ?> </td>
                                                    <td> <?php echo $data['description']; ?> </td>
                                                    <td>
                                                        <a size="sm" class="btn btn-sm btn-primary _page-modal" href="<?php //print_link("customers/View/id/$data[cust_id]") ?>">
                                                            <?php echo $data['cust_name']; ?>
                                                        </a>
                                                    </td>
                                                    
                                                    
                                                    <th class="td-btn no-print">
                                                        <a class="btn btn-sm btn-info " title="<?php print_lang('btn_edit_tooltip'); ?>" href="<?php print_link('cust_payments/edit/'.$data['id']); ?>">
                                                            <i class="material-icons">edit</i> <?php print_lang('btn_edit'); ?>
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

<script>
    $(document).ready(function () {
        $("#subbtn").click(function () {
            var edate=$("#e_date").val();
            window.location.replace( "daily_pay?e_date="+edate);
        });
    });

</script>