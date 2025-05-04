<?php
  // before set the time stop all Actions
  // check if time zone in your xampp control 
  // config = php.ini
  // find
  // ;date.timezone =
  // add "Asia/Manila"
  // date.timezone = Asia/Manila
  echo 'Current Timezone: ' . date_default_timezone_get() . '<br>';
  echo 'Current Time: ' . date('Y-m-d H:i:s');
?>
