<?php
// Language functions
function Dict_GetPhrase($key, $conn)
{
	global $INST;

	$dict_lang = $INST['dict_lang'];

	//$sql = "SELECT en FROM dictionary WHERE tag='send_bubble_tea'";
	$sql = "SELECT " . $dict_lang . " FROM dictionary WHERE phrase_tag='" . $key . "'";
	
	$result = mysql_query($sql, $conn);

	if(!$row = mysql_fetch_array($result))
	{
		return "Error: No data ($dict_lang, $key)";
	}

	return $row[$dict_lang];
}

// Database functions
function DB_GetConnection()
{
	global $MYSQL;
	
	if(!$conn = mysql_connect($MYSQL['hostname'], $MYSQL['username'], $MYSQL['password']))
	{
		echo mysql_error();
	}
	
	if(!mysql_select_db($MYSQL['database'], $conn))
	{
		echo mysql_error();
	}
	
	return $conn;
}

// Data functions
function Data_InitializeUser($profile_id, $conn)
{
	$user_sql = "SELECT user_id FROM users WHERE profile_id='$profile_id'";
	$user_res = mysql_query($user_sql, $conn);
	if($user_atts = mysql_fetch_assoc($user_res))
	{
		return $user_atts['user_id'];
	}
	
	$user_add_sql = "INSERT INTO users SET user_id='$profile_id', profile_id='$profile_id', date_added=NOW()";
	mysql_query($user_add_sql, $conn);
	$user_id = $profile_id;
	
	$bank_acc_sql = "INSERT INTO bank_accounts SET account_id='', user_id='$user_id', sticky_credits='0', daily_credits='0', 
		last_credit='0', last_debit='0'";
	mysql_query($bank_acc_sql, $conn);
	
	$flavor_sql = "SELECT flavor_id FROM tea_flavors WHERE is_default='yes'";
	$flavor_res = mysql_query($flavor_sql, $conn) or die(mysql_error($conn));
	while($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		$flav_add_sql = "INSERT INTO ingredient_purchases SET purchase_id='', user_id='$user_id', ingredient_type='flavor',
			ingredient_id='{$flavor_row['flavor_id']}', purchase_date=NOW(), purchase_price=0";
		mysql_query($flav_add_sql);
	}
	
	$filling_sql = "SELECT filling_id FROM tea_fillings WHERE is_default='yes'";
	$filling_res = mysql_query($filling_sql, $conn) or die(mysql_error($conn));
	while($filling_row = mysql_fetch_assoc($filling_res))
	{
		$filling_add_sql = "INSERT INTO ingredient_purchases SET purchase_id='', user_id='$user_id', ingredient_type='filling',
			ingredient_id='{$filling_row['filling_id']}', purchase_date=NOW(), purchase_price=0";
		mysql_query($filling_add_sql);
	}
	
	// Deposit 100 sticky credits to users' accounts upon adding the application.
	Data_BankAccountStickyCreditDeposit($user_id, 100, $conn);
	
	return $user_id;
}

function Data_AllotDailyCredits($user_id, $conn)
{
	$bank_sql = "UPDATE bank_accounts SET daily_credits='100', last_credit=NOW() WHERE user_id='$user_id'";
	mysql_query($bank_sql, $conn);
}

function Data_GetBankAccountBalance($user_id, $conn)
{
	$bank_sql = "SELECT (daily_credits + sticky_credits) AS total_credits FROM bank_accounts WHERE user_id='$user_id' LIMIT 1";
	$bank_res = mysql_query($bank_sql, $conn);
	
	if($bank_row = mysql_fetch_assoc($bank_res))
	{
		return $bank_row['total_credits'];
	}
	else
	{
		return 0;
	}
}

