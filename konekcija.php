<?php
    $server="localhost";
    $user="root";
    $lozinka="";
    $baza="izdavacka_delatnost";
    $konekcija=new mysqli($server, $user, $lozinka, $baza);    /* kreiranje objekta konekcije preko mysqli klase */
    if($konekcija->connect_error){
        die("Greska pri povezivanju sa bazom: ".$konekcija->connect_error);
    }
    $konekcija->set_charset("utf8");  /* postavljanje skupa karaktera koji ce se koristiti prilikom komunikaciije sa bazom podataka */
?>