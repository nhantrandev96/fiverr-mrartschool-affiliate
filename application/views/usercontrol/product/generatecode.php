<link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
<script type="text/javascript" src="<?= base_url('assets/integration/prism/clipboard.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
<?php 
    function ___h($text,$lan){
        $text = implode("\n", $text);
        $text = htmlentities($text);
        $text = '<div class="lang-copy" ><div class="copy">copy</div><pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre></div>';
        return $text;
    }

    $base_url  = base_url();
?>

<p class="m-b-30"><?= __('user.copy_instruction') ?></p>

<?php 
	$productLink = base_url('store/'. base64_encode($user_id) .'/product/'.$getProduct['product_slug'] );
	$product_featured_image = $getProduct['product_featured_image'] != '' ? $getProduct['product_featured_image'] : '' ; 
	$product_featured_image = base_url('assets/images/product/upload/thumb/'. $product_featured_image);


	$code = array();
	$code[] = '<a href="'. $productLink .'">';
	$code[] = '<h3>'. ($getProduct['product_name'] ? $getProduct['product_name'] : '') .'</h3>';
	$code[] = '<img src="'. $product_featured_image .'" width="200" height="200" border="0" class="img-responsive" />';
	$code[] = '</a>';

	echo ___h($code,'html');
?>

<script type="text/javascript">
	$(document).ready(function(){
        setTimeout(function(){
            $(".token.string").each(function(){
                var c = $(this).text().replace(/[^a-z_0-9\s]/gi, '')
                $(this).addClass(c)
            });
            $(".auto-fill-filed input").trigger("keyup");

            const clipboard = new Clipboard('.copy', {
              target: (trigger) => {
                return trigger.nextElementSibling;
              }
            });

            clipboard.on('success', (event) => {
              event.trigger.textContent = 'copied!';
              setTimeout(() => {
                event.clearSelection();
                event.trigger.textContent = 'copy';
              }, 2000);
            });

        }, 1000);
    })
</script>
