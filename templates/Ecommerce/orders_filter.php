<table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>                                    
                                    <th>Details</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($orders) > 0){
                                    foreach($orders as $key => $order){
                                ?>
                                    <tr>
                                        <td width="5%"><?php e($key+1); ?>.</td>
                                        <td>
											<b>Name: </b><?php e(ucwords($order->customer_name)); ?><br />
                                            <b>Contact: </b><?php e(isCheckVal($order->customer_contact)); ?><br />
                                            <b>Address: </b><?php e(nl2br(isCheckVal($order->customer_address))); ?><br />
                                            <b>Approx. Delivery Date: </b><?php e(isCheckVal($order->delivery_date)); ?><br />
                                            <?php e(date("F jS, Y h:i A",$order->created)); ?>
                                        </td>  
                                        <td style="text-align:center;" width="5%">
											<?php $status = $order->status == 1 ? "Completed" : "Pending"; ?>
                                            <?php $class = $order->status == 1 ? "success" : "danger"; ?>
                                            <a id="statusBtn_<?= $order->id ?>" onclick="changeStatus('Orders','<?= $this->encryptData($order->id); ?>','<?= $order->status ?>','<?= $order->id; ?>');" class="btn btn-<?php e($class);?>"><?php e($status);?></a>
                                            <input type="hidden" id="current_status<?= $order->id ?>" value="<?= $order->status ?>" />
                                        </td>                                  
                                        <td width="35%">
                                        <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-order/'.base64_encode($this->encryptData($order->id))));?>" title="View" class="btn btn-primary">View</a>
                                        <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-order/'.base64_encode($this->encryptData($order->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                        <a href="javascript:void(0);" onclick="deleteRecord('Orders','<?php e(base64_encode($this->encryptData($order->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php }
                                	}else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="6">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($orders->count() > 0){ ?>
                            <tbody>
                            	<tr>
                                	<td align="center" colspan="6">
                                    <ul class="pagination">
                                    <?php
                                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Ecommerce', 'action' => 'ordersFilter'))));?>
                                        <?php echo $this->Paginator->first('First'); ?>
                                        <?php echo $this->Paginator->numbers(); ?>
                                        <?php echo $this->Paginator->last('Last'); ?>
                                    </ul>
                                    </td>
                                </tr>
                            </tbody>
                            <?php } ?>
                       	</table>