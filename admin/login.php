<?php
session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    header("location: index.php");
    exit;
}

require_once "../config.php";

$electionData = "SELECT * FROM electionMeta";
$electionDataQuery = mysqli_query($link, $electionData);
while ($data = mysqli_fetch_assoc($electionDataQuery))
    $electionDataResult[$data["id"]] = $data["value"];
$voterID = $password = "";
$voterID_err = $password_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["voterID"]))) {
        $voterID_err = "Please enter voterID.";
    } else {
        $voterID = trim($_POST["voterID"]);
    }
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter admin password.";
    } else {
        $password = trim($_POST["password"]);
    }
    if (empty($voterID_err) && empty($password_err)) {
        $sql = "SELECT id, voterID, admin FROM users WHERE voterID = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_voterID);
            $param_voterID = $voterID;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $voterID, $admin);
                    if (mysqli_stmt_fetch($stmt)) {
                        // Change the below string to alter the admin password.
                        if ($password === $electionDataResult[3]) {
                            if (!$admin == 0 || $admin !== 0) {
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["admin"] = true;
                                $_SESSION["voterID"] = $voterID;
                                header("location: index.php");
                            } else
                                $password_err = "You are not an admin.";
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $voterID_err = "No account found with that voterID.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
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

                <div class="wrapper">
                    <h2>Login</h2>
                    <p>Please fill in your credentials to login.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($voterID_err)) ? 'has-error' : ''; ?>">
                            <label>Voting Code</label>
                            <input type="text" name="voterID" class="form-control" value="<?php echo $voterID; ?>">
                            <span class="help-block"><?php echo $voterID_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Admin Password</label>
                            <input type="password" name="password" class="form-control">
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Login">
                        </div>
                    </form>
                </div>
