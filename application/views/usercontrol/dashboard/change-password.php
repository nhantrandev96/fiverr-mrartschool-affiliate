<!-- page content -->
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel m-t-30">
            <div class="col-md-12">
                <div class="card">
                	<div class="card-header">
                		<h4 class="card-title"><?= __('user.update_password') ?></h4>
                	</div>
                	<div class="card-body">
                		<form id="demo-form2" name="myform" novalidate class="form-horizontal form-label-left" action="<?php echo base_url() ?>usercontrol/changePassword" method="post">
                			<div class="col-sm-6">
		                		<?php if (isset($validate_err)) { ?>
		                			<div class="alert alert-danger"><?= $validate_err ?></div>
		                		<?php } ?>
		                		<?php flashMsg($this->session->flashdata('flash')); ?>
                			</div>
                			<div class="clearfix"></div>
						   <div class="form-group">
						      <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.old_password') ?> <span class="required">*</span>
						      </label>
						      <div class="col-md-6 col-sm-6 col-xs-12">
						         <input type="password" class="form-control" required name="old_pass" placeholder="Password" value="">
						      </div>
						      <?php echo form_error('old_pass'); ?>
						   </div>
						   <div class="form-group">
						      <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.password') ?> <span class="required">*</span>
						      </label>
						      <div class="col-md-6 col-sm-6 col-xs-12">
						         <input type="password" class="form-control" required name="password" placeholder="Password" value="">
						      </div>
						      <?php echo form_error('password'); ?>
						   </div>
						   <div class="form-group">
						      <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.confirm_password') ?> <span class="required">*</span>
						      </label>
						      <div class="col-md-6 col-sm-6 col-xs-12">
						         <input type="password" class="form-control" required name="conf_password" placeholder="Confirm Password" value="">
						      </div>
						      <?php echo form_error('conf_password'); ?>
						   </div>
						   <!--<div class="ln_solid"></div>-->
						   <div class="form-group">
						      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
						         <button type="submit" class="btn btn-success" ><?= __('user.update') ?></button> 
						      </div>
						   </div>
						</form>
                	</div>
                </div>
          	</div>
        </div>
    </div>
</div>
<!-- /page content -->

