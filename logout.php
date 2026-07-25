<?php
    session_start();
    if(!isset($_SESSION["prijavljen"])){   /* Ukoliko korisnik nije prijavljen, preusmerava se na login.php */
        header("Location: login.php");
        exit();
    }
    $datoteka="evidencija_aktivnosti.txt";
    $ime_korisnika=$_SESSION["ime_korisnika"];
    file_put_contents($datoteka, date("d.m.Y H:i:s")."--------- Korisnik ".$ime_korisnika." se odjavio sa sistema\n", FILE_APPEND);
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        session_unset();        /* unistavanje promenljivih sesije */
        session_destroy();      /* prekin sesije */
        header("Location: login.php");
        exit();
    }
?>