<?php
require 'before.php';

echo '<div class="content contentPage contentPageWelcome" style="overflow: auto;"><article class="textHolder" id="stdTextHolder">';

echo "You're all set to start voting! Click the links below to be taken to the election for each role.<br>";

$fetchElections = "SELECT * FROM elections";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
while ($election = mysqli_fetch_assoc($fetchElectionsQuery))
    $elections[$election["id"]] = $election["rolePlain"];
ksort($elections);
unset($elections[0]);
foreach ($elections as $key => $value) {
    echo "<a href='vote.php?election=$key'><h4>$value</h4></a>";
}

require 'after.php';