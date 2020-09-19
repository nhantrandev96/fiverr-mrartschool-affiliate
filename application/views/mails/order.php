
      <tr>
        <td align="center" style="padding:20px 23px 0 23px">
          <table width="600" style="background-color:#FFF; margin:0 auto; border-radius:5px; border-collapse:collapse">
            <tbody>
              <tr>
                <td align="center">
                  <table width="500" style="margin:0 auto">
                    <tbody>
                      <tr>
                        <td align="center" style="font-family:'Roboto', Arial !important">
                          <h2 style="margin:0; font-weight:bold; font-size:40px; color:#444; text-align:center; font-family:'Roboto', Arial !important">Thanks for your order</h2>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="padding:15px 0 20px 0; font-family:'Roboto', Arial !important">
                          <p style="margin:0; font-size:18px; color:#000; line-height:24px; font-family:'Roboto', Arial !important">
                            You'll receive an email when your items are shipped. If you have any questions, call us any time at <?= $mob ?> or simply reply to this email. <?= $mail ?>
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="padding-bottom:30px">
                          <table style="width:255px; margin:0 auto;">
                            <tbody>
                              <tr>
                                <td width="255" style="background-color:#008AF1; text-align:center; border-radius:5px; vertical-align:middle; padding:13px 0">
                                  <a href="<?= $orderLink ?>" target="_blank" style="outline:0;color:#FFF; text-transform:uppercase; display:block; text-align:center; text-decoration:none; font-weight:bold; font-size:19px;">View Order Status</a>
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
                                            Order #:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= orderId($order['order_id']) ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            Order Date:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 5px 0; font-family:'Roboto', Arial !important">
                                            <?= $order['order_created_dt'] ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 10px 0; font-family:'Roboto', Arial !important">
                                            Order Total:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 10px 0; font-family:'Roboto', Arial !important">
                                            <?= c_format($order['total'] ) ?>
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 10px 0; font-family:'Roboto', Arial !important">
                                            Order Status:
                                          </p>
                                        </td>
                                        <td align="left" style="font-family:'Roboto', Arial !important">
                                          <p style="font-size:16px; color:#000; margin:0 0 10px 0; font-family:'Roboto', Arial !important">
                                            <?php echo $status[$order['order_status']]; ?>
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
              <tr>
                <td align="center" cellspacing="0" style="padding:0; vertical-align:middle">
                  <table width="550" style="border-collapse:collapse; background-color:#FaFaFa; margin:0 auto; border-bottom:1px solid #E5E5E5">
                    <tbody>
                      <tr>
                        <td width="380" align="left" style="padding:15px 0 15px 25px; font-family:'Roboto', Arial !important">
                          <p style="text-transform:uppercase;font-size:16px; color:#333333; margin:0; font-weight:400; font-family:'Roboto', Arial !important; ">
                            <span style="font-weight: 900;">  Items Ordered</span>
                          </p>
                        </td>
                        <td width="80" align="right" style="font-family:'Roboto', Arial !important;padding-right:10px;">
                          <p style="margin:0; font-size:14px; color:#333333;padding:0;font-family:'Roboto', Arial !important;text-align:right;">
                            PRICE</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td style=" font-family:'Roboto', Arial !important;padding:0;" align="center">
                  <table width="550" style="border-collapse:collapse;margin: 0 auto;border-bottom: 1px solid #EBEBEB">
                    <tbody>
                      <tr>
                        <td width="117" align="right" style="padding:24px 0 24px 10px; text-align:left;">
                          <?php $product_featured_image = $order['product_featured_image'] != '' ? $order['product_featured_image'] : 'no-image.jpg' ; ?>                         
                            <img style="width: 100%;" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $product_featured_image; ?>" border="0">
                        </td>
                        <td width="270" style="vertical-align:middle; padding:0 0 0 10px; font-family:'Roboto', Arial !important;">
                          <p style="font-size:16px; margin:0; color:#000; line-height:20px; font-family:'Roboto', Arial !important">
                            <?php echo $order['product_name'];?>
                          </p>
                          <p style="font-size:16px; margin:0; color:#000; line-height:20px; font-family:'Roboto', Arial !important">
                           <b>Commission Type</b> : <?php echo $order['commission_type'];?>
                          </p>
                          <p style="font-size:16px; margin:0; color:#000; line-height:20px; font-family:'Roboto', Arial !important">
                           <b>Commission Amount</b> : <?php echo c_format($order['commission']); ?>
                          </p>
                          
                        </td>
                        
                        <td align="center" width="80" style="font-family:'Roboto', Arial !important;padding:0 10px 0 0;">
                          <p style="font-size:18px; color:#bc0101; margin:0; font-family:'Roboto', Arial !important;text-align:center;font-weight:bold;text-align: right;">
                            <?php echo c_format($order['product_price']); ?>
                          </p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              
              <tr>
                <td align="center" style="padding-top:24px; padding-bottom:20px">
                  <table width="520" style="border-collapse:collapse">
                    <tbody>
                      <tr>
                        <td align="right" width="425" style="padding-bottom:15px; font-family:'Roboto', Arial !important">
                          <p style="font-size:18px; color:#000; margin:0; font-family:'Roboto', Arial !important">
                            Subtotal:
                          </p>
                        </td>
                        <td align="right" style="padding-bottom:15px; font-family:'Roboto', Arial !important">
                          <p style="font-size:18px; color:#000; margin:0; font-family:'Roboto', Arial !important">
                            <?php echo c_format($order['total']); ?>
                          </p>
                        </td>
                      </tr>
                      
                      <tr>
                        <td align="right" style="padding-bottom:15px; font-family:'Roboto', Arial !important">
                          <p style="font-size:18px; color:#000; font-weight:900; margin:0; font-family:'Roboto', Arial !important">
                            Order Total:
                          </p>
                        </td>
                        <td align="right" style="padding-bottom:15px; font-family:'Roboto', Arial !important">
                          <p style="font-size:18px; color:#bc0101; font-weight:bold; margin:0; font-family:'Roboto', Arial !important">
                            <?php echo c_format($order['total']); ?>
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
      