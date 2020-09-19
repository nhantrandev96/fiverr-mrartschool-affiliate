<?php if( current_url() != site_url('/p/login') && current_url() != site_url('/p/forget-password') ){ ?>

<!-- Register -->
<section class="register-section" style="background-image: url(<?= base_url('assets/login/multiple_pages') ?>/img/4.png)">
    <div class="container py-5">
        <div class="section-title mb-5">
            <h2>Want to explore more?</h2>
            <p>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            </p>
        </div>
        <div class="text-center">
            <a href="" class="slide-button btn btn-primary rounded-pill btn-lg px-4">Register Today</a>
        </div>
    </div>
</section>

<!-- /Register -->

<footer class="py-2 py-lg-4">
    <div class="container">
        <div class="row border-bottom py-3">
            <div class="col-md-6">
                <h6>About your Website</h6>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </div>
            <div class="col-md-3 col-6">
                <h6>Primary Links</h6>
                <ul class="m-0 pl-3">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Videos</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-6">
                <h6>Secondary Links</h6>
                <ul class="m-0 pl-3">
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Demo</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
        </div>
        <div class="row py-3">
            <div class="col-md-6">
                <p class="copyright"><span>Copyright ©</span><span> <?= $footer ?></span></p>
            </div>
            <div class="col-md-6">
                <ul class="terms-lang">
                    <li>
                        <a class="d-inline" href="<?= base_url('/p/terms-of-use') ?>">Terms</a>
                    </li>
                    <li>
                        <?php if($store['language_status']){ ?>
                        <div class="language">
                          <div class="dropdown">
                            <?= $LanguageHtml ?>
                          </div>
                        </div>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /.container -->
  </footer>
  
  <?php } ?>
  
    <script>
        $(document).ready(function(){
            var homeSwiper = new Swiper('.main-swiper', {
              autoHeight: true,
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.main-swiper-pagination',
                clickable: true,
              },
              navigation: {
                nextEl: '.main-swiper-button-next',
                prevEl: '.main-swiper-button-prev',
              },
            });
            
            var cleintsSwiper = new Swiper('.clients-swiper', {
              autoHeight: true,
              spaceBetween: 20,
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.clients-swiper-pagination',
                clickable: true,
              },
              navigation: {
                nextEl: '.clients-swiper-button-next',
                prevEl: '.clients-swiper-button-prev',
              },
            });
            
            var loginSwiper = new Swiper('.login-swiper', {
              autoHeight: true,
              spaceBetween: 20,
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.login-swiper-pagination',
                clickable: true,
              },
              navigation: {
                nextEl: '.login-swiper-button-next',
                prevEl: '.login-swiper-button-prev',
              },
            });
            
            checkHeader();
        });
        
        $(window).scroll(function() {
            checkHeader();
        });
        
        function checkHeader(){
            if($(window).scrollTop() <= 80){
                $('.main-navbar').removeClass('active');
            }else{
                $('.main-navbar').addClass('active');
            }
        }
    </script>
</body>

</html>