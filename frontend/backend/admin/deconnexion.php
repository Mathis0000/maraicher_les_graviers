<?php
    session_start();
    if(isset($_SESSION['mainSession'])){
        $_SESSION['mainSession']= array();
        session_destroy();
        header('Location: ../../index.html');
    }
    if(isset($_SESSION['secondarySession'])){
        $_SESSION['mainSession']= array();
        session_destroy();
        header('Location: ../../index.html');
    }
   
?>