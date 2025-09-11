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
    $booking = $sendEmailData['userData']; 
  ?>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:14px;font-weight:500;">Thanks for shopping! <br>You'll find a summary of your recent purchase below.</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Dear <?php e(ucwords($booking['customer_name'])); ?>,</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Invoice ID: <?php e(strtolower($booking['invoice_id'])); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Price: <?php e(number_format($booking['total'],2)); ?> INR</td>
  </tr> 
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Payment Method: <?php e($booking['payment_method']); ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Order Date: <?php e(date("F jS, Y h:i A",$booking['created'])); ?></td>
  </tr>  
  <?php if(isset($booking['message']) && !empty($booking['message'])){?>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Message: <?php e(nl2br($booking['message'])); ?></td>
  </tr>
  <?php } ?>
  <?php if(isset($booking['notes']) && !empty($booking['notes'])){?>
  <tr>
    <td colspan="2" style="padding:20px 15px 10px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400;">Message: <?php e(nl2br($booking['notes'])); ?></td>
  </tr>
  <?php } ?>
   <?php e($this->Element('email_data/email_footer_text'));?>
 </table>
</body>
</html> 