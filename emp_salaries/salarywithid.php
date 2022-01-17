
<?php
$comp_model = new SharedController;
$data = $this->view_data;
$emp = $data['emp'];
$custid = $data['custid'];
$month = $data['month'];

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
                    <h3 class="record-title"><?php print_lang('txt_add_page_title');echo " معاش "; ?></h3>
                    
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
                        <form id="emp_salaries-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation"  novalidate action="<?php print_link("emp_salaries/salarywithid/".$custid) ?>" method="post">
                            <div class="card-body">


                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="amount"><?php echo "مقدار معاش"; ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <label class="form-control"><?php echo $emp['salary']; ?></label>
                                                <input type="hidden" class="form-control" name="amount" value="<?php echo $emp['salary']; ?>" />
                                            </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="description"><?php print_lang('ctp_orders_view_description_label'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input  id="description" value="<?php  echo $this->set_field_value('description','معاش ماه '.$month); ?>" type="text" placeholder="<?php print_lang('ctp_orders_add_description_placeholder'); ?>"  required="" name="description" class="form-control " />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="emp_id"><?php print_lang('emp_payments_list_emp_id_title'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <select required=""  name="emp_id" placeholder="<?php print_lang('prompt_select_placeholder'); ?>"    class="form-control">
                                                            <option value=""><?php print_lang('prompt_select_placeholder'); ?></option>
                                                            
                                                            <?php 
                                                            $emp_id_options = $comp_model -> emp_salaries_emp_id_option_list();
                                                            
                                                            if(!empty($emp_id_options)){
                                                            foreach($emp_id_options as $arr){
                                                            $val=array_values($arr);
                                                            ?>
                                                            <option <?php  if(!empty($custid) && $custid==$val[0]){echo "selected='selected'";}  echo $this->set_field_selected('emp_id',$val[0]) ?> value="<?php echo $val[0]; ?>">
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
                                        
                                        
                                        
                                        
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="s_date"><?php print_lang('emp_salaries_list_s_date_title'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input  id="s_date" class="form-control datepicker" required=""
                                                                value="<?php  echo $this->set_field_value('s_date',$month); ?>"
                                                                type="datetime" name="s_date"
                                                                placeholder="<?php print_lang('emp_salaries_add_s_date_placeholder'); ?>"
                                                                data-enable-time="false"   data-date-format="Y-m" data-alt-format="F, Y"
                                                                data-inline="false" data-no-calendar="false" data-mode="single" />

                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                                            </div>
                                                            
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
            