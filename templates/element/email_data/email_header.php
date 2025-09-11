<tr>
	<th scope="col" style="text-align:left; padding:10px 15px;"><a title="" href="<?php e(SITEURL);?>">
		<?php
		#get admin data
		$adminData = $this->Admin->getSettings();
		if(isset($adminData->id) && !empty($adminData->logo)){
			$imgPath = SITEURL.'img/logos/'.$adminData->logo;
			e($this->Html->image($imgPath, array('title'=>SITE_TITLE, 'alt'=> SITE_TITLE, 'width' => '100' )));
		}
		?>
	</a></th>
	<td style="vertical-align:middle; font-family:Verdana, Geneva, sans-serif; color:#424242; font-size:13px; text-align:right; font-weight:400; line-height: 20px; padding:10px 15px;"><strong>Date:</strong> <?php e(date('F dS, Y'));?></td>
</tr>
<tr>
	<td colspan="2" style="border-bottom:6px solid #424242; padding:0px;"></td>
</tr>
