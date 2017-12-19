<?php
$account_info = $db_config->get('config');

if(isset($_POST['save'])){
    // echo $account_info->password . ' = ' . password_hash($_POST['password'], PASSWORD_DEFAULT);die;
    if(password_verify($_POST['password'], $account_info->password)){
        $_SESSION["user_id"] = sha1(time() . '_' . $account_info->api_key);
        header("Location: " . $base_url . "");
    }else{
        alert_message('danger', 'Wrong password!');
    }
}
?>

<form method="post" action="<?php echo $base_url . 'login';?>">
    <div class="row justify-content-md-center">
        <div class="cold col-md-4 col-xs-12">
            <h1>Login</h1>
            <br>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-primary" name="save">Login</button>
        </div>
    </div>
</form>
