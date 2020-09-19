
<tr>
  <td align="center" style="padding:20px 23px 0 23px">
    <table width="600" style="background-color:#FFF; margin:0 auto; border-radius:5px; border-collapse:collapse">
      <tbody>
        <tr>
          <td align="center">
            <table width="500" style="margin:0 auto">
              <tbody>
                <tr>
                  <td align="center" style="padding:40px 0 35px 0">
                    <a href="<?= $base_url ?>" target="_blank" style="color:#128ced; text-decoration:none;outline:0;"><img alt="" src="<?= base_url('assets/images/world.png') ?>" style='    width: 288px;' border="0"></a>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-family:'Roboto', Arial !important">
                     <h2 style="margin:0; font-weight:bold; font-size:40px; color:#444; text-align:center; font-family:'Roboto', Arial !important">Wallet</h2>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="padding:40px 0 35px 0;font-size: 20px;">
                    Your withdrawal request status change to <?= $status ?>
                  </td>
                </tr>
                <tr>
                <td align="center" cellspacing="0" style="padding:0 0 30px 0; vertical-align:middle">
                  <table width="550" style="border-collapse:collapse; background-color:#FaFaFa; margin:0 auto; border:1px solid #E5E5E5">
                    <tbody>
                      <tr>
                        <td width="276" style="vertical-align:top; border-right:1px solid #E5E5E5">
                          <table style="width:100%; border-collapse:collapse">
                            <tbody>
                              <tr>
                                <td style="vertical-align:top; padding:18px 18px 8px 23px; font-family:'Roboto', Arial !important">
                                  <p style="font-size:16px; color:#333333; text-transform:uppercase; font-weight:900; margin:0; font-family:'Roboto', Arial !important">
                                    Summary:
                                  </p>
                                </td>
                              </tr>
                              <tr style="">
                                <td style="vertical-align:top; padding:0 18px 18px 23px">
                                  <table width="100%" style="border-collapse:collapse">
                                    <tbody>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            Status #:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= $status ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            Amount #:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= c_format($wallet->amount) ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            Comment #:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= $wallet->comment ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            Type #:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= str_replace("_", " ", $wallet->type) ?>
                                          </p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
                
              </tbody>
            </table>
          </td>
        </tr>
        
      </tbody>
    </table>
  </td>
</tr>
      