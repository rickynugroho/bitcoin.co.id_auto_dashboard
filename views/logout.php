<?php
$_SESSION["user_id"] = "";
session_destroy();
header("Location: " . $base_url . "");
?>
