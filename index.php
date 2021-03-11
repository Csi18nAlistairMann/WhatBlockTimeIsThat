<?php
/*
  WhatBlockTimeIsThat
*/

// Set up defaults
$dflt_1st_epoch = 1231466040; // timestamp of genesis block
$dflt_epoch = 1615408080;
$dflt_blockheight = 674052; //matches $dflt_epoch
$dflt_miningperiod = 10.0; // in minutes
// set up others
$ts_now = strtotime('now');

// Handle if arrived with user changes
// Datestamp user wants blockheight for
if (array_key_exists("u_when", $_POST)) {
  $u_orig = $u_when = $_POST['u_when'];
  $ts_when = strtotime($u_when);
  if ($ts_when === false) {
    $ts_when = $dflt_epoch;
  }
  $u_when = date('c', $ts_when);
} else {
  // ISO 8601 date of 'now' if nothing provided
  $ts_when = $ts_now;
  $u_orig = $u_when = date('c', $ts_when);
}
// How fast user says blocks are produced
if (array_key_exists("u_miningperiod", $_POST)) {
  $u_miningperiod = floatval($_POST['u_miningperiod']);
  if ($u_miningperiod < 1)
    $u_miningperiod = 1;
} else {
  $u_miningperiod = $dflt_miningperiod;
}
// The datestamp user says a block was produced
if (array_key_exists("u_epoch", $_POST)) {
  $u_epoch = $_POST['u_epoch'];
  $ts_epoch = strtotime($u_epoch);
  if ($ts_epoch < $dflt_1st_epoch)
    $ts_epoch = $dflt_1st_epoch;
} else {
  $ts_epoch = $u_epoch = $dflt_epoch;
}
// The blockheight user says reached at the datestamp above
if (array_key_exists("u_epoch_blockheight", $_POST)) {
  $u_epoch_blockheight = intval($_POST['u_epoch_blockheight']);
  if ($u_epoch_blockheight < 1)
    $u_epoch_blockheight = 1;
} else {
  $u_epoch_blockheight = $dflt_blockheight;
}

// Run the numbers
$away['secs_since_epoch'] = $ts_when - $ts_epoch;
$away['period'] = $u_miningperiod * 60;
$away['blocks'] = intval($away['secs_since_epoch'] / $away['period']);
$away['height_to_come'] = $u_epoch_blockheight + $away['blocks'];

// Handle showing the last result or error
if ($away['secs_since_epoch'] < 0) {
  echo 'Date to check must not be before \'Last fixed epoch\'';
} else {
  echo '<font size="+2">' . $away['height_to_come'] .
    '</font> is guessed blockheight for \'' . $u_orig . '\' & a block every ' .
    $u_miningperiod . ' minute(s)';
}

// User interaction
//
echo '<hr>';
echo 'Enter another date!<br><br>';

// Form to fill in
echo '<form action="" method="post">';
echo '<label for="u_when">Date, time & timezone: </label>';
echo '<input type="text" id="u_when" name="u_when" value="' .
      addslashes($u_orig) . '">';
echo '<input type="submit" value="Submit"><br>';
echo '<br>';
echo '<label for="u_when">Minutes to mine a block: </label>';
echo '<input type="text" id="u_miningperiod" name="u_miningperiod" value="' .
      addslashes($u_miningperiod) . '"><br>';
echo '<label for="u_epoch">Last fixed epoch: </label>';
echo '<input type="text" id="u_epoch" name="u_epoch" value="' .
      addslashes($u_epoch) . '"><br>';
echo '<label for="u_epoch_blockheight">Blockheight at last fixed epoch: ' .
      '</label>';
echo '<input type="text" id="u_epoch_blockheight" name="u_epoch_blockheight" ' .
      'value="' . addslashes($u_epoch_blockheight) . '">';
echo '<br><br>';
echo "</form>";

// Notes on use
echo <<<EOT
<hr>
Notes:<br>
- Date/Time/Timezone input field can take any English language. If it makes no sense, it'll assume 1970-01-01<br>
- Default representation of date/time/timezone follow <a href="https://en.wikipedia.org/wiki/ISO_8601#Combined_date_and_time_representations">ISO 8601</a><br>
- Service will refuse dates before 2021-03-10 20:28 on the grounds you can look them up elsewhere.<br>
- Submissions are converted to GMT before calculation<br>
- Minutes to mine a block must be 1 minute or longer<br>
- Service uses http not https, submissions are supplied to server. Would be better to implement in Javascript!<br>
EOT;

// Moi
echo <<<EOT
<hr>
Whomped up by Alistair Mann, 2021. Comments to <a href="https://github.com/Csi18nAlistairMann/WhatBlockTimeIsThat">this github</a>
EOT;
?>
