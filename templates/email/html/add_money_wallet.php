<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php e(SITE_TITLE);?></title>
  </head>
<body style="-webkit-text-size-adjust: 100%; -moz-text-size-adjust: none;-ms-text-size-adjust: 100%;">
<table style="margin:0 auto; width:800px; border:1px solid #d3d3d3; padding:10px 15px 10px; box-sizing:border-box; border-collapse: separate; border-spacing:0;" aria-describedby="mydesc">
<tr style="display:none; height:0px; width:0px;">
<th scope="col" colspan="2"></th>
</tr>
  <?php e($this->Element('email_data/email_header'));
    $wallet = $sendEmailData['userData'];
  ?>
  <h2 style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:18px;font-weight:500; text-align:center">WALLET RECEIPT</h2>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:14px;font-weight:500;">Thank you for using Mrcoconut wallet. Please quote your Transaction ID for any queries relating to this transaction in future. </td>
  </tr>
  
  <tr>
    <td colspan="2" style="padding:5px 7px 5px 25px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Dear, <?php e(ucwords($wallet['name'])); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:5px 7px 5px 25px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Transaction ID: <?php e(strtolower($wallet['payment_id'])); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:5px 7px 5px 25px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Transaction Type: <?php e($wallet['type']); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:5px 7px 5px 25px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Amount: <?php e($wallet['amount']); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:5px 7px 5px 25px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Date and Time: <?php e(date("F jS, Y h:i A", $wallet['created'])); ?> </td><br>
  </tr>
   <?php e($this->Element('email_data/email_footer_text'));?>
 </table>
</body>
</html>