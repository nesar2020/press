
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
        <div class="container-fluid">
            
            <div class="row ">
                
                <div class="col-sm-4 comp-grid">
                    <h3 class="record-title">اسعار</h3>
                    
                </div>
                
                <div class="col-sm-3 comp-grid">
                    
                    <a  class="btn btn-outline btn-primary btn-block" href="<?php print_link("currencies/add") ?>">
                        <i class="material-icons">add</i>
                        ارز جدید
                    </a>
                    
                </div>
                
                <div class="col-sm-5 comp-grid">
                    
                    <form  class="search" method="get">
                        <div class="input-group">
                            <input value="<?php echo get_query_str_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="جستجو" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                    
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <?php
                            if(!empty($field_name) || !empty($field_name)){
                            ?>
                            <hr class="sm d-block d-sm-none" />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <?php
                                    if(!empty($field_name)){
                                    ?>
                                    <li class="breadcrumb-item"><a class="text-capitalize" href="<?php print_link('currencies') ?>"><?php echo $field_name ?></a></li>
                                    <li  class="breadcrumb-item active text-capitalize"><?php echo urldecode($field_value) ?></li>
                                    <?php 
                                    }   
                                    ?>
                                    <?php
                                    if(!empty($_GET['search'])){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-capitalize" href="<?php print_link('currencies') ?>">بحث</a>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize"> <strong><?php echo get_value('search'); ?></strong></li>
                                    <?php
                                    }
                                    ?>
                                    
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
            <div class="container-fluid">
                
                <div class="row ">
                    
                    <div class="col-md-8 comp-grid">
                        
                        <?php $this :: display_page_errors(); ?>
                        
                        <div  class="card animated fadeIn">
                            <div id="currencies-list-records">
                                
                                <?php
                                if(!empty($records)){
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>

                                                <th > ای دی</th>
                                                <th > ارز</th>
                                                
                                                <th class="td-btn"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                            $counter = 0;
                                            foreach($records as $data){
                                            $counter++;
                                            ?>
                                            <tr>

                                                    <td><a><?php echo $data['id']; ?></a></td>
                                                    <td> <?php echo $data['currency']; ?> </td>
                                                    
                                                    
                                                    <th class="td-btn">

                                                        <a class="btn btn-sm btn-info " title="تحرير هذا السجل"
                                                           href="<?php print_link('currencies/edit/'.$data['id']); ?>">
                                                            <i class="material-icons">edit</i> ویرایش
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
                                    if( $show_footer == true ){
                                    ?>
                                    <div class="card-footer">
                                        <div class="row">   
                                            <div class="col-sm-3">  

                                            </div>
                                            <div class="col">   
                                                
                                                <?php
                                                if( $show_pagination == true ){
                                                $pager = new Pagination($total_records,$record_count);
                                                $pager->page_name='currencies';
                                                $pager->show_page_count=true;
                                                $pager->show_record_count=true;
                                                $pager->show_page_limit=true;
                                                $pager->show_page_number_list=true;
                                                $pager->pager_link_range=5;
                                                
                                                $pager->render();
                                                }
                                                ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    }
                                    else{
                                    ?>
                                    <div class="text-muted animated bounce">
                                        <h4><i class="material-icons">block</i>رکوردی یافت نشد!!!</h4>
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
        