<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Affiliate Program</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>assets/css/base.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/newlogin.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/plugins/fonts/css/fa.css?v=<?= av() ?>" rel="stylesheet">
</head>
<body>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reset Password</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($this->session->flashdata('error'))){?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php } ?>
                    <?php if(!empty($this->session->flashdata('success'))){?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label class="control-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Confirm Password</label>
                            <input type="password" name="conf_password" class="form-control">
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Change</button>
                            <a href="<?= base_url() ?>" class="btn btn-primary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="<?PHP echo base_url();?>assets/js/jquery-1.10.2.min.js"></script>
<script src="<?PHP echo base_url();?>assets/js/bootstrap.min.js"></script>
</body>
</html>
