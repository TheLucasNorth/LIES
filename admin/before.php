<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("location: login.php");
    exit;
}
require_once "../config.php";

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
    <link rel="stylesheet" href="../stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="scripts.js"></script>
    <meta name="msapplication-TileColor" content="#e6e6e6">
</head>
<body>
<div class="over_footer_wrapper">
    <div class="container" id="page">
        <header id="mainmenu">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <ul class="nav navbar-nav">
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="row voting-content">
            <main class="col-md-12 well"><a href="index.php"><h1><?php echo $electionDataResult[1] ?> Admin</h1></a>