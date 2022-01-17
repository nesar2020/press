<?php
$comp_model = new SharedController;
$custid = $this->view_data;

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
                    <h3 class="record-title"><?php print_lang('txt_add_page_title');echo " ctp "; ?></h3>
                    
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
                        <form id="ctp_orders-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation"  novalidate action="<?php print_link("ctp_orders/ctpwithid/".$custid) ?>" method="post">
                            <div class="card-body">
                                
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="description"><?php print_lang('ctp_orders_view_description_label'); ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="description" value="<?php  echo $this->set_field_value('description',''); ?>" type="text" placeholder="<?php print_lang('ctp_orders_add_description_placeholder'); ?>"  required="" name="description" class="form-control " />
                                                    
                                                    
                                                    
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="date"><?php print_lang('ctp_orders_list_date_title'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input  id="date" class="form-control datepicker" required=""
                                                            value="<?php  echo $this->set_field_value('date',date("Y-m-d")); ?>" type="datetime" name="date" placeholder="<?php print_lang('ctp_orders_add_date_placeholder'); ?>" data-enable-time="false"   data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                        
                                                        
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="plate_type"><?php print_lang('ctp_orders_list_plate_type_title'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <input  id="plate_type" value="<?php  echo $this->set_field_value('plate_type',''); ?>" type="text" placeholder="<?php print_lang('ctp_orders_add_plate_type_placeholder'); ?>"  required="" name="plate_type" class="form-control " />
                                                            
                                                            
                                                            
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="plate_no"><?php print_lang('ctp_orders_list_plate_no_title'); ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input  id="plate_no" value="<?php  echo $this->set_field_value('plate_no',''); ?>" type="number" placeholder="<?php print_lang('ctp_orders_add_plate_no_placeholder'); ?>" step="1"  required="" name="plate_no" class="form-control " />
                                                                
                                                                
                                                                
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="plate_price"><?php print_lang('ctp_orders_list_plate_price_title'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input  id="plate_price" value="<?php  echo $this->set_field_value('plate_price',''); ?>" type="number" placeholder="<?php print_lang('ctp_orders_add_plate_price_placeholder'); ?>" step="any"  required="" name="plate_price" class="form-control " />
                                                                    
                                                                    
                                                                    
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                <!-- currencies -->
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
                                                            <label class="control-label" for="u_id"><?php print_lang('ctp_orders_list_u_id_title'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <select  required=""  name="u_id" placeholder="<?php print_lang('prompt_select_placeholder'); ?>"    class="form-control">
                                                                    <option value=""><?php print_lang('prompt_select_placeholder'); ?></option>

                                                                    <?php
                                                                    $u_id_options = $comp_model -> ctp_orders_u_id_option_list();

                                                                    if(!empty($u_id_options)){
                                                                    foreach($u_id_options as $arr){
                                                                    $val=array_values($arr);
                                                                    ?>
                                                                    <option <?php if(!empty($custid) && $custid==$val[0]){echo "selected='selected'";} echo $this->set_field_selected('u_id',$val[0]) ?> value="<?php echo $val[0]; ?>">
                                                                        <?php echo (!empty($val[1]) ? $val[1] : $val[0]); ?>
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