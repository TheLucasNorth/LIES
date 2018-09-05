<?php
require 'before.php';
echo '<div class="content contentPage contentPageWelcome" style="overflow: auto;"><article class="textHolder" id="stdTextHolder">';
if (isset($_GET["election"])) {
    $election = htmlspecialchars($_GET["election"]);
    $_POST["election"] = $election;
    $fetchElections = "SELECT `table`, `rolePlain`, `start`, `end`, `seats` FROM elections WHERE id = $election";
    $fetchElectionsQuery = mysqli_query($link, $fetchElections);
    $result = mysqli_fetch_assoc($fetchElectionsQuery);
    $table = $result["table"];
    $rolePlain = $result["rolePlain"];
    $startTime = strtotime($result["start"]);
    $endTime = strtotime($result["end"]);
    $seats = $result["seats"];
    $_POST["table"] = $table;
    $_POST["rolePlain"] = $rolePlain;
    echo "<h3>$rolePlain</h3>";
    echo '<div class="row"><div class="col-md-6">';
    $election = htmlspecialchars($_GET["election"]);
    $voteCheck = "SELECT * FROM $table";
    $voteCheckResult = $link->query($voteCheck);
    $voters = $voteCheckResult->num_rows - 1;
    $candidates = "SELECT `id`, `name` FROM candidates WHERE role='$table'";
    $candidateNames = mysqli_query($link, $candidates);
    while ($name = mysqli_fetch_assoc($candidateNames))
        $names[$name["id"]] = $name["name"];
    $withdrawnCandidates = "SELECT `id`, `name` FROM candidates WHERE withdrawn='y'";
    $withdrawnCandidatesQuery = mysqli_query($link, $withdrawnCandidates);
    while ($withdrawal = mysqli_fetch_assoc($withdrawnCandidatesQuery))
        $withdrawn[] = $withdrawal["id"];
    $getmanifestos = "SELECT `id`, `manifesto` FROM candidates WHERE role='$table'";
    $manifestoQuery = mysqli_query($link, $getmanifestos);
    while ($manifesto = mysqli_fetch_assoc($manifestoQuery))
        $manifestos[$manifesto["id"]] = $manifesto["manifesto"];
    $namesForJSON = array_keys($names);
    $realNamesJSON = json_encode($names);
    $namesJSON = json_encode($namesForJSON);
    $candidateNumber = $candidateNames->num_rows;
    echo "<div>Times below are in epoch format, and must be entered in epoch format. Use a converter such as <a href='https://www.epochconverter.com/'>this one</a> to convert them.</div>";
    echo '<div class="col-md-6"><br><form  id="startTime" method="post" action="processing.php?election=' . $election . '">';
    echo '<input type="text" name="startTime" class="form-control" value=' . $startTime . '>
    <button type="submit" form="startTime" name="updateStartTime" value="updateStartTime" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update Start Time</button></form><br></div>';
    echo '<div class="col-md-6"><br><form  id="endTime" method="post" action="processing.php?election=' . $election . '">';
    echo '<input type="text" name="endTime" class="form-control" value=' . $endTime . '>
    <button type="submit" form="endTime" name="updateEndTime" value="updateEndTime" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update End Time</button></form><br></div>';
    echo '<div class="col-md-12"><br><form  id="seatsForm" method="post" action="processing.php?election=' . $election . '">';
    echo '<input type="number" name="seatsNumber" class="form-control" value=' . $seats . '>
    <button type="submit" form="seatsForm" name="seatsUpdate" value="seatsUpdate" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update Number of Seats</button></form></div><br>';
    if ($voteCheckResult->num_rows > 0) {
        echo "<span><strong>$voters</strong> people have voted in this election.</span><br>";
    } else {
        echo "Nobody has voted in this election yet.";
    }
    echo "There are <strong>$candidateNumber</strong> candidates in this election<br>Candidate names and manifestos are as follows:<br>";
    echo "<strong>Withdrawing a candidate is irreversible.</strong>";
    foreach ($names as $key => $value) {
        echo '<form  id="view' . $key . '" method="post" action="processing.php?election=' . $election . '">';
        echo '<div class="input-group" style="width:100%">
    <input type="hidden" name="id" class="form-control" value="' . $key . '">
    <input type="text" name="name" class="form-control" value="' . $value . '">
    <input type="text" name="manifesto" class="form-control" value="' . $manifestos[$key] . '">
    <input type="hidden" name="role" class="form-control" value="' . $table . '">
    <button type="submit" form="view' . $key . '" name="updateCandidate" value="updateCandidate" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update</button>';
        if (in_array($key, $withdrawn)) {
            echo "Candidate has been withdrawn</div>";
        } else {
            echo ' <button type="submit" form="view' . $key . '" name="deleteCandidate" value="deleteCandidate"  id="admin-button" class="btn admin-button"><i class="fa fa-trash"></i>Delete</button>
</div>';
        }
        echo "</form>";
    }
    echo '<form  id="create" method="post" action="processing.php?election=' . $election . '">';
    echo '<div class="input-group" style="width:100%">
    <input type="text" name="newName" class="form-control" value="">
    <input type="text" name="manifesto" class="form-control" value="">
    <button type="submit" form="create" name="insertCandidate" value="insertCandidate" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Add candidate</button></div>';
    echo "</form>";
    echo '</div><div class="col-md-6">';
    echo "<div>There are $candidateNumber candidates for this role. It is important to set the field names below to reflect the number of candidates. There should be $candidateNumber fields, which should be named grammatically correct. EG field1 should be named First, field2 should be named Second. etc. This is what will be used to populate the 'First Preference', 'Second Preference' lists.</div>";
    $fieldCheck = "SELECT * FROM $table";
    $fieldCheckResult = $link->query($fieldCheck);
    ($fields = mysqli_fetch_assoc($fieldCheckResult));
    array_shift($fields);
    array_pop($fields);
    $fieldsNumber = count($fields);
    echo "There are currently $fieldsNumber fields.";
    foreach ($fields as $key => $value) {
        echo '<form  id="field' . $key . '" method="post" action="processing.php?election=' . $election . '">';
        echo '<div class="input-group" style="width:100%">
    <input type="hidden" name="fieldID" class="form-control" value="' . $key . '">
    <input type="text" name="fieldName" class="form-control" value="' . $key . '">
    <button type="submit" form="field' . $key . '" name="fieldUpdate" value="fieldUpdate" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Update</button>
    <button type="submit" form="field' . $key . '" name="fieldDelete" value="fieldDelete"  id="admin-button" class="btn admin-button"><i class="fa fa-trash"></i>Delete</button>
</div>';
        echo "</form>";
    }
    echo '<form  id="addField" method="post" action="processing.php?election=' . $election . '">';
    echo '<div class="input-group" style="width:100%">
    <input type="hidden" name="table" value="' . $table . '">
    <input type="text" name="newField" class="form-control" value="">
    <button type="submit" form="addField" name="insertField" value="insertField" id="admin-button" class="btn admin-button"><i class="fa fa-folder"></i>Add field</button></div>';
    echo "</form>";
    echo '</div></div><div class="row"></div><div class="col-md-12">';
    echo '<div><h4>Generate BLT file</h4><strong>This file will reflect any withdrawn candidates as is, with no update needed.</strong> It is suitable for use in <a href="https://github.com/Conservatory/openstv">OpenSTV</a>.<br>
<a href="blt.php?election=' . $election . '"><button type="submit" form="BLT" name="generateBLT" value="generateBLT" id="admin-button" class="btn admin-button">Download BLT</button></a></div>';

    require 'election.php';
    echo '</div></div>';
} else {
    echo "<h3>You've followed an invalid pageflow.</h3><br>Please return to the election homepage and choose an election.";
}

require '../after.php';



