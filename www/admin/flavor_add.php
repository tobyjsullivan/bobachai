<?php
include_once('header.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tea Flavor - Add</title>
</head>

<body>
<p>Add a Tea Flavor</p>
<?php
$conn = DB_GetConnection();

if(isset($_POST['flavor_add_submit']))
{

	$dict_sql = "INSERT INTO dictionary (phrase_id, phrase_tag, en)
				 VALUES ('', '{$_POST['name_phrase_tag']}', '{$_POST['name_en']}'),
				 		('', '{$_POST['description_tag']}', '{$_POST['description']}')";
	if(mysql_query($dict_sql, $conn))
	{
		$default = "no";
		if(isset($_POST['default']) && $_POST['default'] == "yes")
		{
			$default = "yes";
		}
		$flavor_sql = "INSERT INTO tea_flavors 
					   SET flavor_id='', type_id='{$_POST['type_id']}', flavor_tag='{$_POST['flavor_tag']}', 
					       phrase_tag='{$_POST['name_phrase_tag']}', is_default='$default', price='{$_POST['price']}',
						   desc_tag='{$_POST['description_tag']}', tea_color_code='{$_POST['color']}'";
		if(mysql_query($flavor_sql, $conn))
		{
			?>
            <p style="color:#0000FF; font-weight:bold;">Flavor INSERT successful.</p>
            <?php
		}
		else
		{
		{
			?>
            <p style="color:#FF0000; font-weight:bold;">Flavor INSERT failed.</p>
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
    <td>Name Phrase Tag: (flavor_taro)</td>
    <td><input name="name_phrase_tag" type="text" id="name_phrase_tag" maxlength="256" /></td>
  </tr>
  <tr>
    <td>Flavor Tag: (taro)</td>
    <td><input name="flavor_tag" type="text" id="flavor_tag" maxlength="256" /></td>
  </tr>
  <tr>
    <td>Type:</td>
    <td>
      <select name="type_id" id="type_id">
        <?php
		$type_sql = "SELECT type_id, phrase_tag FROM tea_types WHERE 1";
		$type_res = mysql_query($type_sql, $conn);
		
		while($type_att = mysql_fetch_assoc($type_res))
		{
		  ?>
          <option value="<?= $type_att['type_id'] ?>"><?= Dict_GetPhrase($type_att['phrase_tag'], $conn) ?></option>
          <?php
		}
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td>Color (Hex Code):</td>
    <td><input name="color" type="text" id="color" maxlength="6" value="FFFFFF" /></td>
  </tr>
  <tr>
    <td>Default:</td>
    <td><input name="default" type="checkbox" id="default" value="yes" /></td>
  </tr>
  <tr>
    <td>Price:</td>
    <td><input name="price" type="text" id="price" maxlength="5" /></td>
  </tr>
  <tr>
    <td>Description:</td>
    <td><input name="description" type="text" id="description" /></td>
  </tr>
  <tr>
    <td>Description Tag: (flavor_taro_desc)</td>
    <td><input name="description_tag" type="text" id="description_tag" maxlength="256" /></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="flavor_add_submit" id="flavor_add_submit" value="Add Flavor" />
    </div></td>
    </tr>
</form>
</table>
<p>&nbsp; </p>
</body>
</html>
