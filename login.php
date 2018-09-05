<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: index.php");
    exit;
}
if (isset($_GET["voter"])) {
    $voter = htmlspecialchars($_GET["voter"]);
    // Include config file
    require_once "config.php";
    $sql = "SELECT id, voterID FROM users WHERE voterID = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_voterID);

        // Set parameters
        $param_voterID = $voter;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $voterID);
                if (mysqli_stmt_fetch($stmt)) {
                    session_start();

                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["voterID"] = $voterID;

                    // Redirect user to welcome page
                    header("Location: index.php");
                }
            }

        }
    }
}

if (!isset($_GET["voter"])) {
    header("Location: voter.php");
}