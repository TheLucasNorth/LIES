<?php

require 'before.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Users and voter validation</h2>
            When someone attempts to access the voter pages, a code snippet is run to check if they have been logged in.
            If they are, it will retrieve their voter ID which is stored as part of the log in data. If they are not
            logged in, they will be automatically redirected to the login form and will be unable to access any of the
            election pages without first signing in.<br>
            Upon attempting to sign in, the login form will validate the input voter ID against a table in the
            application's database, and if the voter ID is found as a permitted record then they will be signed in and
            their voter ID will be stored as a session variable. <strong>If the entered ID code is not found in the
                table they will not be able to sign in.</strong><br>
            When a user accesses a voting page, the page checks for their voter ID and compares it to the table for that
            election. If it is found, it retrieves their vote and displays it in a list of preferences.<br>
            The site has been designed to allow a user to vote multiple times, with votes after the first attempt
            updating their existing vote. When a user submits a vote their voter ID and the timestamp of the submission
            is stored. This makes it possible to identify when votes were submitted, as well as making it possible to
            theoretically delete specific votes - <strong>this can only be done with the user's consent</strong> as they
            will need to provide their voting code. They could also update their vote leaving all preferences as the
            default "no preference set".<br>
            This system bridges the gap between anonymity, as the admin is never aware of which voter ID a particular
            voter has unless specifically told, and delivering features which require knowing if a particular user has
            voted (such as preventing double voting and displaying vote history to users).<br>
            Depending on the capabilities of the GPEW all member emailing system, it is hoped that a pair of values, eg
            a username and password pair, can be used instead of a single code.<br>
            The number of voters in any election is displayed on the admin panel for that election.<br>
            Voters must be manually uploaded in the database admin, an example CSV file for this is included in
            "setup/usersDemo.csv".
        </div>
        <div class="col-md-6">
            <h2>Admin panel, election setup</h2>
            The admin panel is located at /admin from the usual link. It is recommended that the panel is accessed using
            a laptop or desktop, as opposed to a smartphone or tablet.<br>
            Upon attempting to access any page on the admin panel, the page will check if they have logged in as an
            admin. If they are not then they are taken to a login form. This form contains a register option, which will
            create an account. However, it must be manually granted admin rights and so this does not pose a security
            threat. Attempting to log in will check that the username exists, that the password is correct, and that the
            user has been granted admin rights.<br>
            The admin panel has been designed to be user friendly, however some slight issues remain. In the present
            build of the software, updates made <strong>are made</strong> but not displayed. This means it is fine to
            keep making changes, but the data displayed will always be one update out of date. One way to fix this is to
            manually refresh the page, or to click the logo to go back to the index and then reopening the relevant
            page. However, this should not pose an issue to usability as a field can be changed repeatedly.<br>
            Setting up an election is done by clicking the relevant page, and then adding, updating, or removing
            candidate names as needed. This is in the panel on the left hand side of the page.<br>
            It is also necessary - and essential - to update the field names for the election. The number of fields
            should match the number of candidates, and be named correctly eg "first, second, third". The field names
            entered are used to populate the "first preference", "second preference", etc lists and voters will only be
            able to enter as many preferences as there are fields defined in this location.<br>
            The default installation includes seven fields, if there are less candidates then fields can be removed by
            clicking on the remove button.<br>
            Elections can be added and the names of roles can be changed by accessing the "Election Setup" page. Voting
            open and close times can be set on a per-election basis, using the epoch timestamp format.
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Election results</h3>
            Election results are determined by an STV system using the the Weighted Inclusive Gregory Method for
            reallocating surplus votes. The quota for election is (number of votes)/(number of seats + 1) rounded up to
            two decimal places. At each stage of the count, if more than one candidate has surplus votes, the largest
            surplus will be transferred first. All ties are broken randomly.<br>
            Election results can be viewed at any time by logging into the admin panel and choosing the relevant
            election. The number of seats to be elected, ie 1 if there is 1 winner, 2 if there are 2 winners, etc, must
            be entered, and then the election results will be displayed with a round by round breakdown when the admin
            clicks submit.<br>
            Election results are calculated on the client browser, and can be calculated with infinite regularity, and
            are not stored by the server. If you wish to access the results again the same procedure must be followed
            and the results will be recalculated, including any votes submitted in the meantime.<br>
            Therefore, if it is essential to capture results at a particular time and to preserve them, they should be
            calculated and the page should be printed or saved. The raw vote data is displayed before the results,
            allowing for anonymous validation of the results by sharing the individual ballot set.<br>
        </div>
    </div>
</div>
</div>
<span style="align-self: left">Support and feature requests should be sent to Lucas North, contact@lucasnorth.uk</span>