<div class="row">
            <div class="col-lg-12 col-md-12">
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
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        
                          <div class="row top-panel"> <span class="col-sm--2">
									<div class="share-store-list">
										<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("admincontrol/addclients") ?>"><?= __('admin.add_client') ?></a>
									</div>
								</span> 
								</div>
								</br>
								
                        <div class="table-rep-plugin">
                            
                            <?php if ($clientslist == null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_clients') ?></h3></div>
                                <?php }
                                else {?>
                                
                                
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="tech-companies-1" class="table  table-striped">
                                    <thead>
                                        <tr>
                                            <th data-priority="1"><?= __('admin.name') ?></th>
                                            <th data-priority="3"><?= __('admin.refer_user') ?> </th>
                                            <th data-priority="1"><?= __('admin.email') ?></th>
                                            <th data-priority="1"><?= __('admin.phone') ?></th>
                                            <th data-priority="3"><?= __('admin.username') ?></th>
                                            <th data-priority="1"><?= __('admin.sales') ?></th>
                                            <th data-priority="3"><?= __('admin.type') ?></th>
                                            <th data-priority="3"><?= __('admin.action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($clientslist as $clients){ ?>
                                            <tr>
                                                <td><?php echo $clients['firstname'];?> <?php echo $clients['lastname'];?></td>
                                                <td><?php echo $clients['ref_user'];?></td>
                                                <td class="txt-cntr"><?php echo $clients['email'];?></td>
                                                <td class="txt-cntr"><?php echo $clients['PhoneNumber'];?></td>
                                                <td class="txt-cntr"><?php echo $clients['username'];?></td>
                                                <td class="txt-cntr"><?php echo $clients['total_sale'] ?> / <?php echo c_format($clients['amount']); ?></td>
                                                <td class="txt-cntr"><?php echo __('admin.type_'. $clients['type']);?></td>
                                                <td class="txt-cntr"> <a class="btn btn-danger" onclick="return confirm(' You will lost all data releted to this client .Are you sure you want to delete?');" href="<?php echo base_url();?>admincontrol/deleteusers/<?php echo $clients['id'];?>/client"><i class="fa  fa-trash-o cursors" aria-hidden="true" style="color:#ffffff"></i></a> <a class="btn btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url();?>admincontrol/addclients/<?php echo $clients['id'];?>"><i class="fa fa-edit cursors" aria-hidden="true" style="color:#ffffff"></i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
  
<script type="text/javascript" async="">
    function shareinsocialmedia(url) {
        window.open(url, 'sharein', 'toolbar=0,status=0,width=648,height=395');
        return true;
    }
</script>