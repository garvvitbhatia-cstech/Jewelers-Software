<?php
$description = $emailTemplate->email_template_email_from;
if(isset($sendEmailData['mentorData']['id'])){
$mentorData = $this->Mentor->getMentorSingleRow($sendEmailData['mentorData']['id']);
$description = str_replace('[mentor_name]',ucwords($mentorData->mentor_name),$description);
$description = str_replace('[mentor_profile_url]',$mentorData->mentor_profile_url,$description);
}
?>
<tr>
<td colspan="2" style="padding:10px 15px;font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px;font-weight:400; line-height:20px;"><?php e(nl2br($description));?></td>
</tr>
