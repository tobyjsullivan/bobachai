<fb:tabs>
  <fb:tab-item href="index.php" title="<?= Dict_GetPhrase('send_bubble_tea', $conn) ?>" <?= "selected=\"".(Helper_CompareFilenames($_SERVER['PHP_SELF'], "index.php")?"true":"false")."\"" ?> />
  <fb:tab-item href="shop.php" title="Ingredient Shop" <?= "selected=\"".(Helper_CompareFilenames($_SERVER['PHP_SELF'], "shop.php")?"true":"false")."\"" ?> />
  <fb:tab-item href="teas_received.php" title="Received Teas" <?= "selected=\"".(Helper_CompareFilenames($_SERVER['PHP_SELF'], "teas_received.php")?"true":"false")."\"" ?> />
  <fb:tab-item href="teas_sent.php" title="Sent Teas" <?= "selected=\"".(Helper_CompareFilenames($_SERVER['PHP_SELF'], "teas_sent.php")?"true":"false")."\"" ?> />
</fb:tabs>