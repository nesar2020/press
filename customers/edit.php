
<?php
$comp_model = new SharedController;
$data = $this->view_data;

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
                    <h3 class="record-title"><?php print_lang('btn_edit');echo " مشتری "; ?></h3>
                    
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
                        <form role="form" enctype="multipart/form-data"  class="form form-horizontal needs-validation" novalidate action="<?php print_link("customers/edit/$page_id"); ?>" method="post">
                            <div class="card-body">
                                
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="cust_name"><?php print_lang('customers_view_cust_name_label'); ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="cust_name" value="<?php  echo $data['cust_name']; ?>" type="text" placeholder="<?php print_lang('customers_add_cust_name_placeholder'); ?>"  required="" name="cust_name" class="form-control " />
                                                    
                                                    
                                                    
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="office_name"><?php print_lang('customers_view_office_name_label'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input  id="office_name" value="<?php  echo $data['office_name']; ?>" type="text" placeholder="<?php print_lang('customers_add_office_name_placeholder'); ?>"  required="" name="office_name" class="form-control " />
                                                        
                                                        
                                                        
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="phone"><?php print_lang('customers_view_phone_label'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <input  id="phone" value="<?php  echo $data['phone']; ?>" type="text" placeholder="<?php print_lang('customers_add_phone_placeholder'); ?>"  required="" name="phone" class="form-control " />
                                                            
                                                            
                                                            
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="email"><?php print_lang('customers_view_email_label'); ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input  id="email" value="<?php  echo $data['email']; ?>" type="email" placeholder="<?php print_lang('customers_add_email_placeholder'); ?>" name="email" class="form-control " />
                                                                
                                                                
                                                                
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="address"><?php print_lang('customers_view_address_label'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input  id="address" value="<?php  echo $data['address']; ?>" type="text" placeholder="<?php print_lang('customers_add_address_placeholder'); ?>"  required="" name="address" class="form-control " />
                                                                    
                                                                    
                                                                    
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
                    