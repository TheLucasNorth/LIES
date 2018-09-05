<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$voterID = "";
$voterID_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["voterID"]))) {
        $voterID_err = "Please enter your voting ID.";
    } else {
        $voterID = trim($_POST["voterID"]);
    }

    // Validate credentials
    if (empty($voterID_err)) {
        // Prepare a select statement
        $sql = "SELECT id, voterID FROM users WHERE voterID = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_voterID);

            // Set parameters
            $param_voterID = $voterID;

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
                        header("Location: " . $_SERVER['PHP_SELF']);
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $voterID_err = "Please ensure you enter your voting code correctly.";
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($link);
require "config.php";
$electionData = "SELECT * FROM electionMeta";
$electionDataQuery = mysqli_query($link, $electionData);
while ($data = mysqli_fetch_assoc($electionDataQuery))
    $electionDataResult[$data["id"]] = $data["value"];
?>

    <!DOCTYPE HTML>
    <html lang="en" data-lang-variant="en-uk">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $electionDataResult[1] ?></title>
        <link rel="stylesheet" href="stylesheet.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
        <script type="text/javascript" src="scripts.js"></script>
        <meta name="msapplication-TileColor" content="#e6e6e6">
    </head>
<body>
<div class="over_footer_wrapper" style="padding-top: 10%">
    <div class="container" id="page">
    <div class="row voting-content">

    <main class="col-md-12 well"><h1><?php $electionData = "SELECT * FROM electionMeta";
        $electionDataQuery = mysqli_query($link, $electionData);
        while ($data = mysqli_fetch_assoc($electionDataQuery))
            $electionDataResult[$data["id"]] = $data["value"];
        echo $electionDataResult[1] ?></h1>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please enter your voting code before attempting to vote in these elections.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($voterID_err)) ? 'has-error' : ''; ?>">
                <label>Voting code:</label>
                <input type="text" name="voterID" class="form-control" style="width:50%"
                       value="<?php echo $voterID; ?>">
                <span class="help-block"><?php echo $voterID_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary fancyButton" value="Login">
            </div>
        </form>
    </div>

<?php require 'after.php'; ?>