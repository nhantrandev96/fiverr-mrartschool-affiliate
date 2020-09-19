<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title m-0"><?= $title ?></h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="setting-form">
                <input type="hidden" value="<?= $setting_key ?>" name='setting_key'>

                <div class="row">
                    <div class="col-sm-12">
                        <!-- <div class="card">
                            <div class="card-header">
                                <h6 class="card-title m-0">Admin Setting</h6>
                            </div>
                            <div class="card-body"> -->
                                <?php foreach ($settings as $key => $d) { ?>
                                    <?php 
                                        $settingK = 'admin_'.$key; 
                                        $value = isset($db_value[$settingK]) && $db_value[$settingK] == "1" ? 1 : 0;
                                    ?>

                                    <?php if($d['type'] == 'switch') { ?>
                                        <div class="form-group setting-switch">
                                            <div>
                                                <label>
                                                    <input type="radio" <?= $value == '1' ? 'checked' : '' ?> name="settings[<?= $settingK ?>]" value='1'> <span>Yes</span>
                                                </label>
                                                <label>
                                                    <input type="radio" <?= $value == '0' ? 'checked' : '' ?> name="settings[<?= $settingK ?>]" value='0'> <span>No</span>
                                                </label>
                                            </div>
                                            <label class="control-label"><?= $d['name'] ?></label>
                                        </div>
                                    <?php } else if($d['type'] == 'number') { ?>
                                        <div class="form-group">
                                            <label class="control-label"><?= $d['name'] ?></label>
                                            <input class="form-control allow-number" type="text" name="settings[<?= $settingK ?>]" value='<?= isset($db_value[$settingK]) ? $db_value[$settingK] : '' ?>' >
                                            <?php if(isset($d['help'])){ ?>
                                                <span class="help-block"><?= $d['help'] ?></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <!-- </div>
                        </div> -->
                    </div>

                    <!-- <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title m-0">User Setting</h6>
                            </div>
                            <div class="card-body">
                                <?php foreach ($settings as $key => $name) { ?>
                                    <?php 
                                        $settingK = 'user_'.$key; 
                                        $value = isset($db_value[$settingK]) && $db_value[$settingK] == "1" ? 1 : 0;
                                    ?>
                                    <div class="form-group setting-switch">
                                        <div>
                                            <label>
                                                <input type="radio" <?= $value == '1' ? 'checked' : '' ?> name="settings[<?= $settingK ?>]" value='1'> <span>Yes</span>
                                            </label>
                                            <label>
                                                <input type="radio" <?= $value == '0' ? 'checked' : '' ?> name="settings[<?= $settingK ?>]" value='0'> <span>No</span>
                                            </label>
                                        </div>
                                        <label class="control-label"><?= $name ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div> -->

                </div>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary save-settings" >Save</button>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(".save-settings").on('click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('setting/saveSetting') ?>',
            type:'POST',
            dataType:'json',
            data: $("#setting-form").serialize(),
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.btn("reset"); },
            success:function(json){
                if(json['success']){
                    $("#setting-widzard").modal("hide");

                    <?php if($setting_key == 'live_dashboard'){ ?>
                        window.location.reload();
                    <?php  } ?>
                    <?php if($setting_key == 'live_log'){ ?>
                        
                        settings_clear = true;
                        last_id_integration_logs = 0;
                        last_id_integration_orders = 0;
                        last_id_newuser = 0;
                        last_id_notifications = 0;
                        dataTableLiveLog.fnClearTable();

                        getDashboard(false, false,'clearlog');
                    <?php } ?>
                }
            },
        })
    })
</script>