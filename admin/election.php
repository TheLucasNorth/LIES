<?php
$voteCheck = "SELECT * FROM $table";
$voteCheckResult = $link->query($voteCheck);
$voters = $voteCheckResult->num_rows - 1;
$candidates = "SELECT id, name FROM candidates WHERE role='$table'";
$candidateNames = mysqli_query($link, $candidates);
while ($name = mysqli_fetch_assoc($candidateNames))
    $names[$name["id"]] = $name["name"];
$namesForJSON = array_keys($names);
$realNamesJSON = json_encode($names);
$namesJSON = json_encode($namesForJSON);
$candidateNumber = $candidateNames->num_rows;
$candidates = "SELECT id, name FROM candidates WHERE role='$table'";
$candidateNames = mysqli_query($link, $candidates);
while ($names = mysqli_fetch_assoc($candidateNames))
    $realNames[$names["id"]] = $names["name"];
$votesFetch = "SELECT * FROM $table WHERE";
$votesFetchQuery = $link->query($voteCheck);
while ($vote = mysqli_fetch_assoc($votesFetchQuery)) {
    array_shift($vote);
    array_pop($vote);
    $voteArray[] = array_values($vote);
}
array_shift($voteArray);
$voteOutput = json_encode($voteArray);
echo "<br><h4>Calculate results</h4>";
echo "Using the counter below will not take note of any withdrawn candidates, please use the BLT file above with appropriate software if there are any withdrawn candidates in this election.<br><br>";

?>
<!--

Single Transferable Vote Counter <http://paul-lockett.co.uk/stv.html> is copyright (c) 2009 Paul Lockett <mailto:lockett@lavabit.com>

You can redistribute and/or modify the Javascript code in this page under the terms of the Affero GNU General Public Licence (GNU AGPL) as published by the Free Software Foundation.  The code is distributed WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU AGPL for more details.  The licence is available at <http://www.gnu.org/licenses/agpl.html>.

As an additional permission under GNU AGPL version 3 section 7, you may distribute code without the copy of the GNU AGPL normally required by section 4, provided you include this licence notice.

-->

