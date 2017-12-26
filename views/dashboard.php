<?php
$account_info = $db_config->get('config');

//Check password protection
if($account_info->enable_password_protection === '1' && (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == '')){
	header("Location: " . $base_url . "login");
}
?>

<div class="row">
	<?php
	foreach($getInfo['return']['balance'] as $key=>$val){
		if($val > 0 && $key != 'idr'){
			array_push($list_of_currency, $key);
			?>
			<div class="col-md-6 col-xs-12">
				<div class="boxed">
					<iframe id="<?php echo $key;?>idr_iframe" src="https://vip.bitcoin.co.id/chart/<?php echo $key;?>idr" style="width:100%;"></iframe>
					<input type="hidden" id="qty_<?php echo $key;?>" value="<?php echo $val;?>">
					<?php echo $val . ' ' . strtoupper($key);?> 
					&nbsp; X &nbsp; 
					<span id="idr_value_<?php echo $key;?>">(loading...)</span>
					&nbsp; = &nbsp; 
					<span id="times_idr_value_<?php echo $key;?>">(loading...)</span>
					<input id="hidden_idr_value_<?php echo $key;?>" type="hidden" value="">
					
					<div class="float-right">
						<button type="button" class="btn btn-dark refresh-frame" data-frame-id="<?php echo $key;?>idr_iframe" data-toggle="tooltip" data-placement="bottom" title="Refresh this frame"><span class="oi oi-reload"></span></button>
						<button type="button" class="btn btn-dark transaction-list-btn" data-pair="<?php echo $key;?>_idr" data-toggle="tooltip" data-placement="bottom" title="Transaction List"><span class="oi oi-clock"></span></button>
					</div>
					
					<div class="clearfix"></div>
					<?php /*
					<div id="<?php echo $key;?>idr_table">
					<?php
					$orderHistory = btcid_query('orderHistory', array('pair' => $key . '_idr'), $api_key, $secret_key);
					if($orderHistory->success == 1){
					?>
					<table>
					<tr>
					<td>Time</td>
					<td>Type</td>
					<td>Price</td>
					<td><?php echo strtoupper($key); ?></td>
					<td>IDR</td>
					<td>Status</td>
					</tr>
					</table>
					<?php
				}
				?>
				</div>
				*/ ?>
			</div>
		</div>
		<?php
	}
}
?>
</div>

<div class="modal fade" id="transactionListModal" tabindex="-1" role="dialog" aria-labelledby="transactionListModalLabel" aria-hidden="true" data-pair="">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="transactionListModalLabel">Transaction List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Content
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="pendingListModal" tabindex="-1" role="dialog" aria-labelledby="pendingListModal" aria-hidden="true" data-pair="">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pendingListModalLabel">Pending Order List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Content
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
