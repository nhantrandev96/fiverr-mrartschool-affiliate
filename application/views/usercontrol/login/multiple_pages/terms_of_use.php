<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<div class="jumbotron jumbotron-fluid bg-light before-nav-spacer">
  <div class="container section-title">
    <h2>Terms & Conditions</h2>
    <p>This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
  </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h3><?= $page['heading'] ?></h3>

            <?= $page['content'] ?>
        </div>
    </div>
</div>
<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
