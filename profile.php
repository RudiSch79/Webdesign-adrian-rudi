<?php include 'include/header.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['user']['username'];
$users = include "data/users.php";
$user   = $users[$username];
$profileImage = $users[$username]['profile_picture'];
?>
?>

<body class="d-flex flex-column min-vh-100">

<?php include 'include/footer.php';?>