<script type="text/javascript">
    //<![CDATA[
    var votes =<?php echo $voteOutput;?>;
    var totcands = 0;
    var totvotes = 0;
    var candcount = 0;
    var votecount = 0;
    var votenum = [];
    var outputstring = ' ';
    var totlivevotes = 0;
    var wincount = 0;
    var winner;
    var least;
    var mincount;
    var eliminated;
    var elimcount;
    var roundnum = 0;
    var quota;
    var seats =<?php echo $seats ?>;
    var voteweight = [];
    var livecount;
    var elected = [];
    var quotacount;
    var greatest;
    var maxcount;
    var roundelected;
    var names =<?php echo $namesJSON; ?>;
    var votesFromPHP =<?php echo $voters; ?>;
    var candidatesFromPHP =<?php echo $candidateNumber; ?>;
    var votesOutput =<?php echo $voteOutput; ?>;
    var realNames =<?php echo $realNamesJSON; ?>;

    //This function takes the input number of candidates and number of votes, allocates them to variables and moves on to the name input section.
    function voteset(form) {
        seats = +form.inputboxseats.value;
        totcands = candidatesFromPHP;
        totvotes = votesFromPHP;
        if (seats == 0 || totcands == 0 || totvotes == 0) {
            alert("Either no votes, no candidates, or number of winners not declared.");
        } else {
            for (m = 0; m < totvotes; m++) {
                voteweight[m] = 1;
            }
            quota = (Math.ceil(totvotes * 100 / (seats + 1))) / 100;
            votesform();
        }
    }


    //This function controls the input of the votes and outputs the raw votes.
    function votesform() {
        outputstring = '<b>Candidates=' + totcands + ' Seats=' + seats + ' Votes=' + totvotes + ' Quota=' + quota + '<\/b><br \/>Raw votes<br \/>';
        for (k = 0; k < totvotes; k++) {
            outputstring += 'vote ' + (k + 1) + ': ';
            for (j = 0; j < totcands; j++) {
                outputstring += "(" + realNames[votes[k][j]] + ") ";
            }
            outputstring += '<br \/>';
        }
        document.getElementById("bodytext").innerHTML = outputstring;
        countvotes();
    }

    //This function adds "voteend" at each vote array.
    function countvotes() {
        for (v = 0; v < totvotes; v++) {
            votes[v].push("voteend");
        }
        shiftnone();
    }

    //If the number of winners is equal to the number of seats, this function goes to the result.  If not, if the current highest preference in a vote is "none", this function removes it and the process repeats until every vote starts with something other than "none" and it then outputs a list of the votes.
    function shiftnone() {
        if (wincount == seats) {
            result();
        } else {
            roundnum += 1;
            for (v = 0; v < totvotes; v++) {
                if (votes[v][0] == "0") {
                    votes[v].shift();
                    v -= 1
                }
            }
            outputstring += '<p><b>Round ' + roundnum + ' votes<\/b><br \/>';
            for (v = 0; v < totvotes; v++) {
                outputstring += 'vote ' + (v + 1) + ': ';
                for (c = 0; c <= totcands; c++) {
                    if (votes[v][c] !== undefined && votes[v][c] !== "0" && votes[v][c] !== "voteend") {
                        outputstring += "(" + realNames[votes[v][c]] + ") ";
                    }
                }
                outputstring += 'vote value = ' + voteweight[v] + '<br \/>';
            }
            outputstring += '<\/p>';
            document.getElementById("bodytext").innerHTML = outputstring;
            countfirst();
        }
    }

    //This function creates an array which contains the number of highest preference votes that each candidate has and outputs a summary.  If any candidate has enough votes to exceed the quota, it goes to the overquota function, otherwise it goes to the findmin function.
    function countfirst() {
        for (c = 0; c < totcands; c++) {
            votenum[c] = 0;
        }
        for (v = 0; v < totvotes; v++) {
            for (c = 0; c < totcands; c++) {
                if (names[c] == votes[v][0]) {
                    votenum[c] += voteweight[v];
                }
            }
        }
        for (c = 0; c < totcands; c++) {
            outputstring += realNames[names[c]] + ' = ' + votenum[c] + '<br \/>';
        }
        document.getElementById("bodytext").innerHTML = outputstring;
        quotacount = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] > quota) {
                quotacount += 1;
            }
        }
        (quotacount == 0) ? findmin() : overquota();
    }

    //This function works out which candidates have the fewest votes.  If there is only one with the fewest votes, it goes to the function monomin.  If there is a tie at the bottom it goes to multimin.
    function findmin() {
//This loop changes to "none" any vote in any position where the candidate has no first preferences.
        for (v = 0; v < totvotes; v++) {
            for (c = 0; c < totcands; c++) {
                for (q = 0; q < totcands; q++) {
                    if (names[q] == votes[v][c] && votenum[q] == 0) {
                        votes[v][c] = "0";
                    }
                }
            }
        }
//This section counts the number of candidates with first preference votes.  If that number is equal to the number of vacant seats, it goes to the allliveelected function which declares them all elected.  Otherwise it moves on to remove the candidate with the fewest votes.
        livecount = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] > 0) {
                livecount += 1;
            }
        }
        if (livecount + wincount == seats) {
            allliveelected();
        } else {
            least = totvotes;
            for (c = 0; c < totcands; c++) {
                if (votenum[c] < least && votenum[c] > 0) {
                    least = votenum[c];
                }
            }
            outputstring += '<br \/>Fewest votes won by a candidate = ' + least + '.';
            document.getElementById("bodytext").innerHTML = outputstring;
            mincount = 0;
            for (c = 0; c < totcands; c++) {
                if (votenum[c] == least) {
                    mincount += 1;
                }
            }
            outputstring += '<br \/>Number of candidates with the fewest votes = ' + mincount + '.';
            document.getElementById("bodytext").innerHTML = outputstring;
            (mincount == 1) ? monomin() : multimin();
        }
    }

    //When there is only one candidate with the fewest votes, this function outputs that candidate and then goes to the removemin function.
    function monomin() {
        for (c = 0; c < totcands; c++) {
            if (votenum[c] == least) {
                eliminated = c;
            }
        }
        outputstring += '<br \/>' + names[eliminated] + ' is eliminated.';
        document.getElementById("bodytext").innerHTML = outputstring;
        removemin();
    }


    //When there is a tie in last place, this function selects one to eliminate at random, outputs that candidate and then goes to the removemin function.
    function multimin() {
        tiebreakloser = Math.ceil(Math.random() * mincount);
        elimcount = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] == least) {
                elimcount += 1;
                if (elimcount == tiebreakloser) {
                    eliminated = c;
                }
            }
        }
        outputstring += '<br \/>The tiebreaker loser is ' + realNames[names[eliminated]] + '.';
        outputstring += '<br \/>' + realNames[names[eliminated]] + ' is eliminated.';
        document.getElementById("bodytext").innerHTML = outputstring;
        removemin();
    }

    //This function goes through the vote arrays and replaces each instance of the eliminated candidate with "none".  It then goes back to the shiftnone function at the start to begin another round of counting.
    function removemin() {
        for (v = 0; v < totvotes; v++) {
            for (c = 0; c < totcands; c++) {
                if (votes[v][c] == names[eliminated]) {
                    votes[v][c] = "0";
                }
            }
        }
        shiftnone();
    }

    //This function determines if there is a tie in the number of candidates with the most votes above the quota.
    function overquota() {
        greatest = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] > greatest) {
                greatest = votenum[c];
            }
        }
        outputstring += '<br \/>Most votes currently held by a candidate = ' + greatest + '.';
        document.getElementById("bodytext").innerHTML = outputstring;
        maxcount = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] == greatest) {
                maxcount += 1;
            }
        }
        outputstring += '<br \/>Number of candidates with the greatest number of votes = ' + maxcount + '.';
        document.getElementById("bodytext").innerHTML = outputstring;
        roundelected = 0;
        (maxcount == 1) ? monomax() : multimax();
    }

    //This function determines who is elected in the round if there is only one candidate with the highest number of votes.
    function monomax() {
        for (c = 0; c < totcands; c++) {
            if (votenum[c] == greatest) {
                roundelected = c;
            }
        }
        outputstring += '<br \/>' + realNames[names[roundelected]] + '  has exceeded the quota and is elected.  If there are seats remaining to be filled, the surplus will now be reallocated.';
        document.getElementById("bodytext").innerHTML = outputstring;
        electmax();
    }

    //this function determines which candidate to elect in the round when two or more candidates have the greatest number of votes.  It reuses the variables from the multimin function so tiebreakerloser isn't an accurate description in this case.
    function multimax() {
        tiebreakloser = Math.ceil(Math.random() * maxcount);
        elimcount = 0;
        for (c = 0; c < totcands; c++) {
            if (votenum[c] == greatest) {
                elimcount += 1;
                if (elimcount == tiebreakloser) {
                    roundelected = c;
                }
            }
        }
        outputstring += '<br \/>The tiebreaker says the first surplus to be re-allocated is ' + realNames[names[roundelected]] + '\'s.';
        outputstring += '<br \/>' + realNames[names[roundelected]] + ' has exceeded the quota and is elected.  If there are seats remaining to be filled, the surplus will now be reallocated.';
        document.getElementById("bodytext").innerHTML = outputstring;
        electmax()
    }

    //This function adds the name of the elected candidate to the elected array and then reweights their votes and changes their name to "none" in the votes array.
    function electmax() {
        elected[wincount] = names[roundelected];
        wincount += 1;
        for (v = 0; v < totvotes; v++) {
            if (votes[v][0] == names[roundelected]) {
                voteweight[v] *= (votenum[roundelected] - quota) / votenum[roundelected];
            }
        }
        for (v = 0; v < totvotes; v++) {
            for (c = 0; c < totcands; c++) {
                if (votes[v][c] == names[roundelected]) {
                    votes[v][c] = "0";
                }
            }
        }
        shiftnone();
    }

    //When there are as many active candidates as there are seats to fill, this function adds all the active candidates to the elected array.
    function allliveelected() {
        for (c = 0; c < totcands; c++) {
            if (votenum[c] > 0) {
                elected[wincount] = names[c];
                wincount += 1;
            }
        }
        shiftnone();
    }

    //This function announces the winner.
    function result() {
        outputstring += '<p><b>The election is complete and the elected candidates are';
        for (i = 0; i < seats; i++) {
            outputstring += ' (' + realNames[elected[i]] + ')';
        }
        outputstring += '.<\/b><\/p>';
        document.getElementById("bodytext").innerHTML = outputstring;
    }

    //]]>

</script>

<table class="holdertable" cellpadding="5" width="100%">
    <tr>
        <td>
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr valign="top">
                    <td class="w5">
                    </td>
                    <td>
                        <div id="bodytext">
                            <form id="myform" action="" method="get">
                                <div>
                                    Number of Seats:<input type="text" id="inputboxseats" size="4"
                                                           value="<?php echo $seats ?>"/> <input type="button"
                                                                                                 id="button1"
                                                                                                 value="continue"
                                                                                                 onclick="voteset(this.form)"/><br/>
                                </div>
                            </form>
                        </div>
                        <div style="font-weight:bold" id="output"></div>
                        <div class="maintext">

                            <p>The script uses the Weighted Inclusive Gregory Method for reallocating surplus votes. The
                                quota for election is (number of votes)/(number of seats + 1) rounded up to two decimal
                                places. At each stage of the count, if more than one candidate has surplus votes, the
                                largest surplus will be transferred first. All ties are broken randomly.</p>

                        </div>
                    </td>
                    <td class="w5">
                    </td>
                </tr>
            </table>
    </tr>
</table>

<script type="text/javascript">
    document.getElementById("inputboxseats").focus();
</script>
</script>