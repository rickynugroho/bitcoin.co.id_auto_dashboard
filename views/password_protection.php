<?php
$account_info = $db_config->get('config');

//Check password protection
if($account_info->enable_password_protection === '1' && (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == '')){
	header("Location: " . $base_url . "login");
}

if(isset($_POST['save'])){
    if($_POST['enable_password_protection'] == '1'){
        if(($_POST['new_password'] != '' && $_POST['repeat_new_password'] != '') && ($_POST['new_password'] == $_POST['repeat_new_password'])){
            $account_info->enable_password_protection = $_POST['enable_password_protection'];
            $account_info->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $account_info->save();
            
            alert_message('success', 'Password protection setting is saved!');
        }elseif($_POST['new_password'] == '' || $_POST['repeat_new_password'] == ''){
            alert_message('danger', 'Password cannot be empty!');
        }elseif(($_POST['new_password'] != '' && $_POST['repeat_new_password'] != '') && ($_POST['new_password'] != $_POST['repeat_new_password'])){
            alert_message('danger', 'New Password and Repeat New Password is not same!');
        }
    }elseif($_POST['enable_password_protection'] === '0'){
        $account_info->enable_password_protection = $_POST['enable_password_protection'];
        $account_info->password = null;
        $account_info->save();
        
        alert_message('success', 'Password protection setting is saved!');
    }
}

$enable_password_protection = $account_info->enable_password_protection;
if($enable_password_protection == ''){
    $enable_password_protection = '0';
}
?>

<form method="post" action="<?php echo $base_url . 'password_protection';?>">
    <div class="row justify-content-md-center">
        <div class="cold col-md-4 col-xs-12">
            <h1>Password Protection</h1>
            <br>
            <div class="alert alert-warning" role="alert">
                If you have already enabled password protection, old password will be replaced.
            </div>
            <div class="form-group">
                <label for="inputNewPassword">Enable Password Protection?</label>
                <br>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="enable_password_protection" value="1" <?php if($enable_password_protection === '1') echo 'checked="checked"';?>> Yes
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="enable_password_protection" value="0" <?php if($enable_password_protection === '0') echo 'checked="checked"';?>> No
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="inputNewPassword">New Password</label>
                <input type="password" class="form-control" id="inputNewPassword" name="new_password" placeholder="Enter New Password" <?php if($enable_password_protection === '0') echo 'disabled';?>>
            </div>
            <div class="form-group">
                <label for="inputRepeatPassword">Repeat New Password</label>
                <input type="password" class="form-control" id="inputRepeatPassword" name="repeat_new_password" placeholder="Enter Repeat New Password" <?php if($enable_password_protection === '0') echo 'disabled';?>>
            </div>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
            <a href="<?php echo $base_url;?>" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>

<?php
array_push($js_files, $base_url . 'js/password_protection.js');
?>
