<?php
require 'before.php';
echo '<div class="content contentPage contentPageWelcome" style="overflow: auto;"><article class="textHolder" id="stdTextHolder">';

if (isset($_GET["election"])) {
    $election = htmlspecialchars($_GET["election"]);
    $fetchElections = "SELECT `table`, `rolePlain`, `start`, `end` FROM elections WHERE id = $election";
    $fetchElectionsQuery = mysqli_query($link, $fetchElections);
    $result = mysqli_fetch_assoc($fetchElectionsQuery);
    $table = $result["table"];
    $rolePlain = $result["rolePlain"];
    $startTime = strtotime($result["start"]);
    $endTime = strtotime($result["end"]);
    $_POST["table"] = $table;
    $_POST["rolePlain"] = $rolePlain;
    $countElections = "SELECT * FROM elections";
    $countElectionsQuery = mysqli_query($link, $countElections);
    $electionsNumber = $countElectionsQuery->num_rows - 1;
    $next = $election + 1;
    $previous = $election - 1;
    $candidates = "SELECT `id`, `name` FROM candidates WHERE role='$table' && withdrawn='n'";
    $candidateNames = mysqli_query($link, $candidates);
    $realNames[0] = "No preference set";
    while ($names = mysqli_fetch_assoc($candidateNames))
        $sortedNames[$names["id"]] = $names["name"];

    $keys = array_keys($sortedNames);
    shuffle($keys);
    foreach ($keys as $key) {
        $realNames[$key] = $sortedNames[$key];
    }
    $candidateNumber = $candidateNames->num_rows;
    $voterID = $_SESSION["voterID"];
    $_POST["voterID"] = $voterID;
    $voterhash = hash(whirlpool, $voterID);
    $voteCheck = "SELECT * FROM $table WHERE voterID='$voterhash'";
    $voteCheckResult = $link->query($voteCheck);
    $decisionInfo = "SELECT * FROM $table WHERE voterID='0'";
    $decisionInfoQuery = $link->query($decisionInfo);
    if ($previous !== 0) {
        echo '<div style="float:left"><a href="vote.php?election=' . $previous . '">PREVIOUS ELECTION</a></div>';
    }
    if ($next !== $electionsNumber + 1) {
        echo '<div style="float:right"><a href="vote.php?election=' . $next . '">NEXT ELECTION</a></div>';
    }
    echo "<br><h2>$rolePlain</h2>";
    echo "<h3>Manifestos</h3>";
    $getmanifestos = "SELECT `id`, `manifesto` FROM candidates WHERE role='$table' && withdrawn='n'";
    $manifestoQuery = mysqli_query($link, $getmanifestos);
    while ($manifesto = mysqli_fetch_assoc($manifestoQuery))
        $manifestos[$manifesto["id"]] = $manifesto["manifesto"];
    foreach ($sortedNames as $key => $value) {
        echo '<a href="' . $manifestos[$key] . '"><h4>' . $value . '</h4></a>';
    }
    $votersCheck = "SELECT * FROM $table";
    $votersCheckResult = $link->query($votersCheck);
    $voters = $votersCheckResult->num_rows - 1;

    if (time() >= $startTime && time() < $endTime) {

        echo "<br>Number of people to vote for this role: $voters<br><br>";
        if ($voteCheckResult->num_rows > 0) {
            // check if the voterID is already registered in this election, and if so retrieve and display how they voted
            while ($row = mysqli_fetch_assoc($voteCheckResult)) {
                array_shift($row);
                array_pop($row);

                echo "<h4>You've already voted in this election. Here's how you voted:</h4>";

                foreach ($row as $preference => $vote) {
                    echo "$preference Preference: $realNames[$vote]<br>";
                }
                echo "You can update this by voting again below, which you're welcome to do until the close of voting.<br></P>";
            }
        }
        echo '<div id=cochairs"><h3>Vote now for ' . $rolePlain . '!</h3><br>';

// election module vote recorder
        echo "There are <strong>$candidateNames->num_rows</strong> candidates in this election.<br>";
        echo '<form  method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?election=' . $election . '">';
        while ($result = mysqli_fetch_assoc($decisionInfoQuery)) {
            array_shift($result);
            array_pop($result);
            foreach ($result as $preference => $vote) {
                echo '<label for="first-preference">' . $preference . ' Preference: </label>
<select name="' . $preference . '" class="hello" id="first-preference" required>';
                foreach ($realNames as $key => $value) {
                    echo '<option value=' . $key . '>' . $value . '</option>';
                }
                echo '</select><br>';
            }
            echo '<button class="fancyButton" id="resetddls">Reset Options</button>
<input type="submit" id="voteButton" class="fancyButton voteButton" name="submit" value="Vote Now">
</form>
</div>';
            if (isset($_POST["submit"])) {
                $fields = $_POST;
                unset($fields["submit"]);
                unset($fields["table"]);
                unset($fields["rolePlain"]);
                unset($fields["voterID"]);
                $columns = implode(", ", array_keys($fields));
                $values = implode("', '", array_values($fields));

                $sql = "REPLACE INTO $table ($columns, voterID) VALUES ('$values', '$voterhash');";
                $setVoted = " UPDATE `users` SET voted = 1 WHERE `voterID` = '$voterID';";
                if ($conn->query($sql) === TRUE) {
                    $updateQuery = $link->query($setVoted);
                    echo "You have successfully voted for $rolePlain";
                    echo "<br>Here's how you voted, you can update your vote by returning to this page and voting again before the close of voting.<br>";
                    unset($fields["voterID"]);
                    foreach ($fields as $key => $value) {
                        echo "$key Preference: ";
                        echo $realNames["$value"];
                        echo "<br>";
                    }

                } else {
                    echo "Error, please contact the ERO and quote: " . $sql . "<br>" . $conn->error;
                }

            }
        };
    } elseif (time() < $startTime) {
        echo "Voting is not yet open in this election.";
    } elseif (time() > $endTime) {
        echo "Voting has closed in this election.";
        echo "<br>Number of people to vote for this role: $voters";

    }
} else {
    echo "<h3>You've followed an invalid pageflow.</h3><br>Please return to the election homepage and choose an election.";
}
require 'after.php';