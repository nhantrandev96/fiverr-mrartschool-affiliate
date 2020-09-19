
<!-- page content -->
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= __('user.update_user') ?></h2>
                <div class="clearfix"></div>
                 <?php flashMsg($this->session->flashdata('flash')); ?>
            </div>
             <div class="col-md-12">
              <div class="x_panel">
                <div class="x_content">
                
                    <div class="x_content">
                    <br>
					 <form id="demo-form2" name="myform" novalidate class="form-horizontal form-label-left" action="<?php echo base_url() ?>admin/editUser/<?php echo $users[0]->id; ?> " method="post">
					 <input type="hidden" name="id" value="<?php echo $users[0]->id; ?>">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.fisrt_name') ?><span class="required">*</span> </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control" required name="firstname" placeholder="First Name" value="<?php echo $users[0]->firstname; ?>">
							</div>
						 </div>
					  		<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.last_name') ?> <span class="required">*</span> </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control" required name="lastname" placeholder="Last Name" value="<?php echo $users[0]->lastname; ?>">
							</div>
						 </div>
						 	 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.username') ?> <span class="required">*</span> </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="email" class="form-control" required name="username" placeholder="Username" value="<?php echo $users[0]->username; ?>" readonly="true">
							</div>
						 </div>
						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?= __('user.email') ?> <span class="required">*</span> </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="email" class="form-control" required name="email" placeholder="Email" value="<?php echo $users[0]->email; ?>" readonly="true">
							</div>
						 </div>
              
					  
						
                      <!--<div class="ln_solid"></div>-->
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                          <button type="submit" class="btn btn-success" ><?= __('user.update') ?></button> </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
