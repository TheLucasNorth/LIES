<?php

require 'before.php';
require '../config.php';


echo '<div class="row"><div class="col-md-6"><br><br>';
echo "To the right you can update the name of this set of elections, the base URL for the elections, the admin password, and the URL of your privacy policy.";
echo '</div><div class="col-md-6"><br><br>';

$electionData = "SELECT * FROM electionMeta";
$electionDataQuery = mysqli_query($link, $electionData);
while ($data = mysqli_fetch_assoc($electionDataQuery))
    $electionDataResult[$data["id"]] = $data["value"];

echo '<form  id="electionMeta" method="post" action="processing.php">';
echo '<div class="input-group" style="width:50%">
    <input type="text" name="meta1" class="form-control" value="' . $electionDataResult[1] . '">
    <input type="text" name="meta2" class="form-control" value="' . $electionDataResult[2] . '">
    <input type="text" name="meta3" class="form-control" value="' . $electionDataResult[3] . '">
    <input type="text" name="meta4" class="form-control" value="' . $electionDataResult[4] . '">
    <button type="submit" form="electionMeta" name="updateMeta" value="updateMeta" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update</button>
</div>';
echo "</form>";

echo '</div><div class="col-md-6"><br><br>';

$fetchElections = "SELECT `table`, `rolePlain` FROM elections";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
while ($election = mysqli_fetch_assoc($fetchElectionsQuery))
    $elections[$election["table"]] = $election["rolePlain"];
ksort($elections);
echo "You can alter the plain text names of existing elections below. These are shown on the homepage, the voting page, and in the admin pages as the names of the roles available.";
foreach ($elections as $key => $value) {
    echo '<form  id="view' . $key . '" method="post" action="processing.php">';
    echo '<div class="input-group" style="width:100%">
    <input type="hidden" name="tableName" class="form-control" value="' . $key . '">
    <input type="text" name="rolePlain" class="form-control" value="' . $value . '">
    <button type="submit" form="view' . $key . '" name="updateRole" value="updateRole" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update</button>
</div>';
    echo "</form>";
}

echo '</div><div class="col-md-6"><br><br>';
echo "Here you can add new elections. Please specify a shorthand name which must contain no spaces or special character, and the name of the role/election.";
echo '<form  id="create" method="post" action="processing.php">';
echo '<div class="input-group" style="width:100%">
    <input type="text" name="newTable" class="form-control" value="">
    <input type="text" name="newRole" class="form-control" value="">
    <button type="submit" form="create" name="insertRole" value="insertRole" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Add election</button></div>';
echo "</form>";

echo '</div>
  </div>';

require '../after.php';