function Data_BankAccountWithdrawl($user_id, $withdrawl_amount, $conn)
{
	$balance_sql = "SELECT daily_credits, sticky_credits FROM bank_accounts WHERE user_id='$user_id' LIMIT 1";
	$balance_res = mysql_query($balance_sql, $conn);
	
	if(!($balance_row = mysql_fetch_assoc($balance_res)))
	{
		return false;
	}
	
	if($balance_row['daily_credits'] + $balance_row['sticky_credits'] < $withdrawl_amount)
	{
		return false;
	}
	
	if($balance_row['daily_credits'] >= $withdrawl_amount)
	{
		$daily_balance = $balance_row['daily_credits'] - $withdrawl_amount;
		$sticky_balance = $balance_row['sticky_credits'];
	}
	else
	{
		$daily_balance = 0;
		$sticky_balance = $balance_row['daily_credits'] + $balance_row['sticky_credits'] - $withdrawl_amount;
	}
	
	$withdrawl_sql = "UPDATE bank_accounts
					  SET daily_credits='$daily_balance', sticky_credits='$sticky_balance', last_debit=NOW() 
					  WHERE user_id='$user_id' LIMIT 1";
	if(mysql_query($withdrawl_sql, $conn))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function Data_BankAccountStickyCreditDeposit($user_id, $deposit_amount, $conn)
{
	$dep_sql = "UPDATE bank_accounts
				SET sticky_credits=sticky_credits+'$deposit_amount', last_credit=NOW()
				WHERE user_id='$user_id'";
	if(mysql_query($dep_sql, $conn))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function Data_UserOwnsFlavor($user_id, $flavor_id, $conn)
{
	$flavor_sql = "SELECT purchase_id FROM ingredient_purchases 
				   WHERE ingredient_type='flavor' AND user_id='$user_id' AND ingredient_id='$flavor_id' LIMIT 1";
	$flavor_res = mysql_query($flavor_sql, $conn);
	if($flavor_att = mysql_fetch_assoc($flavor_res))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function Data_UserOwnsFilling($user_id, $filling_id, $conn)
{
	$filling_sql = "SELECT purchase_id FROM ingredient_purchases 
				   WHERE ingredient_type='filling' AND user_id='$user_id' AND ingredient_id='$filling_id' LIMIT 1";
	$filling_res = mysql_query($filling_sql, $conn);
	if($filling_att = mysql_fetch_assoc($filling_res))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function Data_GenerateProfileFbml($user_id, $conn)
{
	global $URL;
	
	$total_sql = "SELECT COUNT(transfer_id) AS total_teas
				  FROM tea_transfers
				  WHERE receiver_id='$user_id'";
	$total_res = mysql_query($total_sql, $conn);
	$total_teas = 0;
	if($total_att = mysql_fetch_assoc($total_res))
	{
		$total_teas = $total_att['total_teas'];
	}
	
	if($total_teas == 0)
	{
		$fbml  = "<fb:subtitle seeallurl=\"".$URL['facebook']."/teas_received.php?uid=$user_id\">";
		$fbml .= "No bubble teas to display.";
		$fbml .= "</fb:subtitle>";
		$fbml .= "<p style=\"font-style:italic;\">";
		$fbml .= "<fb:name uid=\"$user_id\" useyou=\"false\" firstnameonly=\"true\" /> has not received any bubble teas yet.";
		$fbml .= "</p>";
	}
	else
	{
		$disp_sql = "SELECT sender_id, flavor_id, filling_id, DATE_FORMAT(date, '%b %e, %Y') AS date_format 
  			   		 FROM tea_transfers 
			   		 WHERE receiver_id='$user_id' 
			   		 ORDER BY date DESC
			   		 LIMIT 3";
		$disp_res = mysql_query($disp_sql, $conn);
		$disp_teas = mysql_num_rows($disp_res);
		
		if($total_teas == 1)
		{
			$fbml  = "<fb:subtitle seeallurl=\"".$URL['facebook']."/teas_received.php?uid=$user_id\">";
			$fbml .= "Displaying the only tea.";
			$fbml .= "</fb:subtitle>";
		}
		else if($disp_teas == $total_teas)
		{
			$fbml  = "<fb:subtitle seeallurl=\"".$URL['facebook']."/teas_received.php?uid=$user_id\">";
			$fbml .= "Displaying all $total_teas teas.";
			$fbml .= "</fb:subtitle>";
		}
		else
		{
			$fbml  = "<fb:subtitle seeallurl=\"".$URL['facebook']."/teas_received.php?uid=$user_id\">";
			$fbml .= "Displaying $disp_teas of $total_teas teas.";
			$fbml .= "</fb:subtitle>";
		}
		
		$fbml .= "<fb:wide>";
		
		$items_displayed = 0;
		$fbml .= "<table style=\"border:none;\">";
		$fbml .= "<tr>";
		while($disp_row = mysql_fetch_assoc($disp_res))
		{
			if($items_displayed % 3 == 0 && $items_displayed != 0)
			{
				$fbml .= "</tr>";
				$fbml .= "<tr>";
			}
		
			$fbml .= "<td style=\"width:122px;text-align:center;\">";
			$fbml .= "<div title=\"".Helper_GetTeaFullName($disp_row['flavor_id'], $disp_row['filling_id'], $conn)."\"";
			$fbml .= " style=\"width:100px;height:150px;margin-left:auto;margin-right:auto;margin-top:5px; margin-bottom:5px;border:#999999 solid 1px;background-color:#FFFFFF;background-image:url('".$URL["callback"]."tea_pic.gen.php?flavor_id=".$disp_row['flavor_id']."&filling_id=".$disp_row['filling_id']."&thumbnail=1');\"></div>";
			$fbml .= "<p style=\"font-weight:bold;margin-top:2px;margin-bottom:2px;text-align:center;\">".Helper_GetTeaShortName($disp_row['flavor_id'], $conn)."</p>";
			$fbml .= "<p style=\"text-alight:center;margin-top:2px;margin-bottom:2px;text-align:center;\"><fb:name uid=\"".$disp_row['sender_id']."\" ifcantsee=\"Unknown Sender\" capitalize=\"true\" /></p>";
			$fbml .= "<p style=\"text-alight:center;margin-top:2px;margin-bottom:2px;text-align:center;\">".$disp_row['date_format']."</p>";
			$fbml .= "</td>";
			
			$items_displayed++;
		}
		$fbml .= "</tr>";
		$fbml .= "</table>";
		
		$fbml .= "</fb:wide>";
		
		$fbml .= "<fb:narrow>";
		
		$disp_sql = "SELECT sender_id, flavor_id, filling_id, DATE_FORMAT(date, '%b %e, %Y') AS date_format 
  			   		 FROM tea_transfers 
			   		 WHERE receiver_id='$user_id' 
			   		 ORDER BY date DESC
			   		 LIMIT 3";
		$disp_res = mysql_query($disp_sql, $conn);
		
		$fbml .= "<table style=\"border:none;\" width=\"100%\">";
		$fbml .= "<tr>";
		while($disp_row = mysql_fetch_assoc($disp_res))
		{
			if($items_displayed != 0)
			{
				$fbml .= "</tr>";
				$fbml .= "<tr>";
			}
		
			$fbml .= "<td style=\"text-align:center;\">";
			$fbml .= "<div title=\"".Helper_GetTeaFullName($disp_row['flavor_id'], $disp_row['filling_id'], $conn)."\"";
			$fbml .= " style=\"width:100px;height:150px;margin-left:auto;margin-right:auto;margin-top:5px; margin-bottom:5px;border:#999999 solid 1px;background-color:#FFFFFF;background-image:url('".$URL["callback"]."tea_pic.gen.php?flavor_id=".$disp_row['flavor_id']."&filling_id=".$disp_row['filling_id']."&thumbnail=1');\"></div>";
			$fbml .= "<p style=\"font-weight:bold;margin-top:2px;margin-bottom:2px;text-align:center;\">".Helper_GetTeaShortName($disp_row['flavor_id'], $conn)."</p>";
			$fbml .= "<p style=\"text-alight:center;margin-top:2px;margin-bottom:2px;text-align:center;\"><fb:name uid=\"".$disp_row['sender_id']."\" ifcantsee=\"Unknown Sender\" capitalize=\"true\" /></p>";
			$fbml .= "<p style=\"text-alight:center;margin-top:2px;margin-bottom:2px;text-align:center;\">".$disp_row['date_format']."</p>";
			$fbml .= "</td>";
			
			$items_displayed++;
		}
		$fbml .= "</tr>";
		$fbml .= "</table>";
		
		$fbml .= "</fb:narrow>";
	}
	
	return $fbml;
}

// Helper functions
function Helper_CompareFilenames($a, $b)
{
	// Remove directory paths
	$aSepPos = strrpos($a, "/");
	$bSepPos = strrpos($b, "/");
	
	if($aSepPos !== false)
	{
		$a = substr($a, $aSepPos + 1);
	}
	if($bSepPos !== false)
	{
		$b = substr($b, $bSepPos + 1);
	}
	
	if(trim($a) == trim($b))
	{
		return true;
	}
	
	return false;
}

function Helper_GetFlavorTeaColorCode($flavor_id, $conn)
{
	$sql = "SELECT tea_color_code FROM tea_flavors WHERE flavor_id='$flavor_id'";
	$result = mysql_query($sql, $conn);
	
	if($att = mysql_fetch_array($result))
	{
		return $att['tea_color_code'];
	}
	else
	{
		return "FFFFFF";
	}
}

function Helper_Hex2RGB($hex)
{
	return array( 'r' => hexdec(substr($hex, 0, 2)), 
    			  'g' => hexdec(substr($hex, 2, 2)), 
        		  'b' => hexdec(substr($hex, 4, 2)));
}

// Produces name of the format Taro
function Helper_GetTeaShortName($flavor_id, $conn)
{
	$flavor_sql = "SELECT phrase_tag FROM tea_flavors WHERE flavor_id='$flavor_id' LIMIT 1";
	$flavor_res = mysql_query($flavor_sql, $conn);
	if($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		$flavor_tag = $flavor_row['phrase_tag'];
	}
	else
	{
		return "Unknown Flavor";
	}
	
	$name_string = Dict_GetPhrase($flavor_tag, $conn);

	return $name_string;
}

// Produces name of the format Taro Milk Tea
function Helper_GetTeaName($flavor_id, $conn)
{
	$flavor_sql = "SELECT phrase_tag, type_id FROM tea_flavors WHERE flavor_id='$flavor_id' LIMIT 1";
	$flavor_res = mysql_query($flavor_sql, $conn);
	if($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		$flavor_tag = $flavor_row['phrase_tag'];
	}
	else
	{
		return "Unknown Flavor";
	}
	
	$type_sql = "SELECT phrase_tag FROM tea_types WHERE type_id='{$flavor_row['type_id']}' LIMIT 1";
	$type_res = mysql_query($type_sql, $conn);
	if($type_row = mysql_fetch_assoc($type_res))
	{
		$type_tag = $type_row['phrase_tag'];
	}
	else
	{
		return "Unknown Type";
	}
	
	$name_string = Dict_GetPhrase($flavor_tag, $conn)." ".Dict_GetPhrase($type_tag, $conn);

	return $name_string;
}

// Produces name of the formate Taro Milk Tea with Coconut Jellies
function Helper_GetTeaFullName($flavor_id, $filling_id, $conn)
{
	$flavor_sql = "SELECT phrase_tag, type_id FROM tea_flavors WHERE flavor_id='$flavor_id' LIMIT 1";
	$flavor_res = mysql_query($flavor_sql, $conn);
	if($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		$flavor_tag = $flavor_row['phrase_tag'];
	}
	else
	{
		return "Unknown Flavor";
	}
	
	$type_sql = "SELECT phrase_tag FROM tea_types WHERE type_id='{$flavor_row['type_id']}' LIMIT 1";
	$type_res = mysql_query($type_sql, $conn);
	if($type_row = mysql_fetch_assoc($type_res))
	{
		$type_tag = $type_row['phrase_tag'];
	}
	else
	{
		return "Unknown Type";
	}
	
	$filling_sql = "SELECT phrase_tag FROM tea_fillings WHERE filling_id='$filling_id' LIMIT 1";
	$filling_res = mysql_query($filling_sql, $conn);
	if($filling_row = mysql_fetch_assoc($filling_res))
	{
		$filling_tag = $filling_row['phrase_tag'];
	}
	else
	{
		return "Unknown Filling";
	}
	
	$name_string = Dict_GetPhrase($flavor_tag, $conn)." ".Dict_GetPhrase($type_tag, $conn)." with ".Dict_GetPhrase($filling_tag, $conn);

	return $name_string;
}

// Updates the user's profile FBML
function Helper_UpdateProfile($user_id, $conn)
{
	$profile_fbml = Data_GenerateProfileFbml($user_id, $conn);
	
	$GLOBALS['facebook']->api_client->profile_setFBML('', $user_id, $profile_fbml);
}
?>