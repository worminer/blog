<?php
session_start();

if(!$_SESSION['email'])
{

    header("Location: loginView.phtml");
}

?>


<?php
echo $_SESSION['email'];
?>


<h1><a href="logout.php">Logout here</a> </h1>