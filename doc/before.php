<?php

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
    <title>Documentation</title>
    <link rel="stylesheet" href="../stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="scripts.js"></script>
    <meta name="msapplication-TileColor" content="#e6e6e6">
</head>
<body>
<div class="container">
    <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo $electionDataResult[2] ?>/admin/">Admin Panel</a></li>
        <li class="active"><a id="homeLink" href="<?php echo $electionDataResult[2] ?>">Elections Home</a></li>
    </ul>
</div>
<div class="over_footer_wrapper">
    <div class="container" id="page">
        <div class="row voting-content">

            <main class="col-md-12 well"><h1>Lucas' Ironically-named Election System [LIES] Documentation</h1>