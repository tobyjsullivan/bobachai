<? include('initialization.inc.php'); ?>
<? include('header.inc.php'); ?>
<div id="left_section">
<?php
$selected_user = $user_id;
if(isset($_GET['uid']))
{
	$selected_user = $_GET['uid'];
}

$selected_format = "compact";
if(isset($_GET['format']))
{
	$selected_format = $_GET['format'];
}

$selected_page = 1;
if(isset($_GET['page']))
{
	$selected_page = $_GET['page'];
}

$items_per_page = 1;
if($selected_format == "compact")
{
	$items_per_page = 16;
}
else if($selected_format == "detailed")
{
	$items_per_page = 6;
}

$first_item = ($selected_page - 1) * $items_per_page;

$count_sql = "SELECT COUNT(transfer_id) AS num_teas_received FROM tea_transfers WHERE receiver_id='$selected_user'";
$count_res = mysql_query($count_sql, $conn);
if($count_att = mysql_fetch_assoc($count_res))
{
	$num_teas_received = $count_att['num_teas_received'];
}
else
{
	$num_teas_received = 0;
}

$total_pages = (($num_teas_received) / $items_per_page) + 1;
if($num_teas_received % $items_per_page == 0)
{
	$total_pages--;
}

if($num_teas_received == 0)
{
	$total_pages = 1;
}
?>
<div class="instruction_box">
<p>This is a list of all the bubble teas <fb:name uid="<?= $selected_user ?>" useyou="true" capitalize="false" possessive="true" linked="false" /> awesome friends have sent <fb:pronoun uid="<?= $selected_user ?>" useyou="true" capitalize="false" objective="true" />!</p>
</div>
<div class="tea_list_format_selection">
<fb:tabs>
  <fb:tab-item href="teas_received.php?format=compact&uid=<?= $selected_user ?>" title="Compact" <?= $selected_format=="compact"?"selected=\"true\"":"" ?> />
  <fb:tab-item href="teas_received.php?format=detailed&uid=<?= $selected_user ?>" title="Detailed" <?= $selected_format=="detailed"?"selected=\"true\"":"" ?> />
</fb:tabs>

</div>
<div class="page_selection">
<p>
  Page
  <?
  for($i = 1; $i <= $total_pages; $i++)
  {
  ?>
  <a href="<?= "teas_received.php?format=".$selected_format."&uid=".$selected_user."&page=".$i ?>" 
    <?= ($selected_page==$i)?"class=\"selected_page\"":"" ?>><?= $i ?></a>
  <?
  }
  ?>
</p>
</div>
<?php
if($num_teas_received == 0)
{
?>
<p class="notification">Nobody has sent <fb:name uid="<?= $selected_user ?>" useyou="true" capitalize="false" firstnameonly="true" linked="false" /> a bubble tea yet!</p>
<?php
}
else if($selected_format == "compact")
{
?>
<div class="compact_tea_list">
  	  <div class="compact_tea_list_row">
<?php
  $row_num = 0;
  $items_on_row = 0;
  
  $first_item = ($selected_page - 1) * $items_per_page;
  
  $teas_sql = "SELECT sender_id, flavor_id, filling_id, DATE_FORMAT(date, '%b %e, %Y') as date_format 
  			   FROM tea_transfers 
			   WHERE receiver_id='$selected_user' 
			   ORDER BY date DESC
			   LIMIT $first_item, $items_per_page";
  $teas_res = mysql_query($teas_sql, $conn);
  while($teas_row = mysql_fetch_assoc($teas_res))
  {
  	if($items_on_row >= 4)
	{
	  ?>
	  </div>
  	  <div class="compact_tea_list_row">
	  <?php
	  $items_on_row = 0;
	}
    ?>
  <div class="compact_tea_list_item">
	<div class="tea_image_thumbnail" title="<?= Helper_GetTeaFullName($teas_row['flavor_id'], $teas_row['filling_id'], $conn) ?>" 
	  style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=".$teas_row['flavor_id']."&filling_id=".$teas_row['filling_id']."&thumbnail=1" ?>');">

	</div>
	<p class="compact_tea_list_name"><?= Helper_GetTeaShortName($teas_row['flavor_id'], $conn) ?></p>
	<p><fb:name uid="<?= $teas_row['sender_id'] ?>" ifcantsee="Unknown Recipient" capitalize="true" /></p>
	<p><?= $teas_row['date_format'] ?></p>
  </div>
    <?php
    $items_on_row++;
  }
  ?>
  </div>
</div>
<?php
}
else if($selected_format == "detailed")
{
?>
<div class="detailed_tea_list">
  <?php
  $first_item = ($selected_page - 1) * $items_per_page;
  
  $teas_sql = "SELECT sender_id, flavor_id, filling_id, DATE_FORMAT(date, '%M %e, %Y %l:%i %p') as date_format, message 
  			   FROM tea_transfers 
			   WHERE receiver_id='$selected_user' 
			   ORDER BY date DESC
			   LIMIT $first_item, $items_per_page";
  $teas_res = mysql_query($teas_sql, $conn);
  while($teas_row = mysql_fetch_assoc($teas_res))
  {
  ?>
  <div class="detailed_tea_list_item">
	<div class="tea_image_thumbnail detailed_tea_list_image" title="<?= Helper_GetTeaFullName($teas_row['flavor_id'], $teas_row['filling_id'], $conn) ?>"
	  style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=".$teas_row['flavor_id']."&filling_id=".$teas_row['filling_id']."&thumbnail=1" ?>');">

	</div>
	<p class="detailed_tea_list_name"><?= Helper_GetTeaFullName($teas_row['flavor_id'], $teas_row['filling_id'], $conn) ?></p>
	<p class="detailed_tea_list_date"><?= $teas_row['date_format'] ?></p>
	<p><fb:name uid="<?= $teas_row['sender_id'] ?>" ifcantsee="Unknown Sender" capitalize="true" /></p>
    <?php
	if($teas_row['message'] != "")
	{
	?>
	<p class="detailed_tea_list_message">&quot;<?= $teas_row['message'] ?>&quot;</p>
    <?php
	}
	?>
  </div>
  <?php
  }
  ?>
</div>
<?php
}
?>
<div class="page_selection">
<p>
  Page
  <?
  for($i = 1; $i <= $total_pages; $i++)
  {
  ?>
  <a href="<?= "teas_received.php?format=".$selected_format."&uid=".$selected_user."&page=".$i ?>" 
    <?= ($selected_page==$i)?"class=\"selected_page\"":"" ?>><?= $i ?></a>
  <?
  }
  ?>
</p>
</div>
</div>

<?php include('footer.inc.php'); ?>