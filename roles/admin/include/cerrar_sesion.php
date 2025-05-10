<?php
session_start(); 
unset($_SESSION["user_id"]);
unset($_SESSION["username"]);
unset($_SESSION["id_usuario"]);

session_destroy();


session_write_close();


header("Location: ../../../index.php");
exit(); 
?>