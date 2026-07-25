<?php
    session_start();
    $korisnik_id=$_SESSION["id_korisnika"];
    $ime_korisnika=$_SESSION["ime_korisnika"];
    require_once("konekcija.php");
    $datoteka="evidencija_aktivnosti.txt";
    if(isset($_POST["naziv"])){
        $naziv=trim($_POST["naziv"]);
        $tip=$_POST["tip"];
        $tiraz=$_POST["tiraz"];
        $isbn=trim($_POST["isbn"]);
        $imena=$_POST["ime_autora"];
        $prezimena=$_POST["prezime"];
        $emailovi=$_POST["email"];
        $biografije=$_POST["biografija"] ?? [];   
        $broj_autora=count($imena);
        for($i=0;$i<$broj_autora;$i++){
            $imena[$i]=trim($imena[$i]);
            $prezimena[$i]=trim($prezimena[$i]);
            $emailovi[$i]=trim($emailovi[$i]);
            if(empty($biografije[$i])){
                $biografije[$i]=NULL;
            }
        }
        for($i=0;$i<$broj_autora;$i++){
            $emailovi[$i]=filter_var($emailovi[$i], FILTER_SANITIZE_EMAIL);
            if(!filter_var($emailovi[$i], FILTER_VALIDATE_EMAIL)){    /* provera ispravnosti formata posaltog email-a/email-ova */ 
                echo "neispravan email";
                exit();
            }
        }
        $upit="INSERT INTO publikacije(naziv, vrsta_publikacije, tiraz, ISBN_ISSN, datum_kreiranja, id_korisnika) 
        VALUES (?, ?, ?, ?, NOW(), ?)";    /* parametrizovani upiti sa placeholder-ima umesto konkretnih vrednosti */
        $upit2="INSERT INTO autori(ime, prezime, email, biografija) VALUES (?, ?, ?, ?)";
        $upit3="INSERT INTO autori_publikacije(id_autora, id_publikacije) VALUES (?, ?)";
        $stmt=$konekcija->prepare($upit);
        if($stmt){
            $stmt->bind_param("ssisi", $naziv, $tip, $tiraz, $isbn, $korisnik_id);
            if($stmt->execute()){
                $izvrseno=0;
                $publikacija_id=$konekcija->insert_id;
                for($i=0;$i<$broj_autora;$i++){
                    $stmt=$konekcija->prepare($upit2);
                    if($stmt){
                        $stmt->bind_param("ssss", $imena[$i], $prezimena[$i], $emailovi[$i], $biografije[$i]);
                        if($stmt->execute()){ 
                            $izvrseno++;
                            $autor_id=$konekcija->insert_id;
                            $stmt2=$konekcija->prepare($upit3);
                            $stmt2->bind_param("ii", $autor_id, $publikacija_id);
                            if(!$stmt2->execute()){
                                echo "Greska prilikom davanja podataka, probajte ponovo";
                            }
                        }
                    }
                    else{
                        echo "Greska prilikom davanja podataka, probajte ponovo";
                    }
                }
                if($izvrseno==$broj_autora){
                    echo "Uspesno ste dodali podatke o publikaciji";
                    file_put_contents($datoteka, date("d.m.Y H:i:s")."--------- Korisnik ".$ime_korisnika." sa id: ".$korisnik_id.
                    " je uspesno dodao novu publikaciju\n", FILE_APPEND);
                }
                else echo "Greska prilikom dodavanja podataka, probajte ponovo";
            }
            else echo "Greska prilikom dodavanja podataka, probajte ponovo";
        }
        $stmt2->close();
        $stmt->close();
        $konekcija->close();
    }
    if(isset($_GET["pretraga"])){
        $upit="SELECT id_publikacije, naziv, vrsta_publikacije, tiraz, ISBN_ISSN, datum_kreiranja FROM publikacije WHERE id_korisnika=$korisnik_id";
        $upit2="SELECT ime, prezime, email, biografija FROM autori JOIN autori_publikacije using(id_autora) WHERE id_publikacije=?";
        $rezultat=$konekcija->query($upit);
        if(!$rezultat){
            die("Greska prilikom prikaza rezultata");
        }
        $broj=$rezultat->num_rows;
        if($broj>0){
            while($red=$rezultat->fetch_assoc()){     /* dohvatanje i ispisivanje rezultata red po red u formi asocijativnog niza */ 
                echo "<div style='display: inline-block'><p>Podaci o publikaciji:</p>";
                echo "Id publikacije: ".$red["id_publikacije"]."<br>";
                $id_publikacije=$red["id_publikacije"];
                echo "Naziv: ".$red["naziv"]."<br>";
                echo "Vrsta publikacije: ".$red["vrsta_publikacije"]."<br>";
                echo "Tiraz: ".$red["tiraz"]."<br>";
                echo "ISBN: ".$red["ISBN_ISSN"]."<br>";
                echo "Datum dodavanja: ".$red["datum_kreiranja"]."<br>";
                echo "</div>";
                $stmt=$konekcija->prepare($upit2);
                $stmt->bind_param("i", $id_publikacije);
                if(!$stmt->execute()){
                    echo "Greska prilikom prikaza rezultata";
                }
                $stmt->bind_result($ime, $prezime, $email, $biografija);
                echo "<div style='display: inline-block; margin-left: 10px'><p>Podaci o autoru/autorima:</p>";
                while($stmt->fetch()){       /* dohvatanje i ispis rezultata */
                    echo "Ime: ".$ime."<br>";
                    echo "Prezime: ".$prezime."<br>";
                    echo "Email: ".$email."<br>";
                    echo "Biografija: ".$biografija."<br>";
                }
                echo "</div><hr>";
            }
        }
        else{
            die("Nema podataka");
        }
        $rezultat->free_result();     /* oslobanjanje memorije zauzete rezultatom upita */
        $stmt->close();
        $konekcija->close();
    }
    if(isset($_GET["pretraga_sve"])){
        $upit1="SELECT id_korisnika, kor_ime FROM korisnici";
        $upit2="SELECT id_publikacije, naziv, vrsta_publikacije, tiraz, ISBN_ISSN, datum_kreiranja FROM publikacije WHERE id_korisnika=?";
        $upit3="SELECT ime, prezime, email, biografija FROM autori JOIN autori_publikacije using(id_autora) WHERE id_publikacije=?";
        $rezultat=$konekcija->query($upit1);
        if(!$rezultat){
            die("Greska prilikom prikaza rezultata");
        }
        $broj=$rezultat->num_rows;     /* cuvanje broja redova vracenih SELECT upitom */ 
        if($broj>0){
            while($red=$rezultat->fetch_assoc()){     /* dohvatanje i ispis rezultata red po red u formi asocijativnog niza */ 
                echo "<p>Id korisnika: ".$red["id_korisnika"]."<br>";
                $id_korisnika=$red["id_korisnika"];
                echo "Ime korisnika: ".$red["kor_ime"]."</p>";
                $stmt=$konekcija->prepare($upit2);
                if(!$stmt){
                    die("Greska prilikom konekcije");
                }
                $stmt->bind_param("i", $id_korisnika);
                if(!$stmt->execute()){
                    die("Greska prilikom prikaza rezultata");
                }
                $rezultat2=$stmt->get_result();
                if($rezultat2->num_rows==0){
                    echo "Korisnik nema unetih podataka o publikacijama";
                }
                else{
                    while($red2=$rezultat2->fetch_assoc()){
                        echo "<div style='display: inline-block; margin-left: 10px'><p>Podaci o publikaciji:</p>";
                        echo "Id publikacije: ".$red2["id_publikacije"]."<br>";
                        $id_publikacije=$red2["id_publikacije"];
                        echo "Naziv: ".$red2["naziv"]."<br>";
                        echo "Vrsta publikacije: ".$red2["vrsta_publikacije"]."<br>";
                        echo "Tiraz: ".$red2["tiraz"]."<br>";
                        echo "ISBN: ".$red2["ISBN_ISSN"]."<br>";
                        echo "Datum kreiranja: ".$red2["datum_kreiranja"]."</div>";
                        $stmt=$konekcija->prepare($upit3);
                        if(!$stmt){
                            die("Greska prilikom konekcije");
                        }
                        $stmt->bind_param("i", $id_publikacije);
                        if(!$stmt->execute()){
                            die("Greska prilikom prikaza rezultata");
                        }
                        $stmt->bind_result($ime, $prezime, $email, $biografija);
                        echo "<div style='display: inline-block; margin-left: 10px'><p>Podaci o autoru/autorima:</p>";
                        while($stmt->fetch()){
                            echo "Ime: ".$ime."<br>";
                            echo "Prezime: ".$prezime."<br>";
                            echo "Email: ".$email."<br>";
                            echo "Biografija: ".$biografija."<br>";
                        }
                        echo "</div>";
                    }
                }
                echo "<hr>";
            }
        }
        else{
            die("Nema podataka");
        }
        $rezultat->free_result();
        $rezultat2->free_result();
        $stmt->close();
        $konekcija->close();
    }
    if(isset($_GET["brisanje"])){
        $unet_podatak=$_GET["podatak"] ?? "";
        $poslato=$_GET["poslato"] ?? "";
        if(empty($unet_podatak) && empty($poslato)){
            echo "Niste uneli kriterijum za brisanje";
        }
        $podatak=$konekcija->real_escape_string($unet_podatak);   /* escapovanje specijalnih znakova u unetom podatku */
        $upit="DELETE FROM publikacije WHERE naziv=? AND id_korisnika=?";
        $upit2="DELETE FROM publikacije WHERE vrsta_publikacije=? AND id_korisnika=?";
        $upit3="DELETE FROM autori WHERE id_autora NOT IN (SELECT DISTINCT id_autora FROM autori_publikacije)";
        if($poslato=="naziv"){
            $stmt=$konekcija->prepare($upit);
            $stmt->bind_param("si", $podatak, $korisnik_id);
        }
        else{
            $stmt=$konekcija->prepare($upit2);
            $stmt->bind_param("si", $podatak, $korisnik_id);
        }
        $stmt->execute();
        if($stmt->affected_rows>0){
            $rezultat=$konekcija->query($upit3);
            if($rezultat){
                echo "uspesno";
                file_put_contents($datoteka, date("d.m.Y H:i:s")."--------- Korisnik ".$ime_korisnika." sa id: ".$korisnik_id.
                " je uspesno obrisao svoju publikaciju\n", FILE_APPEND);
            }
            else echo "neuspesno";
        }
        else echo "neuspesno";
        $stmt->close();
        $konekcija->close();
    }
    if(isset($_POST["promena_lozinke"])){
        $stara_lozinka=$_POST["stara_lozinka"];
        $nova_lozinka=$_POST["nova_lozinka"];
        $potvrda=$_POST["nova_potvrda"];
        if(empty($stara_lozinka) || empty($nova_lozinka) || empty($potvrda)){
            echo "Nisu uneti podaci potrebni za promenu lozinke";
            exit();
        }
        if($nova_lozinka!==$potvrda){
            echo "Nova lozinka se ne poklapa sa potvrdom";
            exit();
        }
        $upit="SELECT id_korisnika FROM korisnici WHERE lozinka=SHA2(?, 512)";
        $stmt=$konekcija->prepare($upit);
        $stmt->bind_param("s", $stara_lozinka);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==1){    /* provera da li je broj vracenih redova SELECT upitom jednak jedan */
            $stmt->bind_result($id_korisnika);
            $stmt->fetch();
            $upit2="UPDATE korisnici SET lozinka=SHA2(?, 512) WHERE id_korisnika=$id_korisnika";
            $stmt=$konekcija->prepare($upit2);
            $stmt->bind_param("s", $nova_lozinka);
            if($stmt->execute()){
                echo "uspesno";
            }
            else echo "neuspesno";
        }
        else echo "greska";
        $stmt->close();
        $konekcija->close();
    }
    if(isset($_GET["brisanje_korisnika"])){
        $korisnik_ime=trim($_GET["korisnicko_ime"]);
        $upit1="SELECT tip_korisnika FROM korisnici WHERE kor_ime=?";
        $upit2="DELETE FROM korisnici WHERE kor_ime=? AND tip_korisnika='izdavac'";
        $stmt=$konekcija->prepare($upit1);
        if($stmt){
            $stmt->bind_param("s", $ime_korisnika);
            if($stmt->execute()){
                $stmt->bind_result($tip_korisnika);
                if($stmt->fetch()){
                    if($tip_korisnika=="administrator"){
                        echo "administrator";
                        exit();
                    }
                    $stmt->close();
                    $stmt2=$konekcija->prepare($upit2);
                    $stmt2->bind_param("s", $ime_korisnika);
                    $stmt2->execute();
                    if($stmt2->affected_rows>0){
                        echo "uspesno";
                        file_put_contents($datoteka, date("d.m.Y H:i:s")."-------- Administrator: ".$ime_korisnika." je obrisao izdavaca "
                        .$korisnik_ime."\n", FILE_APPEND);
                    }
                    else echo "neuspesno";
                    $stmt2->close();
                }
                else echo "ne postoji";
            }
            else echo "neuspesno";
        }
        else echo "neuspesno";
        $konekcija->close();
    }
    if(isset($_GET["admin_dodavanje"])){
        $korisnicko_ime=$_GET["ime_korisnika"] ?? "";
        if(empty($korisnicko_ime)){
            echo "Niste uneli korisnicko ime";
            exit();
        }
        $upit1="SELECT id_korisnika, kor_ime, tip_korisnika FROM korisnici WHERE kor_ime=?";
        $upit2="UPDATE korisnici SET tip_korisnika='administrator' WHERE id_korisnika=?";
        $stmt=$konekcija->prepare($upit1);
        if(!$stmt){
            echo "neuspesno";
        }
        else{
            $stmt->bind_param("s", $korisnicko_ime);
            if($stmt->execute()){
                $stmt->bind_result($id_korisnika, $ime_korisnika, $tip_korisnika);
                if(!$stmt->fetch()){
                    echo "ne postoji";
                    $stmt->close();
                    exit();
                }
                $stmt->close();
                if($tip_korisnika=="administrator"){
                    echo "vec je administrator";
                    exit();
                }
                else{
                    $stmt2=$konekcija->prepare($upit2);
                    if(!$stmt2){
                        echo "neuspesno";
                        exit();
                    }
                    $stmt2->bind_param("i", $id_korisnika);
                    $stmt2->execute();
                    if($stmt2->affected_rows==1){
                        echo "uspesno";
                        file_put_contents($datoteka,  date("d.m.Y H:i:s")."--------- Korisniku ".$korisnicko_ime.
                        " je dodeljena uloga administratora.\n", FILE_APPEND);
                    }
                    else echo "neuspesno";
                    $stmt2->close();
                }
            }
            else{
                echo "neuspesno";
                $stmt->close();
            }
        }
        $konekcija->close();
    }
    if(isset($_POST["statistika"])){
        if(!file_exists($datoteka)){    /* provera da li fajl sa datim imenom odnosno putanjom postoji */
            die("Fajl za pregled statistike ne postoji.");
        }
        $sadrzaj=file_get_contents($datoteka);   /* citanje celog sadrzaja datoteke kao string i cuvanje u promenljivoj $sadrzaj */
        $sadrzaj=str_replace("\n", "<br>", $sadrzaj);  /* zamena svih pojava \n sa <br> u $sadrzaj */
        echo $sadrzaj;
    }
?>