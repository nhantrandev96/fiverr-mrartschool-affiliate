<tr>
  <td align="center" style="padding:20px 23px 0 23px">
    <table width="604" style="background-color:#fff; margin:0 auto; border-radius:5px; border-collapse:collapse">
      <td align="center" bgcolor="#E3E3E3" style="padding:40px 0 35px 0">
        <a href="<?= $base_url ?>" target="_blank" style="color:#128ced; text-decoration:none;outline:0;">
          <?php
            $logo = base_url(trim($emailsetting['logo']) ? 'assets/images/site/'.$emailsetting['logo'] : 'assets/images/no_image_available.png');
          ?>
          <img alt="" src="<?= $logo ?>" style='width: 288px;' border="0">
        </a>
      </td>
      <tr>
        <td align="center">
          <table width="550" style="margin:0 auto">
            <tr>
              <td align="left" style="font-family:'Roboto', Arial !important">
                <?php echo $html ?>
              </td>
            </tr>
          </td>
        </tr>


