<?php
session_start();
session_destroy();
redirect('/auth/login.php');
?>