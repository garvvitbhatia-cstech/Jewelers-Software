<?php
$description = $emailTemplate->email_template_description;
$userDataVal  = 'userData';
if(isset($sendEmailData[$userDataVal]['id'])){
    $description = str_replace('[PASSWORD]',$this->User->decryptData($sendEmailData[$userDataVal]['password']),$description);
}
?>
<tr>
 <td colspan="2" style="padding:5px 15px 15px; font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px; line-height:20px;font-weight:400;"><?php e(nl2br($description));?></td>
</tr>
