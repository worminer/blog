<?php
session_start();

if(!$_SESSION['email'])
{

    header("Location: login.phtml");
}

?>


<?php
echo $_SESSION['email'];
?>


<h1><a href="logout.php">Logout here</a> </h1>
