<?php
$comp_model = new SharedController;
$data_v = $this->view_data;

$data = $data_v['data'];
$data_pay = $data_v['data_pay'];

//$rec_id = $data['__tableprimarykey'];
$page_id = Router :: $page_id;

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
                    <h3 class="record-title"><?php print_lang('btn_edit'); echo " فرمایش ctp " ?></h3>
                    
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
                        <form role="form" enctype="multipart/form-data"  class="form form-horizontal needs-validation" novalidate action="<?php print_link("ctp_orders/edit_d/$page_id"); ?>" method="post">
                            <div class="card-body">
                                
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="description"><?php print_lang('ctp_orders_view_description_label'); ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="description" value="<?php  echo $data['description']; ?>" type="text" placeholder="<?php print_lang('ctp_orders_add_description_placeholder'); ?>"  required="" name="description" class="form-control " />
                                                    
                                                    
                                                    
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
                                                    <input  id="date" class="form-control datepicker" required="" value="<?php  echo $data['date']; ?>" type="datetime" name="date" placeholder="<?php print_lang('ctp_orders_add_date_placeholder'); ?>" data-enable-time="false"   data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                        
                                                        
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
                                                        <input  id="plate_type" value="<?php  echo $data['plate_type']; ?>" type="text" placeholder="<?php print_lang('ctp_orders_add_plate_type_placeholder'); ?>"  required="" name="plate_type" class="form-control " />
                                                            
                                                            
                                                            
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
                                                            <input  id="plate_no" value="<?php  echo $data['plate_no']; ?>" type="number" placeholder="<?php print_lang('ctp_orders_add_plate_no_placeholder'); ?>" step="1"  required="" name="plate_no" class="form-control " />
                                                                
                                                                
                                                                
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
                                                                <input  id="plate_price" value="<?php  echo $data['plate_price']; ?>" type="number" placeholder="<?php print_lang('ctp_orders_add_plate_price_placeholder'); ?>" step="any"  required="" name="plate_price" class="form-control " />

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
                                                    $o_currency = $data['currency'];
                                                    $currencies = $comp_model -> list_currencies();

                                                    if(!empty($currencies)){
                                                        foreach($currencies as $arrc){
                                                            $valc=array_values($arrc);
                                                            $selected = ( $valc[0] == $o_currency ? ' selected="selected" ' : null ) ;
                                                            ?>
                                                            <option <?php echo $selected; ?>  value="<?php echo $valc[0]; ?>">
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
                                                                    <select required=""  name="u_id" placeholder="<?php print_lang('prompt_select_placeholder'); ?>"    class="form-control">
                                                                        <?php
                                                                        $rec = $data['u_id'];
                                                                        $u_id_options = $comp_model -> ctp_orders_u_id_option_list();
                                                                        if(!empty($u_id_options)){
                                                                        foreach($u_id_options as $arr){
                                                                        $val=array_values($arr);
                                                                        $selected = ( $val[0] == $rec ? ' selected="selected" ' : null ) ;
                                                                        ?>
                                                                        <option <?php echo $selected; ?> value="<?php echo $val[0]; ?>"><?php echo (!empty($val[1]) ? $val[1] : $val[0]); ?></option>
                                                                        <?php
                                                                        }
                                                                        }
                                                                        ?>
                                                                        
                                                                    </select> 
                                                                    
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr/>

                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="amount"><?php echo "رسید پول "; ?> <span class="text-danger"></span></label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <input  id="amount" value="<?php  echo $data_pay['amount']; ?>" type="number" placeholder="<?php echo "مقدار پول را وارد کنید"; ?>" step="any"   name="amount" class="form-control " />
                                                                    <input  id="pay_id" value="<?php  echo $data_pay['id']; ?>" type="hidden"  step="any"   name="pay_id" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                <!-- reciept currencies -->
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="currency_r"><?php echo "ارز"; ?>
                                                <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <select  required=""  name="currency_r"  class="form-control">

                                                    <?php
                                                    $currencies = $comp_model -> list_currencies();
                                                    $r_currency = $data_pay['currency'];
                                                    if(!empty($currencies)){
                                                        foreach($currencies as $arrc){
                                                            $valc=array_values($arrc);
                                                            $r_selected = ( $valc[0] == $r_currency ? ' selected="selected" ' : null ) ;
                                                            ?>
                                                            <option <?php echo $r_selected; ?>  value="<?php echo $valc[0]; ?>">
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

                            </div>
                                                <div class="form-group text-center">
                                                    <button class="btn btn-primary" type="submit">
                                                        <?php print_lang('edit_btn_submit'); ?>
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