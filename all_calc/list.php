
<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;

$cpayments= $view_data->cpayments;
$flex= $view_data->flex;
$ctp= $view_data->ctp;
$paper= $view_data->paper;
$expend= $view_data->expend;
$supp_pay= $view_data->supp_pay;
$supp_pur= $view_data->supp_pur;
$emp_pay= $view_data->emp_pay;
$emp_sal= $view_data->emp_sal;
$af2usd= $view_data->af2usd;
$usd2af= $view_data->usd2af;


$currencies = $comp_model -> list_currencies();
// for customer orders and payments
$order_tot=array();
$order_rem=array();
// suppliers order and payments
$supp_tot=array();
$supp_rem=array();
//money in available
$inven_tot=array();
$inven_rem=array();
foreach($currencies as $c){
    $order_tot[$c['label']]=0;
    $order_rem[$c['label']]=0;
    $supp_tot[$c['label']]=0;
    $supp_rem[$c['label']]=0;
    $inven_tot[$c['label']]=0;
    $inven_rem[$c['label']]=0;

}

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
                    <h3 class="record-title"><?php print_lang('all_calc_list_header_title'); ?></h3>
                    
                </div>
                
                <div class="col-sm-5 comp-grid">
                    
                </div>
                
                <div class="col-md-12 comp-grid">

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
                        <div id="all_calc-list-records">
                            <?php $this :: display_page_errors(); ?>
                            <div class="page-records table-responsive">
                                <table class="table  table-striped table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <th>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <table>
                                                            <tr><td colspan="2"> <?php echo "مجموع فرمایشات ctp"; ?></td></tr>
                                                            <?php foreach($ctp as $tp){
                                                                $order_tot[$tp['currency']]+=$tp['total'];  ?>
                                                                <tr>
                                                                    <td><?php echo $tp['currency'] ?></td>
                                                                    <td><?php echo number_format($tp['total'],2); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <table>
                                                            <tr><td colspan="2"> <?php echo "مجموع فرمایشات دیجیتل"; ?></td></tr>
                                                            <?php foreach($flex as $tp){
                                                                $order_tot[$tp['currency']]+=$tp['total'];  ?>
                                                                <tr>
                                                                    <td><?php echo $tp['currency'] ?></td>
                                                                    <td><?php echo number_format($tp['total'],2); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <table>
                                                            <tr><td colspan="2"> <?php echo "مجموع فرمایشات کاغذی"; ?></td></tr>
                                                            <?php foreach($paper as $tp){
                                                                $order_tot[$tp['currency']]+=$tp['total'];  ?>
                                                                <tr>
                                                                    <td><?php echo $tp['currency'] ?></td>
                                                                    <td><?php echo number_format($tp['total'],2); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th > <?php echo "قیمت مجموعی کل فرمایشات"; ?></th>
                                            <td><table>
                                                <?php foreach($order_tot as $key=>$value){  ?>
                                                    <tr>
                                                        <td><?php echo $key; ?></td>
                                                        <td><?php echo $value; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table></td>
                                        </tr>
                                        <tr>
                                            <th > <?php print_lang('all_calc_list_cpayments_title'); ?></th>
                                            <td><table>
                                            <?php foreach($cpayments as $tp){
                                                $order_rem[$tp['currency']]+=$tp['total'];
                                                $inven_tot[$tp['currency']]+=$tp['total'];  ?>
                                                <tr>
                                                    <td><?php echo $tp['currency'] ?></td>
                                                    <td><?php echo number_format($tp['total'],2); ?></td>
                                                </tr>
                                            <?php } ?>
                                            </table></td>
                                        </tr>

                                        <tr>
                                            <th > <?php print_lang('all_calc_list_supp_pur_title'); ?></th>
                                            <td><table>
                                                <?php foreach($supp_pur as $tp){
                                                    $supp_rem[$tp['currency']]+=$tp['total'];  ?>
                                                    <tr>
                                                        <td><?php echo $tp['currency'] ?></td>
                                                        <td><?php echo number_format($tp['total'],2); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table></td>
                                        </tr>
                                        <tr>
                                            <th > <?php print_lang('all_calc_list_supp_pay_title'); ?></th>
                                            <td><table>
                                                    <?php foreach($supp_pay as $tp){
                                                        $supp_tot[$tp['currency']]+=$tp['total'];
                                                        $inven_rem[$tp['currency']]+=$tp['total'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $tp['currency'] ?></td>
                                                            <td><?php echo number_format($tp['total'],2); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table></td>
                                        </tr>

                                        <tr>
                                            <th > <?php print_lang('all_calc_list_emp_sal_title'); ?></th>
                                            <td><table>
                                                    <?php foreach($emp_sal as $tp){
                                                        $supp_rem[$tp['currency']]+=$tp['total'];  ?>
                                                        <tr>
                                                            <td><?php echo $tp['currency'] ?></td>
                                                            <td><?php echo number_format($tp['total'],2); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table></td>
                                        </tr>
                                        <tr>
                                            <th > <?php print_lang('all_calc_list_emp_pay_title'); ?></th>
                                            <td><table>
                                                    <?php foreach($emp_pay as $tp){
                                                        $supp_tot[$tp['currency']]+=$tp['total'];
                                                        $inven_rem[$tp['currency']]+=$tp['total'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $tp['currency'] ?></td>
                                                            <td><?php echo number_format($tp['total'],2); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table></td>
                                        </tr>

                                            <tr>
                                                <th > <?php print_lang('all_calc_list_expend_title'); ?></th>
                                                <td><table>
                                                        <?php foreach($expend as $tp){
                                                            //$order_tot[$tp['currency']]+=$tp['total'];
                                                            $inven_rem[$tp['currency']]+=$tp['total'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $tp['currency'] ?></td>
                                                                <td><?php echo number_format($tp['total'],2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table></td>
                                            </tr>
                                            <tr>
                                                <th > <?php echo "تبادله افغانی به دالر"; ?></th>
                                                <td>
                                                    <?php
                                                        if(isset($af2usd)){
                                                            $inven_tot['دالر']+=$af2usd['dollar'];
                                                            $inven_rem['افغانی']+=$af2usd['afghani'];
                                                    ?>

                                                    <table>
                                                        <tr>
                                                            <td><?php echo number_format($af2usd['afghani'],2); ?></td>
                                                            <td><?php echo 'افغانی' ?></td>
                                                            <td><?php echo 'تبادله به'; ?></td>
                                                            <td><?php echo number_format($af2usd['dollar'],2); ?></td>
                                                            <td><?php echo 'دالر' ?></td>
                                                        </tr>

                                                    </table>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th > <?php echo "تبادله دالر به افغانی"; ?></th>
                                                <td>
                                                    <?php
                                                        if(isset($usd2af)){
                                                            $inven_tot['افغانی']+=$usd2af['afghani'];
                                                            $inven_rem['دالر']+=$usd2af['dollar'];
                                                    ?>
                                                    <table>
                                                        <tr>
                                                            <td><?php echo number_format($usd2af['dollar'],2); ?></td>
                                                            <td><?php echo 'دالر' ?></td>
                                                            <td><?php echo 'تبادله به'; ?></td>
                                                            <td><?php echo number_format($usd2af['afghani'],2); ?></td>
                                                            <td><?php echo 'افغانی' ?></td>
                                                        </tr>

                                                    </table>
                                                    <?php } ?>
                                                </td>
                                            </tr>


                                        
                                    </tbody>
                                </table>
                                <div style="padding:20px;">
                                    <table class="table  table-sm table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><h5 style="padding: 10px;background: #eee;"><strong> پول موجود</strong></h5></td>
                                                <td>
                                                    <table>
                                                        <?php foreach($inven_tot as $key=>$value): ?>
                                                            <tr>
                                                                <td><?php echo $key; ?></td>
                                                                <td dir="ltr"><?php
                                                                    if(!empty($inven_rem[$key])){echo number_format(($value-$inven_rem[$key]),2);}
                                                                    else{ echo number_format($value,2);}
                                                                    ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                     <?php //echo ($data['cpayments']-($data['emp_pay']+$data['supp_pay']+$data['expend'])); ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><h5 style="padding: 10px;background: #eee;"><strong>قروض مطبعه</strong></h5></td>
                                                <td>
                                                    <table>
                                                        <?php foreach($supp_tot as $key=>$value): ?>
                                                            <tr>
                                                                <td><?php echo $key; ?></td>
                                                                <td dir="ltr"><?php
                                                                    if(!empty($supp_rem[$key])){echo number_format(($value-$supp_rem[$key]),2);}
                                                                    else{ echo number_format($value,2);}
                                                                    ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                    <?php //echo (($data['emp_pay']+$data['supp_pay'])-($data['supp_pur']+$data['emp_sal'])); ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><h5 style="padding: 10px;background: #eee;"><strong>قروض مشتریان</strong></h5></td>
                                                <td>
                                                    <h5 style="padding: 10px;background: #eee;">
                                                        <table>
                                                            <?php foreach($order_rem as $key=>$value): ?>
                                                                <tr>
                                                                    <td><?php echo $key; ?></td>
                                                                    <td dir="ltr"><?php
                                                                        if(!empty($order_tot[$key])){echo number_format(($value-$order_tot[$key]),2);}
                                                                        else{ echo number_format($value,2);}
                                                                        ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    
</section>
