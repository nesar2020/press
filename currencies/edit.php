
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

                <div class="col-10 comp-grid">
                    <h3 class="record-title"><?php echo "ویرایش اسعار"; ?></h3>

                </div>
                <div class="col-2 comp-grid">
                    <a  class="btn btn-outline btn-primary btn-block" href="<?php print_link("currencies/list") ?>">
                        <i class="material-icons">keyboard_arrow_left</i>
                        <?php echo "برگشت"; ?>
                    </a>
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
                    
                    <?php $this :: display_page_errors(); ?>
                    
                    <div  class="card animated fadeIn">
                        <form role="form" enctype="multipart/form-data"  class="form form-horizontal needs-validation" novalidate action="<?php print_link("currencies/edit/$page_id"); ?>" method="post">
                            <div class="card-body">
                                
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name"><?php echo "نام اسعار"; ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="currency" value="<?php  echo $data['currency']; ?>" type="text" placeholder="<?php print_lang('enter_name'); ?>"  required="" name="currency" class="form-control " />

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-outline btn-primary" type="submit">
                                        <?php echo "ویرایش"; ?>
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
    