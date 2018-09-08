<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <?php require 'before.php'; ?>
</head>
<body>
<a href="electionSetup.php"><h4>Election Setup Page</h4></a>
<h3>Check on the elections:</h3>
<?php
$fetchElections = "SELECT * FROM elections";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
while ($election = mysqli_fetch_assoc($fetchElectionsQuery))
    $elections[$election["id"]] = $election["rolePlain"];
ksort($elections);
unset($elections[0]);
foreach ($elections as $key => $value) {
    echo "<a href='admin.php?election=$key'><h4>$value</h4></a>";
}
?>
<h3>Number of voters so far:</h3>
<?php
$fetchVoters = "SELECT * FROM users WHERE voted = 1";
$fetchVotersQuery = mysqli_query($link, $fetchVoters);
$numberVoters = $fetchVotersQuery->num_rows;
$fetchElections = "SELECT * FROM elections";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
while ($election = mysqli_fetch_assoc($fetchElectionsQuery))
    $elections[$election["id"]] = $election["table"];
ksort($elections);
unset($elections[0]);
$numberVotes = 0;
foreach ($elections as $key => $value) {
    $voteCheck = "SELECT * FROM $value";
    $voteCheckResult = $link->query($voteCheck);
    $voters = $voteCheckResult->num_rows - 1;
    $numberVotes = $numberVotes + $voters;
}
echo "$numberVoters people have voted, and have cast $numberVotes ballots."
?>
</body>
<?php require '../after.php'; ?>
</html>