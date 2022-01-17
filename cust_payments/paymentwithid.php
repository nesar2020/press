
<?php
$comp_model = new SharedController;
$data = $this->view_data;
$custid=$data['custid'];

$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;

?>

<section class="page">
    
    <?php
    if( $show_header == true ){
    ?>
    
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            
            <div class="row ">
                
                <div class="col-12 comp-grid">
                    <h3 class="record-title"><?php print_lang('txt_add_page_title');echo " پرداخت "; ?></h3>
                    
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
                
                <div class="col-md-7 comp-grid">
                    
                    <div  class="card animated fadeIn">
                        <?php 
                        $this :: display_page_errors(); 
                        ?>
                        <form id="cust_payments-add-form" role="form" enctype="multipart/form-data"
                              class="form form-horizontal needs-validation"  novalidate
                              action="<?php print_link("cust_payments/paymentwithid/".$custid."/".$data['order_id']."/".$data['order_type']) ?>" method="post">
                            <div class="card-body">
                                
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="amount"><?php echo "مقدار پول "; ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="amount" value="<?php  echo $this->set_field_value('amount',''); ?>" type="number" placeholder="<?php echo "مقدار پول را وارد کنید"; ?>" step="1"  required="" name="amount" class="form-control " />
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="currency"><?php echo "ارز"; ?>
                                                <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <select  required=""  name="currency"  class="form-control">

                                                    <?php
                                                    $currencies = $comp_model -> list_currencies();

                                                    if(!empty($currencies)){
                                                        foreach($currencies as $arrc){
                                                            $valc=array_values($arrc);
                                                            ?>
                                                            <option  value="<?php echo $valc[0]; ?>">
                                                                <?php echo (!empty($valc[1]) ? $valc[1] : $valc[0]); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="cust_date"><?php print_lang('cust_payments_list_cust_date_title'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input  id="cust_date" class="form-control datepicker" required=""
                                                            value="<?php  echo $this->set_field_value('cust_date',date("Y-m-d")); ?>" type="datetime" name="cust_date" placeholder="<?php print_lang('cust_payments_add_cust_date_placeholder'); ?>" data-enable-time="false"   data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                        
                                                        
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        

                                            
                                            <div class="form-group " style="display:none">
                                                <div class="row">
                                                    <div class="col-sm-4">

                                                        <label style="" class="control-label" for="cust_id"><?php print_lang('cust_payments_list_cust_id_title'); ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <select  required=""  name="cust_id" placeholder="<?php print_lang('prompt_select_placeholder'); ?>"    class="form-control">
                                                                <option value=""><?php print_lang('prompt_select_placeholder'); ?></option>
                                                                
                                                                <?php 
                                                                $cust_id_options = $comp_model -> cust_payments_cust_id_option_list();
                                                                
                                                                if(!empty($cust_id_options)){
                                                                foreach($cust_id_options as $arr){
                                                                $val=array_values($arr);
                                                                ?>
                                                                <option <?php if(!empty($custid) && $custid==$val[0]){echo "selected='selected'";} echo $this->set_field_selected('cust_id',$val[0]) ?> value="<?php echo $val[0]; ?>">
                                                                    <?php echo (!empty($val[1]) ? $val[1] : $val[0]); ?>
                                                                </option>
                                                                <?php
                                                                }
                                                                }
                                                                ?>
                                                                
                                                            </select> 
                                                            
                                                        </div>
                                                        <input type="hidden" value="<?php echo $data['order_type']; ?>" name="order_type"/>
                                                        <input type="hidden" value="<?php echo $data['order_id']; ?>" name="order_id"/>

                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                        </div>
                                        <div class="form-group form-submit-btn-holder text-center">
                                            <button class="btn btn-primary" type="submit">
                                                <?php print_lang('ctp_orders_add_btn_submit'); ?>
                                                <i class="material-icons">send</i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </section>
            