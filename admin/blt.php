<?php


require '../config.php';
$election = htmlspecialchars($_GET["election"]);
$fetchElections = "SELECT `table`, `rolePlain`, `start`, `end`, `seats` FROM elections WHERE id = $election";
$fetchElectionsQuery = mysqli_query($link, $fetchElections);
$result = mysqli_fetch_assoc($fetchElectionsQuery);
$table = $result["table"];
$rolePlain = $result["rolePlain"];
$startTime = strtotime($result["start"]);
$endTime = strtotime($result["end"]);
$seats = $result["seats"];
$candidates = "SELECT `id`, `name`, `withdrawn` FROM `candidates` WHERE role='$table'";
$candidateNames = mysqli_query($link, $candidates);
while ($name = mysqli_fetch_assoc($candidateNames))
    $names[$name["id"]] = $name["name"];
$candidateNumber = $candidateNames->num_rows;
$candidateIDs[0] = 0;
foreach ($names as $key => $value) {
    $candidateIDs[] = $key;
}

$withdrawnCandidates = "SELECT `id`, `name` FROM candidates WHERE role='$table' && withdrawn='y'";
$withdrawnCandidatesQuery = mysqli_query($link, $withdrawnCandidates);
while ($withdrawal = mysqli_fetch_assoc($withdrawnCandidatesQuery))
    $withdrawn[] = $withdrawal["id"];

$votesFetch = "SELECT * FROM `$table`";
$votesFetchQuery = $link->query($votesFetch);
while ($vote = mysqli_fetch_assoc($votesFetchQuery)) {
    array_shift($vote);
    array_pop($vote);
    $voteArray[] = array_values($vote);
}
array_shift($voteArray);
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=$rolePlain.blt");
echo "$candidateNumber $seats\n";
if ($withdrawnCandidatesQuery->num_rows > 0) {
    foreach ($withdrawn as $key => $value) {
        echo "-";
        echo array_search($value, $candidateIDs);
    }
    echo "\n";
}
foreach ($voteArray as $key => $ballot) {
    echo "1 ";
    foreach ($ballot as $preference => $choice) {
        echo array_search($choice, $candidateIDs);
        echo " ";
    }
    echo "0\n";
}
echo "0\n";
foreach ($names as $id => $name) {
    echo '"' . $name . '"';
    echo "\n";
}

echo '"' . $rolePlain . '"';