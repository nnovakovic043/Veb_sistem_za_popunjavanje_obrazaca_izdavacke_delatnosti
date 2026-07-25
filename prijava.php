<?php
    require_once("konekcija.php");   /* ukljucivanje datoteke koja sadrzi definiciju konekcije */
    $ima_gresaka=false;
    $datoteka="evidencija_aktivnosti.txt";
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $tip_kor=$_POST["korisnik"];
        $kor_ime=trim($_POST["ime"]);
        $lozinka=trim($_POST["lozinka"]);
        $zapamcen=$_POST["zapamti_me"] ?? "";
        if(empty($kor_ime) || empty($lozinka) || empty($tip_kor)){
            $ima_gresaka=true;
        }                
        if(strlen($kor_ime)<3){
            $ima_gresaka=true;
        }
        if(strlen($lozinka)<8){
            $ima_gresaka=true;
        }
        if(!$ima_gresaka){
            $upit="SELECT id_korisnika, kor_ime, lozinka, tip_korisnika FROM korisnici WHERE kor_ime=? AND lozinka=SHA2(?, 512) AND tip_korisnika=?";
            $stmt=$konekcija->prepare($upit);
            if($stmt){
                $stmt->bind_param("sss",$kor_ime, $lozinka, $tip_kor);
                $stmt->execute();
                $stmt->store_result();
            }
            if($stmt->num_rows!==1){    /* ukoliko je broj vracenih redovova dobijenih SELECT 
                                            upitom razlicit od 1, korisnik nije pronadjen */
                echo "Nije pronadjen korisnik sa unetim podacima ili su uneti podaci neispravni";
            }
            else{
                $stmt->bind_result($id_korisnika, $kor_ime, $lozinka, $tip_kor);
                $stmt->fetch();
                session_start();    /* startovanje sesije */
                $_SESSION["ime_korisnika"]=$kor_ime;      /* kreiranje promenljivih sesije */
                $_SESSION["tip_korisnika"]=$tip_kor;
                $_SESSION["id_korisnika"] = $id_korisnika;
                $_SESSION["prijavljen"]=true;
                if(empty($zapamcen)){
                    setcookie("korisnik", "", time()-3600, "/");
                }
                else{
                    setcookie("korisnik", $kor_ime, time()+86400, "/");  /* postavljane kolacica za pamcenje poslednjeg ulogovanog korisnika */
                }
                $stmt->close();
                $konekcija->close();
                switch($tip_kor){
                    case "izdavac": 
                        echo "izdavac"; 
                        file_put_contents($datoteka, date("d.m.Y H:i:s")."---------- Korisnik ".$kor_ime.
                        " se prijavio na sistem kao izdavac\n", FILE_APPEND); break;
                    case "administrator":
                        echo "administrator";
                        file_put_contents($datoteka, date("d.m.Y H:i:s")."---------- Korisnik ".$kor_ime.
                        " se prijavio na sistem kao administrator\n", FILE_APPEND); break;
                }
            }
        }
    }
?>