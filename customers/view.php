<?php
$comp_model = new SharedController;

//Page Data Information from Controller
$view_data = $this->view_data;
$currencies = $comp_model -> list_currencies();

$record= $view_data->record;
$total_paper= $view_data->total_paper;
$total_ctp= $view_data->total_ctp;
$total_flex= $view_data->total_flex;
$total_pay= $view_data->total_pay;
$order_tot=array();
$order_rem=array();
foreach($currencies as $c){
    $order_tot[$c['label']]=0;
    $order_rem[$c['label']]=0;
}

//$rec_id = $data['__tableprimarykey'];
$page_id = Router::$page_id; //Page id from url

$view_title = $this->view_title;

$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;

?>

<section class="page">

    <!-- customer details -->
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            
            <div class="row ">
                
                <div class="col-12 comp-grid">
                    <h3 class="record-title"><?php echo " بیلانس مشتری "; ?></h3>
                    
                </div>
                
            </div>
        </div>
    </div>
    <div  class="">
        <div class="container">
            
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    
                    <div  class="card animated fadeIn" style="padding:5px;">
                        <?php 
                        $this :: display_page_errors(); 
                        
                        if(!empty($record)){
                        ?>
                        <div class="page-records ">
                            <table class="table table-hover table-bordered table-striped">
                                <!-- Table Body Start -->
                                <tbody>
                                    <tr>
                                        <th class="title"> <?php print_lang('ctp_orders_list_id_title'); ?> </th>
                                        <th class="title"> <?php print_lang('customers_view_cust_name_label'); ?> </th>
                                        <th class="title"> <?php print_lang('customers_view_office_name_label'); ?> </th>
                                        <th class="title"> <?php print_lang('customers_view_phone_label'); ?> </th>
                                        <th class="title"> <?php print_lang('customers_view_email_label'); ?> </th>
                                        <th class="title"> <?php print_lang('customers_view_address_label'); ?> </th>

                                    </tr>
                                    <tr>
                                        <td class="value"> <?php echo $record['id']; ?> </td>
                                        <td class="value"> <?php echo $record['cust_name']; ?> </td>
                                        <td class="value"> <?php echo $record['office_name']; ?> </td>
                                        <td class="value"> <?php echo $record['phone']; ?> </td>
                                        <td class="value"> <?php echo $record['email']; ?> </td>
                                        <td class="value"> <?php echo $record['address']; ?> </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                            <hr/>

                            <div class="row">
                                <div class="col-md-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <tr><td colspan="2">فرمایشات کاغذی</td></tr>
                                        <?php foreach($total_paper as $tp): ?>
                                        <?php
                                            $order_tot[$tp['currency']]+=$tp['total']
                                        ?>
                                        <tr>
                                            <td><?php echo $tp['currency'] ?></td>
                                            <td><?php echo $tp['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <tr><td colspan="2">فرمایشات دیجیتل</td></tr>
                                        <?php foreach($total_flex as $tp): ?>
                                        <?php
                                        $order_tot[$tp['currency']]+=$tp['total']
                                        ?>
                                        <tr>
                                            <td><?php echo $tp['currency'] ?></td>
                                            <td><?php echo $tp['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </table>
                                </div>
‍‍‍‍‍‍‍‍‍‍                                    <div class="col-md-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <tr><td colspan="2">فرمایشات CTP</td></tr>
                                        <?php foreach($total_ctp as $tp): ?>
                                        <?php
                                        $order_tot[$tp['currency']]+=$tp['total']
                                        ?>
                                        <tr>
                                            <td><?php echo $tp['currency'] ?></td>
                                            <td><?php echo $tp['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <tr><td colspan="2">رسیدات</td></tr>
                                        <?php foreach($total_pay as $tp): ?>
                                        <?php
                                            $order_rem[$tp['currency']]=$tp['total'];
                                        ?>
                                        <tr>
                                            <td><?php echo $tp['currency'] ?></td>
                                            <td><?php echo $tp['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </table>
                                </div>
‍‍‍‍‍‍‍‍‍‍
                            </div>

                            <hr/>
                            <div class="row">
                                <div class="col-md-1">
                                    <a class="btn btn-sm btn-success"  href="<?php print_link("customers/list/"); ?>">
                                        <i class="material-icons">keyboard_arrow_left</i>    برگشت
                                    </a>
                                </div>

                                <div class="col-md-8">
                                    <a class="btn btn-sm btn-outline-primary "
                                       href="<?php print_link('cust_payments/paywithcid/'.$record['id']); ?>">
                                        رسید
                                    </a>
                                    <a class="btn btn-sm btn-outline-info" href="<?php print_link('ctp_orders/ctpwithid/'.$record['id']); ?>">
                                    CTP
                                    </a>
                                    <a class="btn btn-sm btn-outline-info" href="<?php print_link('flex_orders/flexwithid/'.$record['id']); ?>">
                                    دیجیتل
                                    </a>
                                    <a class="btn btn-sm btn-outline-info"  href="<?php print_link('paper_orders/paperwithid/'.$record['id']); ?>">
                                    کاغذ
                                    </a>
                                    <a class="btn btn-md btn-outline-primary"  href="<?php print_link("cust_payments/cust_list/".$record['id']); ?>">
                                        <i class="material-icons"></i>رسیدات
                                    </a>
                                    <a class="btn btn-md btn-outline-primary"  href="<?php print_link("ctp_orders/cust_list/".$record['id']); ?>">
                                        <i class="material-icons"></i>فرمایش های  CTP
                                    </a>
                                    <a class="btn btn-md btn-outline-primary"  href="<?php print_link("flex_orders/cust_list/".$record['id']); ?>">
                                        <i class="material-icons"></i>فرمایش های دیجیتل
                                    </a>
                                    <a class="btn btn-md btn-outline-primary"  href="<?php print_link("paper_orders/cust_list/".$record['id']); ?>">
                                        <i class="material-icons"></i>فرمایش های کاغذی
                                    </a>


                                </div>

                                <div class="col-md-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <tr><td colspan="2">باقیات</td></tr>
                                        <?php foreach($order_tot as $key=>$value): ?>
                                            <tr>
                                                <td><?php echo $key; ?></td>
                                                <td><?php
                                                    if(!empty($order_rem[$key])){echo ($value-$order_rem[$key]);}
                                                    else{ echo $value;}
                                                ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                                ‍‍‍‍‍‍‍‍‍‍
                            </div>



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
    </div><br/>



</section>