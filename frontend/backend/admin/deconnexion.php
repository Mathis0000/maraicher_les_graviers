<?php
    session_start();
    if(isset($_SESSION['mainSession'])){
        $_SESSION['mainSession']= array();
        session_destroy();
        header('Location: ../../index.html');
    }
    else{
        header('Location: ../login.php');
    }
?>