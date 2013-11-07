<?php
if(isset($_GET['t']) && $_GET['t'] != "")
{
	$redirect_target = $_GET['t'];
	header("Location: ".$redirect_target);
	exit;
}
?>
<?php include('initialization.inc.php'); ?>
<?php include('header.inc.php'); ?>
<div id="left_section">
<div class="instruction_box">
<p>Welcome to Boba Chai Tea House! Serve up some bubble tea for your friends. Be creative!</p>
</div>
<?php
if(isset($_POST['bubble_tea_send']) && isset($_POST['ids']))
{
	$recipient_ids = $_POST['ids'];
	$flavor_id = $_POST['flavor_select'];
	$filling_id = $_POST['filling_select'];
	if(get_magic_quotes_gpc())
	{
		$message = $_POST['send_tea_message'];
	}
	else
	{
		$message = mysql_real_escape_string($_POST['send_tea_message'], $conn);
	}
	
	if(Data_UserOwnsFlavor($user_id, $flavor_id, $conn) && Data_UserOwnsFilling($user_id, $filling_id, $conn))
	{
		$teas_sent = 0;
		foreach($recipient_ids as $cur_recipient)
		{
			$transfer_sql = "INSERT INTO tea_transfers SET transfer_id='', sender_id='$user_id', receiver_id='$cur_recipient',
							 flavor_id='$flavor_id', filling_id='$filling_id', message='$message', date=NOW()";
			if(mysql_query($transfer_sql, $conn))
			{
				$teas_sent++;
			}
		}
		
		$dep_amount = $teas_sent * 50;
		Data_BankAccountStickyCreditDeposit($user_id, $dep_amount, $conn);
		?>
		<fb:success>
			<fb:message>Bubble Teas Sent</fb:message>
			<p>You have successfully sent bubble teas to <?= $teas_sent ?> of your friends.</p>
            <p>For this generous action, you have received <?= "$".$dep_amount ?> to purchase more ingredients at the 
              <a href="<?= $URL['facebook']."shop.php" ?>">ingredient shop</a>. 
              These credits will not expire, so feel free to collect them to purchase the more expensive ingredients.</p>
		</fb:success>
		<?php
	}
	else
	{
		?>
		<fb:error>
		  <fb:message>Error Sending Bubble Tea</fb:message>
		  Unfortunately there was an error sending your bubble tea. Please try again.
		</fb:error>
		<?php
	}
}

$request_content = "<fb:name uid='$user_id' useyou='false' linked='false' /> has sent you a bubble tea from the Boba Chai Tea House!";
$request_content .= "<fb:req-choice url='".$URL['facebook']."teas_received.php?format=detailed' label='See Bubble Tea and Read Message' />";
?>
<fb:request-form action="<?= $URL['facebook']."index.php" ?>" method="post" invite="false" type="Boba Chai"
  content="<?= $request_content ?>" >
<div id="send_bubble_tea">
<script>
<!--
function UpdateTeaPreview()
{
	var flav_sel = document.getElementById('flavor_select');
	var fill_sel = document.getElementById('filling_select');
	var preview = document.getElementById('mix_tea_preview');
	
	var flav_id = flav_sel.getValue();
	var fill_id = fill_sel.getValue();
	
	var pic_url = "<?= $URL["callback"]."tea_pic.gen.php" ?>?flavor_id=" + flav_id + "&filling_id=" + fill_id;
	
	preview.setStyle('backgroundImage', 'url(\'' + pic_url + '\')');
}
//-->
</script>
<div id="mix_tea_ingredient_selection">
<table width="100%">
<tr>
<td width="50%">
<p class="ingredient_option">Flavor:
	<select name="flavor_select" id="flavor_select" onchange="UpdateTeaPreview()" fb-protected="true">
    	<?php
		$type_sql = "SELECT type_id, phrase_tag FROM tea_types WHERE 1";
		$type_result = mysql_query($type_sql, $conn);
		
		while($type_row = mysql_fetch_assoc($type_result))
		{
			$flavor_sql = "SELECT tea_flavors.flavor_id AS flavor_id, tea_flavors.phrase_tag AS phrase_tag 
						   FROM ingredient_purchases 
						   LEFT JOIN tea_flavors
						     ON ingredient_purchases.ingredient_id=tea_flavors.flavor_id
						   WHERE tea_flavors.type_id='{$type_row['type_id']}'
						     AND ingredient_purchases.user_id='$user_id'
							 AND ingredient_purchases.ingredient_type='flavor'
						   ORDER BY tea_flavors.flavor_id";
			$flavor_result = mysql_query($flavor_sql, $conn);
		
			$flavor_num_rows = mysql_num_rows($flavor_result);
		
			if($flavor_num_rows >= 1)
			{
		    	?>
    			<optgroup label="<?= Dict_GetPhrase($type_row['phrase_tag'], $conn) ?>">
        		<?php
				while($flavor_row = mysql_fetch_assoc($flavor_result))
				{
					?>
    				<option value="<?= $flavor_row['flavor_id'] ?>"><?= Dict_GetPhrase($flavor_row['phrase_tag'], $conn) ?></option>
        			<?php
				}
				?>
        		</optgroup>
        		<?php
			}
		}
		?>
    </select></p>
	</td>
	<td width="50%">
<p class="ingredient_option">Filling:
	<select name="filling_select" id="filling_select" onchange="UpdateTeaPreview()" fb-protected="true">
    	<?php
		$filling_sql = "SELECT tea_fillings.filling_id AS filling_id, tea_fillings.phrase_tag AS phrase_tag 
						   FROM ingredient_purchases 
						   LEFT JOIN tea_fillings
						     ON ingredient_purchases.ingredient_id=tea_fillings.filling_id
						   WHERE ingredient_purchases.user_id='$user_id'
							 AND ingredient_purchases.ingredient_type='filling'
						   ORDER BY tea_fillings.filling_id";;
		$filling_result = mysql_query($filling_sql, $conn);
		
		while($filling_row = mysql_fetch_assoc($filling_result))
		{
		?>
    	<option value="<?= $filling_row['filling_id'] ?>"><?= Dict_GetPhrase($filling_row['phrase_tag'], $conn) ?></option>
        <?php
		}
		?>
    </select></p>
	</td>
	</tr>
	</table>
    </div>
<div id="mix_tea_preview" class="tea_image" style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=01&filling_id=01" ?>')">

</div>
<p>Friends:<br />
  <fb:multi-friend-input width="480px" /></p>
<p>Message (optional):<br />
	<textarea name="send_tea_message" rows="6" id="send_tea_message" fb-protected="true"></textarea></p>
<input type="hidden" name="bubble_tea_send" value="1" fb-protected="true" />
<p id="send_tea_submit"><fb:request-form-submit label="Send Bubble Tea!" /></p>
</div>
</fb:request-form>
</div>
<?php include('footer.inc.php'); ?>