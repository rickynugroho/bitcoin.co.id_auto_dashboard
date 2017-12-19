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
					<iframe src="https://vip.bitcoin.co.id/chart/<?php echo $key;?>idr" style="width:100%;"></iframe>
					<input type="hidden" id="qty_<?php echo $key;?>" value="<?php echo $val;?>">
					<?php echo $val . ' ' . strtoupper($key);?> 
					&nbsp; X &nbsp; 
					<span id="idr_value_<?php echo $key;?>">(loading...)</span>
					&nbsp; = &nbsp; 
					<span id="times_idr_value_<?php echo $key;?>">(loading...)</span>
					
					<div class="float-right">
						<button type="button" class="btn btn-dark">Transaction List</button>
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
