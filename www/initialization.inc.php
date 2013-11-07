<?php
// Include the Facebook API
include_once('client/facebook.php');

// Include the configuration file
include_once('config.inc.php');

// Include the dictionary file
//include_once('dict.inc.php');

// Include the functions file
include_once('functions.inc.php');

// Initialize the Facebook API
$facebook = new Facebook($FB['api_key'], $FB['secret']);
$facebook->require_frame();
$profile_id = $facebook->api_client->users_getLoggedInUser();

$conn = DB_GetConnection();
$user_id = Data_InitializeUser($profile_id, $conn);

Helper_UpdateProfile($user_id, $conn);
?>
<style type="text/css">
<?php include('css/standard.css'); ?>
</style>