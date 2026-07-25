<?php
    require_once("konekcija.php");    /* ukljucivanje datoteke koja sadrzi definiciju konekcije */
    $ima_gresaka=false;
    $datoteka="evidencija_aktivnosti.txt";    /* datoteka u kojoj se cuva evidencija svih aktivnosti na sistemu */
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $kor_ime=trim($_POST["ime2"]);
        $lozinka=trim($_POST["lozinka2"]);
        $potvrda_loz=trim($_POST["potvrda"]);
        if(empty($kor_ime) || empty($lozinka) || empty($potvrda_loz)){   /* provera  da li su poslati trazeni podaci */  
            echo "Niste popunili sva polja.";
            $ima_gresaka=true;
        }
        if(strlen($kor_ime)<3){
            echo "Korisnicko ime mora da sadrzi minimum 3 karaktera";
            $ima_gresaka=true;
        }
        if(strlen($lozinka)<8){
            echo "Lozinka mora da sadrzi minimum 8 karaktera";
            $ima_gresaka=true;
        }
        if($potvrda_loz!==$lozinka){
            echo "Lozinka i potvrda lozinke se ne poklapaju!";
            $ima_gresaka=true;
        }
        if(!$ima_gresaka){     /* ako nema gresaka moze da se nastavi sa daljim tokom provera */
            $upit="SELECT kor_ime, lozinka FROM korisnici WHERE kor_ime=? OR lozinka=SHA2(?, 512)";    /* koriscenje parametrizovanog upita */
            $stmt=$konekcija->prepare($upit);
            if($stmt){
                $stmt->bind_param("ss", $kor_ime, $lozinka);    /* vezivanje promenljivih za parametrizovan upit */
                $stmt->execute();                             /* izvsavanje upita */
                $stmt->store_result();                       /* cuvanje rezultata upita */
            }
            if($stmt->num_rows>0){
                echo "vec postoji";  /* ako korisnik, odnosno izdavac postoji, vraca se odgovor preko AJAX-a vec postoji */
            }            
            else{
                $stmt=$konekcija->prepare("INSERT INTO korisnici(kor_ime, lozinka, tip_korisnika, datum_kreiranja)
                VALUES (?, SHA2(?, 512), 'izdavac', NOW())");
                $stmt->bind_param("ss", $kor_ime, $lozinka);
                if($stmt->execute()){
                    echo "uspesno";   /* rec uspesno se salje kao povratni odgovor preko AJAX-a */
                    file_put_contents($datoteka, date("d.m.Y H:i:s")."--------- Uspesno registovanje novog korisnika: ".$kor_ime."\n", FILE_APPEND);
                }   /* upisivanje uspesne registracije u fajl za evidenciju aktivnosti */
                else {
                    echo "neuspesno";
                    file_put_contents($datoteka, date("d.m.Y H:i:s")."--------- Greska prilikom registracije novog korisnika\n", FILE_APPEND);
                }
            }
            $stmt->close();
            $konekcija->close();   /* zatvaranje konekcije ka bazi */
        }
    }
    else{
        header("Location: dodavanje_korisnika.php");    /* sprecava direktno otvaranje starnice u browser-u i 
                                                        prebacuje korisnika na stranicu za dodavanje korisnika */
        exit();
    }
?>