<?php
session_start();
unset($_SESSION['token']);
session_destroy(); // Destroy session
header('Location: index.php');