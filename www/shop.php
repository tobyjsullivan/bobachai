<? include('initialization.inc.php'); ?>
<? include('header.inc.php'); ?>
<div id="left_section">
<div class="instruction_box">
<p>Welcome to the ingredient shop! Buy ingredients so you can make more interesting bubble teas.</p>
</div>
<?php
if(isset($_POST['product_buy_submit']))
{
	if($_POST['ingredient_type'] == 'flavor')
	{
		$flavor_sql = "SELECT phrase_tag, price FROM tea_flavors WHERE flavor_id='{$_POST['ingredient_id']}' LIMIT 1";
		$flavor_res = mysql_query($flavor_sql);
		
		if($flavor_row = mysql_fetch_assoc($flavor_res))
		{
			if(Data_BankAccountWithdrawl($user_id, $flavor_row['price'], $conn))
			{
			  $purchase_sql = "INSERT INTO ingredient_purchases 
			  				   SET purchase_id='', user_id='$user_id', ingredient_type='flavor', 
							     ingredient_id='{$_POST['ingredient_id']}', purchase_date=NOW(), 
								 purchase_price='{$flavor_row['price']}'";
			  mysql_query($purchase_sql, $conn);
			  	?>
		<fb:success>
			<fb:message><?= Dict_GetPhrase($flavor_row['phrase_tag'], $conn) ?> Purchased</fb:message>
			You have successfully purchased <?= Dict_GetPhrase($flavor_row['phrase_tag'], $conn) ?>.
		</fb:success>
                <?php
			}
			else
			{
			  ?>
		<fb:error>
		  <fb:message>Not enough money</fb:message>
		  You do not have enough money in your bank account to purchase <?= Dict_GetPhrase($flavor_row['phrase_tag'], $conn) ?>.
		</fb:error>
              <?php
			}
		}
	}
	else if($_POST['ingredient_type'] == 'filling')
	{
		$filling_sql = "SELECT phrase_tag, price FROM tea_fillings WHERE filling_id='{$_POST['ingredient_id']}' LIMIT 1";
		$filling_res = mysql_query($filling_sql);
		
		if($filling_row = mysql_fetch_assoc($filling_res))
		{
			if(Data_BankAccountWithdrawl($user_id, $filling_row['price'], $conn))
			{
			  $purchase_sql = "INSERT INTO ingredient_purchases 
			  				   SET purchase_id='', user_id='$user_id', ingredient_type='filling', 
							     ingredient_id='{$_POST['ingredient_id']}', purchase_date=NOW(), 
								 purchase_price='{$filling_row['price']}'";
			  mysql_query($purchase_sql, $conn);
			  	?>
		<fb:success>
			<fb:message><?= Dict_GetPhrase($filling_row['phrase_tag'], $conn) ?> Purchased</fb:message>
			You have successfully purchased <?= Dict_GetPhrase($filling_row['phrase_tag'], $conn) ?>.
		</fb:success>
                <?php
			}
			else
			{
			  ?>
		<fb:error>
		  <fb:message>Not enough money</fb:message>
		  You do not have enough money in your bank account to purchase <?= Dict_GetPhrase($filling_row['phrase_tag'], $conn) ?>.
		</fb:error>
              <?php
			}
		}
	}
}
?>
<div id="ingredient_shop_department_selection">
<?php
$selected_department = "milk_tea";

if(isset($_GET['department']))
{
	$selected_department = $_GET['department'];
}

$selected_page = 1;
if(isset($_GET['page']))
{
	$selected_page = $_GET['page'];
}

$num_products = 0;
if($selected_department == "milk_tea" || $selected_department == "green_tea" || 
	$selected_department == "black_tea" || $selected_department == "blended_juice")
{
	$flavor_sql = "SELECT COUNT(tea_flavors.flavor_id) AS total_flavors 
				   FROM tea_flavors 
				   LEFT JOIN tea_types
				     ON tea_flavors.type_id=tea_types.type_id
				   WHERE tea_types.type_tag='$selected_department'
				     AND tea_flavors.flavor_id NOT IN (
					   SELECT ingredient_id
					   FROM ingredient_purchases
					   WHERE user_id='$user_id'
					     AND ingredient_type='flavor'
					   )";
	$flavor_res = mysql_query($flavor_sql, $conn);
	if($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		$num_products = $flavor_row['total_flavors'];
	}
}
else if($selected_department == "fillings")
{
	$filling_sql = "SELECT COUNT(filling_id) AS total_fillings
					FROM tea_fillings
					WHERE filling_id NOT IN (
					   SELECT ingredient_id
					   FROM ingredient_purchases
					   WHERE user_id='$user_id'
					     AND ingredient_type='filling'
					   )";
	$filling_res = mysql_query($filling_sql, $conn);
	if($filling_row = mysql_fetch_assoc($filling_res))
	{
		$num_products = $filling_row['total_fillings'];
	}
}

$products_per_page = 6;
$total_pages = ($num_products / $products_per_page) + 1;

if($num_products % $products_per_page == 0)
{
	$total_pages--;
}

$first_product = ($selected_page - 1) * $products_per_page;

