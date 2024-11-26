<?php 
if(!$_SESSION['user_id']){
    header('Location:index.php?page=login');
    exit();
}
$db = ConnectDb::getInstance();
$user = new User($db);
$user_details = $user->getUserById($_SESSION['user_id']);


include_once dirname(__DIR__) . "/templates/header.php";
?>

<div class="dashboard-container">
    <div class="dashboard-header">
    <h1>Welcome, <?php echo htmlspecialchars($user_details['username']); ?>!</h1>
    </div>
</div>