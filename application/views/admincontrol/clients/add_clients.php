<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
         <div class="row">
            <div class="col-12">
               <div class="card m-b-30">
                  <div class="card-body">
                     <?php if($this->session->flashdata('success')){?>								
	                     <div class="alert alert-success alert-dismissable my_alert_css">									
	                     	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>								
	                     	<?php echo $this->session->flashdata('success'); ?> 
	                     </div>
                     <?php } ?>	
                     <?php if($this->session->flashdata('error')){?>								
	                     <div class="alert alert-danger alert-dismissable my_alert_css">									
	                     	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>								
	                     	<?php echo $this->session->flashdata('error'); ?> 
	                     </div>
                     <?php } ?>	
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.first_name') ?></label>								
                        <div class="col-sm-9">									
                        	<input name="firstname" value="<?php echo $client->firstname; ?>" class="form-control" required="required" type="text">								
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.last_name') ?></label>								
                        <div class="col-sm-9">									
                        	<input name="lastname" class="form-control" value="<?php echo $client->lastname; ?>" required="required" type="text">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.email') ?> </label>								
                        <div class="col-sm-9">									
                        	<input name="email" id="email" class="form-control" value="<?php echo $client->email; ?>" required="required" type="email">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.username') ?> </label>								
                        <div class="col-sm-9">									
                        	<input name="username" id="username" class="form-control" value="<?php echo $client->username; ?>" type="text">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.status') ?> </label>
                        <div class="col-sm-9">
                           <select name="status" class="form-control">
                              <option value="0"><?= __('admin.disable') ?></option>
                              <option value="1" <?= $client->status == '1' ? 'selected' : '' ?> ><?= __('admin.enable') ?></option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.password') ?> </label>								
                        <div class="col-sm-9">									
                        	<input name="password" id="password" class="form-control" value="" <?php echo empty($client->email) ?  'required="required"' : '';?> type="text">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.confirm_password') ?> </label>								
                        <div class="col-sm-9">									
                        	<input name="cnfrm_password" id="cnfrm_password" class="form-control" value="" type="text">								
                        </div>
                     </div>
                     <button class="btn btn-block btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>						
                  </div>
               </div>
            </div>
         </div>
      </form>