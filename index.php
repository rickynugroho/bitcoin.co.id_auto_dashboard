<?php
//Set base URL
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI';
$base_url = "http://".$host;
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

//Require vendor file
require_once('vendor/autoload.php');

//Setting up filebase database
$db_config = new \Filebase\Database([
    'dir'            => 'db' . DIRECTORY_SEPARATOR . 'config',
    'pretty'         => true,
    'validate' => [
        'api_key'   => [
            'valid.type' => 'string',
            'valid.required' => true
        ],
        'secret_key'   => [
            'valid.type' => 'string',
            'valid.required' => true
        ]
    ]
]);

//Require API file
//Put here because we need to read some configuration from $db_config and use it on btcid.php
require_once('btcid.php');

//Routing
$requestUrl = $_SERVER['REQUEST_URI'];
if (($pos = strpos($requestUrl, '?')) !== false) {
    $requestUrl = substr($requestUrl, 0, $pos);
}

$requestUrlArr = explode('/', $requestUrl);
$requestUrl = isset($requestUrlArr[2]) ? $requestUrlArr[2] : '';
// print_r($requestUrl);die;
if($requestUrl == '') $requestUrl = 'dashboard';

//Check for configured api and secret key in bitcoin.co.id
if($requestUrl != 'api_configuration'){
    $account_info = $db_config->get('config');
    $getInfo = btcid_query('getInfo', array(), $account_info->api_key, $account_info->secret_key);
    // print_r($getInfo);die;
    if($account_info->api_key == '' || $account_info->secret_key == '' || $getInfo['success'] == 0){
        header("Location: " . $base_url . "api_configuration?api_not_correct");
        die();
    }
}

//Alert function
function alert_message($type, $message){
    ?>
    <div class="alert alert-<?php echo $type;?> alert-dismissible fade show" role="alert">
        <?php echo $message;?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
}

//File js stored loading
$js_files = array();

//Load views
require_once('views/html_header.php');
require_once('views/' . $requestUrl . '.php');
require_once('views/html_footer.php');
