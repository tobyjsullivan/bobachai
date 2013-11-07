<?php
include_once('header.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tea Type - Add</title>
</head>

<body>
<p>Add a Tea Type</p>
<?php
if(isset($_POST['type_add_submit']))
{
	$conn = DB_GetConnection();

	$dict_sql = "INSERT INTO dictionary SET phrase_id='', phrase_tag='{$_POST['name_phrase_tag']}', en='{$_POST['name_en']}'";
	if(mysql_query($dict_sql, $conn))
	{
		$type_sql = "INSERT INTO tea_types SET type_id='', type_tag='{$_POST['type_tag']}', phrase_tag='{$_POST['name_phrase_tag']}'";
		if(mysql_query($type_sql, $conn))
		{
			?>
            <p style="color:#0000FF; font-weight:bold;">Type INSERT successful.</p>
            <?php
		}
		else
		{
		{
			?>
            <p style="color:#FF0000; font-weight:bold;">Type INSERT failed.</p>
            <?php
			echo mysql_error($conn);
		}
		}
	}
	else
	{
		?>
        <p style="color:#FF0000; font-weight:bold;">Dictionary INSERT failed.</p>
        <?php
		echo mysql_error($conn);
	}
}

?>
<table width="400" border="1">
<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
  <tr>
    <td>English Name:</td>
    <td><input type="text" name="name_en" id="name_en" /></td>
  </tr>
  <tr>
    <td>Name Phrase Tag:</td>
    <td><input name="name_phrase_tag" type="text" id="name_phrase_tag" maxlength="256" /></td>
  </tr>
  <tr>
    <td>Type Tag:</td>
    <td><input name="type_tag" type="text" id="type_tag" maxlength="256" /></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="type_add_submit" id="type_add_submit" value="Add Type" />
    </div></td>
    </tr>
</form>
</table>
<p>&nbsp; </p>
</body>
</html>
