<div id="right_section">
  <div id="balance_box" class="right_menu_box">
	<p class="right_menu_header">Ingredient Shop</p>
	<p id="account_balance"><?= "$".Data_GetBankAccountBalance($user_id, $conn); ?></p>
	<p><a href="shop.php" class="right_menu_link">Buy More Ingredients</a></p>
  </div>

  <div class="right_menu_box">
	<p class="right_menu_header">Recently Received</p>
    <?php
    $teas_sql = "SELECT sender_id, flavor_id, filling_id, DATE_FORMAT(date, '%b %e, %Y') as date_format 
  			     FROM tea_transfers 
			     WHERE receiver_id='$user_id' 
			     ORDER BY date DESC
			     LIMIT 0, 2";
    $teas_res = mysql_query($teas_sql, $conn);
	
	$teas_num_rows = mysql_num_rows($teas_res);
	
	if($teas_num_rows == 0)
	{
	  ?>
      <p class="notification">You have not received any bubble teas yet.</p>
      <?php
	}
	else
	{
	  ?>
	  <div class="compact_tea_list">
      <?php
      while($teas_row = mysql_fetch_assoc($teas_res))
      {
	    ?>
  	    <div class="compact_tea_list_item right_menu_tea_list_item">
		  <div class="tea_image_thumbnail" title="<?= Helper_GetTeaFullName($teas_row['flavor_id'], $teas_row['filling_id'], $conn) ?>" 
            style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=".$teas_row['flavor_id']."&filling_id=".$teas_row['filling_id']."&thumbnail=1" ?>')"></div>
		  <p class="compact_tea_list_name"><?= Helper_GetTeaShortName($teas_row['flavor_id'], $conn) ?></p>
  		  <p><fb:name uid="<?= $teas_row['sender_id'] ?>" ifcantsee="Unknown Sender" /></p>
		  <p><?= $teas_row['date_format'] ?></p>
    	  </div>
  	    <?php
	  }
	  ?>
  	  </div>
      <?php
	}
	?>
  </div>
</div>