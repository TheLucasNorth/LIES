<?php
require '../config.php';
$election = mysqli_real_escape_string($link, $_GET["election"]);
$fetchElections = "SELECT `table`, `rolePlain`, `start`, `end` FROM elections WHERE id = $election";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
$result = mysqli_fetch_assoc($fetchElectionsQuery);
$table = $result["table"];
$rolePlain = $result["rolePlain"];
$election = mysqli_real_escape_string($link, $_GET["election"]);
$voteCheck = "SELECT * FROM $table";
$voteCheckResult = $link->query($voteCheck);
$voters = $voteCheckResult->num_rows - 1;
$candidates = "SELECT id, name FROM candidates WHERE role='$table'";
$candidateNames = mysqli_query($link, $candidates);
$candidateNumber = $candidateNames->num_rows;
$fieldCheck = "SELECT * FROM $table";
$fieldCheckResult = $link->query($fieldCheck);
$fields = mysqli_fetch_assoc($fieldCheckResult);
array_pop($fields);

if (isset($_POST['deleteCandidate'])) {
    $id = mysqli_real_escape_string($link, $_POST["id"]);
    $delete = "UPDATE candidates SET withdrawn = 'y' WHERE `id` = '$id';";
    $deleteQuery = $link->query($delete);
    $_POST["delete"] = false;
    header("location: admin.php?election=$election");
}

if (isset($_POST['updateCandidate'])) {
    $name = mysqli_real_escape_string($link, $_POST["name"]);
    $candidateID = mysqli_real_escape_string($link, $_POST["id"]);
    $manifesto = mysqli_real_escape_string($link, $_POST["manifesto"]);
    $update = "UPDATE candidates SET name = '$name' WHERE `id` = '$candidateID';";
    $updateQuery = $link->query($update);
    $updateManifesto = "UPDATE candidates SET manifesto = '$manifesto' WHERE `id` = '$candidateID';";
    $updateManifestoQuery = $link->query($updateManifesto);
    $_POST["update"] = false;
    header("location: admin.php?election=$election");
}

if (isset($_POST["updateStartTime"])) {
    $newStartTime = htmlspecialchars($_POST["startTime"]);
    $newDateTime = date('Y-m-d H:i:s', $newStartTime);
    $update = "UPDATE elections SET start = '$newDateTime' WHERE `table` = '$table';";
    $updateQuery = $link->query($update);
    $_POST["updateStartTime"] = false;
    header("location: admin.php?election=$election");
}

if (isset($_POST["updateEndTime"])) {
    $newEndTime = htmlspecialchars($_POST["endTime"]);
    $newDateTime = date('Y-m-d H:i:s', $newEndTime);
    $update = "UPDATE elections SET end = '$newDateTime' WHERE `table` = '$table';";
    $updateQuery = $link->query($update);
    $_POST["updateEndTime"] = false;
    header("location: admin.php?election=$election");
}


if (isset($_POST["seatsUpdate"])) {
    $newSeats = mysqli_real_escape_string($link, $_POST["seatsNumber"]);
    $update = "UPDATE elections SET seats = '$newSeats' WHERE `table` = '$table';";
    $updateQuery = $link->query($update);
    $_POST["seatsUpdate"] = false;
    header("location: admin.php?election=$election");
}


if (isset($_POST['insertCandidate'])) {
    $newName = mysqli_real_escape_string($link, $_POST["newName"]);
    $manifesto = mysqli_real_escape_string($link, $_POST["manifesto"]);
    $insert = "INSERT INTO `candidates` (`id`, `role`, `name`, `manifesto`) VALUES (NULL, '$table', '$newName', '$manifesto');";
    $insertQuery = $link->query($insert);
    $_POST["insert"] = false;
    header("location: admin.php?election=$election");
}


if (isset($_POST['fieldDelete'])) {
    $fieldID = $_POST["fieldID"];
    $fieldDelete = "ALTER TABLE $table DROP COLUMN $fieldID;";
    $fieldDeleteQuery = $link->query($fieldDelete);
    $_POST["fieldDelete"] = false;
    header("location: admin.php?election=$election");
}

if (isset($_POST['fieldUpdate'])) {
    $fieldID = $_POST["fieldID"];
    $fieldName = mysqli_real_escape_string($link, $_POST["fieldName"]);
    $fieldUpdate = "ALTER TABLE $table CHANGE COLUMN $fieldID $fieldName INT(11);";
    $fieldUpdateQuery = $link->query($fieldUpdate);
    $_POST["fieldUpdate"] = false;
    header("location: admin.php?election=$election");
}
if (isset($_POST['insertField'])) {
    $table = $_POST["table"];
    $newField = $_POST["newField"];
    end($fields);
    $lastField = key($fields);
    $insert = "ALTER TABLE $table ADD $newField INT( 11 ) DEFAULT 0 after $lastField";
    $insertQuery = $link->query($insert);
    $_POST["insertField"] = false;
    header("location: admin.php?election=$election");
}


if (isset($_POST['updateMeta'])) {
    $meta1 = mysqli_real_escape_string($link, $_POST["meta1"]);
    $meta2 = mysqli_real_escape_string($link, $_POST["meta2"]);
    $meta4 = mysqli_real_escape_string($link, $_POST["meta4"]);
    $meta3 = mysqli_real_escape_string($link, $_POST["meta3"]);
    $update = "REPLACE INTO `electionMeta` (`id`, `value`) VALUES (1, '$meta1'), (2, '$meta2'), (3, '$meta3')(4, '$meta4');";
    $updateQuery = $link->query($update);
    $_POST["updateMeta"] = false;
    header("location: electionSetup.php");
}

if (isset($_POST['updateRole'])) {
    $tableName = mysqli_real_escape_string($link, $_POST["tableName"]);
    $rolePlain = mysqli_real_escape_string($link, $_POST["rolePlain"]);
    $update = "UPDATE elections SET rolePlain = '$rolePlain' WHERE `table` = '$tableName';";
    $updateQuery = $link->query($update);
    $_POST["updateRole"] = false;
    header("location: electionSetup.php");
}

if (isset($_POST['insertRole'])) {
    $newTable = mysqli_real_escape_string($link, $_POST["newTable"]);
    $newRole = mysqli_real_escape_string($link, $_POST["newRole"]);
    $createTable = "CREATE TABLE $newTable LIKE example;";
    if (mysqli_query($link, $createTable)) {
        echo "Table created successfully.<br>";
    } else {
        echo "ERROR: Could not execute $createTable. <br>" . mysqli_error($link);
    }
    $addData = "INSERT INTO $newTable SELECT * FROM example;";
    if (mysqli_query($link, $addData)) {
        echo "Data added successfully.<br>";
    } else {
        echo "ERROR: Could not execute $addData. <br>" . mysqli_error($link);
    }
    $addElection = "REPLACE INTO elections VALUES ('$newTable', '$newRole');";
    if (mysqli_query($link, $addElection)) {
        echo "Election added successfully.<br>";
    } else {
        echo "ERROR: Could not execute $addElection. <br>" . mysqli_error($link);
    }
    header("location: electionSetup.php");
}
