<?php 
    session_start(); 
    if(!isset($_SESSION["prijavljen"])){
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izdavac panel</title>
    <link href="stilovi.css" type="text/css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
    <script src="script.js" type="text/javascript"></script> 
</head>
<body onload="prosiri()">
    <?php    
        echo "<p class='poruke'>Dobrodosli ".$_SESSION["ime_korisnika"]."</p>";
    ?>
    <button class="dugme"> Popunite obrazac </button>
    <button class="dugme"> Pregled podataka o obrascima </button>
    <button class="dugme"> Brisanje podataka </button>
    <button class="dugme"> Promena lozinke</button>
    <form action="logout.php" method="post" id="odjavljivanje">
        <input type="submit" class="odjava" value="Odjavite se">
    </form><br><br><br>
    <div id="card1" class="kartica"> 
        <form action="" method="post" id="obrazac1"> 
            <div class="sadrzaj">   
                <p>Ovde mozete da dodate podatke o publikacijama:<p>
                Unesite naziv:<input type="text" name="naziv" maxlength="50" class="obrasci" required ><br>
                Unesite vrstu publikacije:
                <select class="obrasci" name="tip" id="tip" required>
                    <option value="">--izaberite--</option>
                    <option value="knjiga">knjiga</option>
                    <option value="udzbenik">udzbenik</option>
                    <option value="casopis">casopis</option>
                    <option value="ostalo">ostalo</option>
                </select><br>
                Unesite tiraz:<input type="number" name="tiraz" min="1" max="250" class="obrasci" required><br>
                Unesite ISBN: <input type="text" name="isbn" maxlength="17" class="obrasci" required><br>
            </div>
            <div class="sadrzaj" id="container">
                <div id="radio_dugmad">
                    <p> Unesite podatke o autoru/autorima: </p>
                    Izaberite broj autora:
                    <input type="radio" name="broj_autora" class="radio" value="1" checked>1
                    <input type="radio" name="broj_autora" class="radio" value="2">2
                    <input type="radio" name="broj_autora" class="radio" value="3">3
                </div>
                <div id="autor">
                    Unesite ime:
                    <input type="text" name="ime_autora[]" maxlength="20" class="autori" required><br>
                    Unesite prezime:
                    <input type="text" name="prezime[]" maxlength="20" class="autori" required><br>
                    Unesite email:
                    <input type="email" name="email[]" maxlength="100" class="autori" required><br>
                    Unesite kratku biografiju autora (opciono):<br>
                    <textarea name="biografija[]" rows="5" cols="50" class="autori"></textarea> 
                </div>
            </div>
            <p class="submit2"> <input type="submit" name="slanje" value="Posaljite"> </p>
        </form> 
        <p id="potvrda_servera1" class="potvrde"></p>
    </div>
    <div id="card2" class="kartica"> 
        <p> Ovde mozete da vidite podatke o Vasim poslatim obrascima. </p>
        <form action="obrasci.php" method="get">
            <p class="submit2"><input type="submit" name="pretraga" value="Prikazi"></p>
        </form> 
    </div>
    <div id="card3" class="kartica"> 
        <div class="sadrzaj">
            <p> Ovde mozete da obrisete publikacije koje ste dodali na osnovu izabranog kriterijuma.</p>
            <form action="" method="get" id="brisanje1">
                Izaberite kriterijum za brisanje:<br>
                <select id="kriterijum_za_brisanje" class="obrasci" onchange="dodaj()">
                    <option value="">--izaberite--</option>
                    <option value="naziv">naziv publikacije</option>
                    <option value="vrsta">vrsta publikacije</option>
                </select>
                <div id="kriterijum">
                </div>
                <p><input type="submit" value="Obrisite"></p>
                <p id="potvrda_servera3" class="potvrde"></p>
            </form>
        </div>    
    </div>
    <div id="card4" class="kartica">
        <p>Popunite sva donja polja za promenu lozinke.</p>
        <form action="" method="post" id="promena_lozinke">
            Unesite trenutnu lozinku:
            <input type="password" id="stara_lozinka" minlength="8" maxlength="20" class="obrasci" required><br>
            Unesite novu lozinku:
            <input type="password" id="nova_lozinka" minlength="8" maxlength="20" class="obrasci" required><br>
            Potvrdite novu lozinku:
            <input type="password" id="nova_potvrda" minlength="8" maxlength="20" class="obrasci" required><br>
            <p><input type="submit" value="Promenite lozinku"></p>
        </form>
        <p id="potvrda_servera4" class="potvrde"></p> 
    </div>   
</body>
</html>