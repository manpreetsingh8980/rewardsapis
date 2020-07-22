<?php
define('DB_USER', 'root'); // Your database user.
define('DB_PASSWORD', ''); // Your database password.
define('DB_HOST', '127.0.0.1'); // Your database host (usually 127.0.0.1).
define('DB_HOST_PORT', '3306'); // Your database host port (usually 3306).
define('DB_NAME', 'rewardsapp'); // Your database name.
define('DB_PREFIX', 'reward_'); // OPTIONAL: A database table prefix, eg 'adgem_app_'

// Whitelist AdGem Postback IP
define('ADGEM_WHITELIST_IP', null); // IMPORTANT, FOR TESTING ONLY, REMOVE THIS BEFORE GOING TO PRODUCTION AND UNCOMMENT THE LINE BELOW
//define('ADGEM_WHITELIST_IP', '18.191.5.158'); // The production IP to whitelist for the AdGem server sending the postback

error_reporting(E_WARNING);

// *** No more configuration below this line. ***

header('Content-Type:text/plain');

// If &setup=1 is passed in, database tables will be created if they do not exist
if(isset($_REQUEST['setup']))
{
	$query = 
	"CREATE TABLE IF NOT EXISTS `".DB_PREFIX."transactions` (
	`id` INT NOT NULL AUTO_INCREMENT,
        `transaction_id` VARCHAR(255),
	`player_id` VARCHAR(255),
	`app_id` INT,
        `campaign_id` INT,
        `campaign_name` VARCHAR(255),
	`amount` INT,
        `payout` DECIMAL(10,2),
	`click_datetime` DATETIME,
        `conversion_datetime` DATETIME,
        `gaid` VARCHAR(255),
        `idfa` VARCHAR(255),
        `ip` VARCHAR(255),
        `time` DATETIME,
	PRIMARY KEY (`id`)) 
	CHARACTER SET utf8 COLLATE utf8_general_ci;
	CREATE TABLE IF NOT EXISTS `".DB_PREFIX."users` (
	`player_id` BIGINT NOT NULL,
	`balance` INT,
	`time` DATETIME,
	PRIMARY KEY (`player_id`)) 
        CHARACTER SET utf8 COLLATE utf8_general_ci;";
    
	try 
	{
		// Connect to Database.
		$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_HOST_PORT, DB_USER, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ));
		$query = $dbh->prepare($query);
		if(!$query->execute())
			echo "Could not create tables in database: ".DB_NAME." @ ".DB_HOST.'. Check your configuration.';
		else
			echo "Database tables setup successfully! After setting ADGEM_WHITELIST_IP, your postback script will be ready to use.";
		$dbh = null;
	}
	catch (PDOException $e) 
	{
		exit($e->getMessage());
	}
	exit();
}

$player_id = $_REQUEST['player_id']; // The unique id of the player/user on your app.
$app_id = $_REQUEST['app_id']; // Your unique AdGem App ID as found from the AdGem Publisher Dashboard.
$amount = $_REQUEST['amount']; // The amount of virtual currency to reward the user who completed the offer.
$campaign_id = $_REQUEST['campaign_id']; // The unique campaign or offer ID for the completed offer.
$campaign_name = $_REQUEST['campaign_name']; // The name of the campaign/offer that was completed.
$click_datetime = $_REQUEST['click_datetime']; // The exact date and time when the user clicked on the offer.
$conversion_datetime = $_REQUEST['conversion_datetime']; // The exact date and time when the user completed the offer.
$transaction_id = $_REQUEST['transaction_id']; // The unique transaction id generated on the click.
$gaid = $_REQUEST['gaid']; // Google Advertising ID, available when the developer is using Google Play Services.
$idfa = $_REQUEST['idfa']; // Apple Advertising ID, available when the user has not limited ad tracking.
$ip = $_REQUEST['ip']; // The IP Address for the user who completed the offer.
$payout = $_REQUEST['payout']; // The decimal amount of revenue earned from the user completing the offer.

if(!(is_numeric($app_id) && is_numeric($amount) && is_numeric($campaign_id) && is_numeric($payout)))
	exit('Failed. Non-numeric value sent on numeric only fields.'); // Fail.


if(null !== ADGEM_WHITELIST_IP){
    if(ADGEM_WHITELIST_IP !== $_SERVER['SERVER_ADDR']){
        exit('Failed. '.$_SERVER['SERVER_ADDR'].' does not match the whitelisted IP address.'); 
    }
}

$success = true;
$timestamp = date("Y-m-d H:i:s", time());

try 
	{
		// Connect to Database.
		$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_HOST_PORT, DB_USER, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ));
		// Add new transaction
		$query = $dbh->prepare("INSERT INTO ".DB_PREFIX."transactions(transaction_id, player_id, app_id, campaign_id, campaign_name, amount, payout, click_datetime, conversion_datetime, gaid, idfa, ip, time) VALUES (:transaction_id, :player_id, :app_id, :campaign_id, :campaign_name, :amount, :payout, :click_datetime, :conversion_datetime, :gaid, :idfa, :ip, :time) ON DUPLICATE KEY UPDATE transaction_id=:transaction_id, player_id=:player_id, app_id=:app_id, campaign_id=:campaign_id, campaign_name=:campaign_name, amount=:amount, payout=:payout, click_datetime=:click_datetime, conversion_datetime=:conversion_datetime, gaid=:gaid, idfa=:idfa, ip=:ip, time=:time");
                $query->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
                $query->bindParam(':player_id', $player_id, PDO::PARAM_STR);
		$query->bindParam(':app_id', $app_id, PDO::PARAM_INT);
                $query->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
                $query->bindParam(':campaign_name', $campaign_name, PDO::PARAM_STR);
                $query->bindParam(':amount', $amount, PDO::PARAM_INT);
                $query->bindParam(':payout', $payout, PDO::PARAM_STR);
                $query->bindParam(':click_datetime', $click_datetime, PDO::PARAM_STR);
                $query->bindParam(':conversion_datetime', $conversion_datetime, PDO::PARAM_STR);
                $query->bindParam(':gaid', $gaid, PDO::PARAM_STR);
                $query->bindParam(':idfa', $idfa, PDO::PARAM_STR);
                $query->bindParam(':ip', $ip, PDO::PARAM_STR);
		$query->bindParam(':time', $timestamp, PDO::PARAM_STR);
		if(!$query->execute())
                     $success = false; // SQL execution failed. 

		// If player does not exist, add them, otherwise update their balance
		$query = $dbh->prepare("INSERT INTO ".DB_PREFIX."users(player_id, balance, time) VALUES (:player_id, :balance, :time) ON DUPLICATE KEY UPDATE player_id=:player_id, balance=balance+:balance, time=:time");
		$query->bindParam(':player_id', $player_id, PDO::PARAM_INT);
		$query->bindParam(':balance', $amount, PDO::PARAM_INT);
		$query->bindParam(':time', $timestamp, PDO::PARAM_STR);
		if(!$query->execute())
		     $success = false;  // SQL execution failed. 
		     $dbh = null;
	}
catch (PDOException $e) 
	{
		exit($e->getMessage());
        }
    
echo $success;

?>