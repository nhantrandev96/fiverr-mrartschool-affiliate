<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/bootstrap.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/font-awesome.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/icon-font.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/material-design-iconic-font.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/animate.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/hamburgers.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/animsition.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/select2.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/daterangepicker.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/util.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/') ?>/main.css?v=<?= av() ?>">

    <script src="<?= base_url('assets/login/js/jquery-3.2.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/login/js/popper.js') ?>"></script>
    <script src="<?= base_url('assets/login/js/bootstrap.min.js') ?>"></script>

    <style type="text/css">body {padding-top: 5rem;}</style>
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"> <a class="nav-link" href="<?php echo base_url() ?>">Home</a> </li>
          <li class="nav-item active"> <a class="nav-link" href="<?php echo base_url('term-condition') ?>">Term & Conditions</a> </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
        <h1><?= $page['heading'] ?></h1><br>
        <?= $page['content'] ?>

    </main><!-- /.container -->
    <div style="display:none;"><a href="https://affiliatepro.org">affiliate pro</a></div>
  </body>
</html>
