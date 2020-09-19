        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><h3>Default Template</h3></div>
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="row front-design-form vc">
                                <div class="col-sm-2">
                                    <div>
                                        <ul class="card1-list">
                                            <li data-show='content' class="active">Home Content</li>
                                            <li data-show='about-content'>About Content</li>
                                            <li data-show='heading'>Heading Settings</li>
                                            <li data-show='inputs'>Inputs Settings</li>
                                            <li data-show='bgcolor'>Background Color</li>
                                            <li data-show='footersettings'>Footer Settings</li>
                                            <li data-show='buttons'>Buttons</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div>
                                        <div class="card1 active" data-toggle='content'>
                                            <div class="card1-header">Content</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    
                                                    <tr>
                                                        <td><?= __('admin.content') ?></td>
                                                        <td><textarea name="loginclient[content]" class="form-control summernote"><?php echo $loginclient['content']; ?></textarea></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td><?= __('admin.logo') ?></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-6 p-4">
                                                                    <?php $img = $loginclient['logo'] ? base_url('assets/images/site/'. $loginclient['logo']) : base_url('assets/vertical/assets/images/users/avatar-1.jpg'); ?>
                                                                    <img style="width: 150px;" src="<?= $img ?>" class='img-responsive'>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <input type="file" name="loginclient_logo">
                                                                </div>
                                                        </td>
                                                    </tr> -->
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card1" data-toggle='about-content'>
                                            <div class="card1-header">About Content</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <tr>
                                                        <td>About Content</td>
                                                        <td><textarea name="loginclient[about_content]" class="form-control summernote"><?php echo $loginclient['about_content']; ?></textarea></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card1" data-toggle='heading'>
                                            <div class="card1-header">Heading Settings</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <tr>
                                                        <td><?= __('admin.heading') ?></td>
                                                        <td><input  name="loginclient[heading]" value="<?php echo $loginclient['heading']; ?>" class="form-control"  type="text"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Color</td>
                                                        <td><input type="text" name="loginclient[heading_color]" value="<?= $loginclient['heading_color'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card1" data-toggle='inputs'>
                                            <div class="card1-header">Inputs Settings</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <tr>
                                                        <td>Text Color</td>
                                                        <td><input type="text" name="loginclient[input_text_color]" value="<?= $loginclient['input_text_color'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Background Color</td>
                                                        <td><input type="text" name="loginclient[input_bg_color]" value="<?= $loginclient['input_bg_color'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Label Color</td>
                                                        <td><input type="text" name="loginclient[input_label_color]" value="<?= $loginclient['input_label_color'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card1" data-toggle='bgcolor'>
                                            <div class="card1-header">Background Color</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <tr>
                                                        <td>Left Side Background Color</td>
                                                        <td><input type="text" name="loginclient[bg_left]" value="<?= $loginclient['bg_left'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Right Side Background Color</td>
                                                        <td><input type="text" name="loginclient[bg_right]" value="<?= $loginclient['bg_right'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card1" data-toggle='footersettings'>
                                            <div class="card1-header">Footer Settings</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <!-- <tr>
                                                        <td>Text</td>
                                                        <td><input type="text" name="loginclient[footer]" value="<?= $loginclient['footer'] ?>" class='form-control'></td>
                                                    </tr> -->
                                                    <tr>
                                                        <td>Background Color</td>
                                                        <td><input type="text" name="loginclient[footer_bf]" value="<?= $loginclient['footer_bf'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Text Color</td>
                                                        <td><input type="text" name="loginclient[footer_color]" value="<?= $loginclient['footer_color'] ?>" class='form-control color-input'></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card1" data-toggle='buttons'>
                                            <div class="card1-header">Buttons</div>
                                            <div class="card1-body">
                                                <table class="table-striped table">
                                                    <tr>
                                                        <td>Send Mail Button</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_sendmail_bg]" value="<?= $loginclient['btn_sendmail_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_sendmail_color]" value="<?= $loginclient['btn_sendmail_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Back To Login Button</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_backlogin_bg]" value="<?= $loginclient['btn_backlogin_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_backlogin_color]" value="<?= $loginclient['btn_backlogin_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Forgot Password</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_forgotlink_bg]" value="<?= $loginclient['btn_forgotlink_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_forgotlink_color]" value="<?= $loginclient['btn_forgotlink_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sign In Button</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_signin_bg]" value="<?= $loginclient['btn_signin_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_signin_color]" value="<?= $loginclient['btn_signin_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sign Up Button</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_signup_bg]" value="<?= $loginclient['btn_signup_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_signup_color]" value="<?= $loginclient['btn_signup_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Register Submit Button</td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Background Color</td>
                                                                    <td><input type="text" name="loginclient[btn_registersubmit_bg]" value="<?= $loginclient['btn_registersubmit_bg'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Text Color</td>
                                                                    <td><input type="text" name="loginclient[btn_registersubmit_color]" value="<?= $loginclient['btn_registersubmit_color'] ?>" class='form-control color-input'></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="form-group text-right">
                                <button class="btn btn-sm btn-primary"><?= __('admin.save_settings') ?></button>
                            </div>
                                
                        </form>
                    </div>
                </div>
                    
                       
            </div>
        </div>

<script>
    var editor = null;

    apply_color($(".color-input"))
    $(".card1-list li").on('click',function(){
        $(".card1-list li").removeClass("active");
        $(this).addClass("active");

        $(".card1[data-toggle]").hide();
        $(".card1[data-toggle='"+ $(this).attr("data-show") +"']").show();

        setCookie('design_last_tab', $(this).attr("data-show"));
    })

    $(document).on('ready',function(){
        if(getCookie('design_last_tab') != ''){
            $(".card1-list li[data-show='"+ getCookie('design_last_tab') +"']").trigger('click');
        }
    })

    $(document).on('ready',function(){
        $('#image_manager .model_image').load("<?= $image_manager_url ?>");
        $(".summernote").summernote()
    })
//--></script>

    