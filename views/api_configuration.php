<?php
$account_info = $db_config->get('config');

//Check password protection
if($account_info->enable_password_protection === '1' && (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == '')){
	header("Location: " . $base_url . "login");
}

if(isset($_POST['save'])){
    $account_info->api_key = $_POST['api_key'];
    $account_info->secret_key  = $_POST['secret_key'];
    $account_info->save();
    
    alert_message('success', 'API configuration is saved! Go to <a href="' . $base_url . '">Dashboard</a> to check your API Configuration');
}

if(isset($_GET['api_not_correct'])){
    alert_message('danger', 'API is not correctly configured! Please input your API configuration correctly.<br> Or if you think you have set this API configuration, try to back to <a href="' . $base_url . '">Dashboard</a>. Sometimes it is API fault.');
}
?>

<form method="post" action="<?php echo $base_url . 'api_configuration';?>">
    <div class="row justify-content-md-center">
        <div class="cold col-md-4 col-xs-12">
            <h1>API Configuration</h1>
            <br>
            <div class="form-group">
                <label for="inputApiKey">API Key</label>
                <input type="text" class="form-control" id="inputApiKey" name="api_key" placeholder="Enter API Key" value="<?php echo $account_info->api_key;?>">
            </div>
            <div class="form-group">
                <label for="inputSecretKey">Secret Key</label>
                <input type="text" class="form-control" id="inputSecretKey" name="secret_key" placeholder="Enter Secret Key" value="<?php echo $account_info->secret_key;?>">
            </div>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
            <a href="<?php echo $base_url;?>" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>