if($num_products == 0)
{
	$total_pages = 1;
}
?>
<fb:tabs>
  <fb:tab-item href="shop.php?department=milk_tea" title="Milk Teas" <?= $selected_department=="milk_tea"?"selected=\"true\"":"" ?> />
  <fb:tab-item href="shop.php?department=green_tea" title="Green Teas" <?= $selected_department=="green_tea"?"selected=\"true\"":"" ?> />
  <?php
  /*
  <fb:tab-item href="shop.php?department=black_tea" title="Black Teas" <?= $selected_department=="black_tea"?"selected=\"true\"":"" ?> />
  <fb:tab-item href="shop.php?department=blended_juice" title="Blended Juices" <?= $selected_department=="blended_juice"?"selected=\"true\"":"" ?> />
  */
  ?>
  <fb:tab-item href="shop.php?department=fillings" title="Fillings" <?= $selected_department=="fillings"?"selected=\"true\"":"" ?> />
</fb:tabs>
</div>
<div class="page_selection">
<p>
  Page
  <?php
  for($i = 1; $i <= $total_pages; $i++)
  {
  ?>
  <a href="<?= "shop.php?department=".$selected_department."&page=".$i ?>" 
  	<?= ($selected_page==$i)?"class=\"selected_page\"":"" ?>><?=$i ?></a>
  <?php
  }
  ?>
</p>
</div>
<div id="ingredient_shop_products_list">
<?php
if($num_products == 0)
{
?>
<p class="notification">There are no items currently available in this category.</p>
<?php
}
else if($selected_department == "milk_tea" || $selected_department == "green_tea" || 
	$selected_department == "black_tea" || $selected_department == "blended_juice")
{
	$flavor_sql = "SELECT tea_flavors.flavor_id AS flavor_id, tea_flavors.desc_tag AS desc_tag, tea_flavors.price AS price
				   FROM tea_flavors 
				   LEFT JOIN tea_types
				     ON tea_flavors.type_id=tea_types.type_id
				   WHERE tea_types.type_tag='$selected_department'
				     AND tea_flavors.flavor_id NOT IN (
					   SELECT ingredient_id
					   FROM ingredient_purchases
					   WHERE user_id='$user_id'
					     AND ingredient_type='flavor'
					   )
				   ORDER BY price ASC
				   LIMIT $first_product, $products_per_page";
	$flavor_res = mysql_query($flavor_sql, $conn);
	while($flavor_row = mysql_fetch_assoc($flavor_res))
	{
		?>
  <form method="post" action="<?= $URL['facebook']."shop.php?department=".$selected_department."&page=".$selected_page ?>">
  <div class="product_listing">
	<div class="tea_image_thumbnail product_image" style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=".$flavor_row['flavor_id']."&filling_id=01&thumbnail=1" ?>')"></div>
	<div class="product_details">
	  <p class="product_name"><?= Helper_GetTeaName($flavor_row['flavor_id'], $conn) ?></p>
	  <div class="product_description">
		<p><?= Dict_GetPhrase($flavor_row['desc_tag'], $conn) ?></p>
	  </div>
      <input type="hidden" name="ingredient_type" value="flavor" />
      <input type="hidden" name="ingredient_id" value="<?= $flavor_row['flavor_id'] ?>" />
	  <input type="submit" name="product_buy_submit" class="button" id="product_buy_button" value="  Buy  " 
	  	<?php if($flavor_row['price'] > Data_GetBankAccountBalance($user_id, $conn)) { ?> disabled="disabled"<?php } ?>/>
	  <p class="product_price"><?= "$".$flavor_row['price'] ?></p>
	</div>
  </div>
  </form>
		<?php
	}
}
else if($selected_department == "fillings")
{
	$filling_sql = "SELECT filling_id, phrase_tag, desc_tag, price
					FROM tea_fillings
					WHERE filling_id NOT IN (
					  SELECT ingredient_id
					  FROM ingredient_purchases
					  WHERE user_id='$user_id'
					    AND ingredient_type='filling'
					  )
					ORDER BY price ASC
				    LIMIT $first_product, $products_per_page";
	$filling_res = mysql_query($filling_sql, $conn);
	while($filling_row = mysql_fetch_assoc($filling_res))
	{
		?>
  <form method="post" action="<?= $URL['facebook']."shop.php?department=".$selected_department."&page=".$selected_page ?>">
  <div class="product_listing">
	<div class="tea_image_thumbnail product_image" style="background-image:url('<?= $URL["callback"]."tea_pic.gen.php?flavor_id=01&filling_id=".$filling_row['filling_id']."&thumbnail=1" ?>')"></div>
	<div class="product_details">
	  <p class="product_name"><?= Dict_GetPhrase($filling_row['phrase_tag'], $conn) ?></p>
	  <div class="product_description">
		<p><?= Dict_GetPhrase($filling_row['desc_tag'], $conn) ?></p>
	  </div>
      <input type="hidden" name="ingredient_type" value="filling" />
      <input type="hidden" name="ingredient_id" value="<?= $filling_row['filling_id'] ?>" />
	  <input type="submit" name="product_buy_submit" class="button" id="product_buy_button" value="  Buy  " 
	  	<?php if($filling_row['price'] > Data_GetBankAccountBalance($user_id, $conn)) { ?> disabled="disabled"<?php } ?>/>
	  <p class="product_price"><?= "$".$filling_row['price'] ?></p>
	</div>
  </div>
  </form>
		<?php
	}
}
?>
</div>
<div class="page_selection">
<p>
  Page
  <?php
  for($i = 1; $i <= $total_pages; $i++)
  {
  ?>
  <a href="<?= "shop.php?department=".$selected_department."&page=".$i ?>" 
  	<?= ($selected_page==$i)?"class=\"selected_page\"":"" ?>><?=$i ?></a>
  <?php
  }
  ?>
</p>
</div>
</div>
<?php include('footer.inc.php'); ?>