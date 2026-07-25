<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link href="stilovi.css" type="text/css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
    <script src="script.js" type="text/javascript"></script>
</head>
<body id="login">
    <p id="odgovor"></p>
    <div id="div1">
        <form action="" onsubmit="return validate()" id="form" method="post" class="forme1">
            Prijavljujete se kao:
            <select name="korisnik" id="select">
            <option value="">--izaberite--</option>
            <option value="administrator">administrator</option>
            <option value="izdavac">izdavac</option> 
            </select> <br>
            Unestite korisnicko ime:
            <input type="text" name="ime" id="ime" placeholder="Korisnicko ime" minlength="3" maxlength="30" value="<?php
            echo htmlspecialchars($_COOKIE['korisnik'] ?? '');?>" required> <br>  <!-- prikaz postavljenog kolacica kao vrednost input polja --> 
            Unesite lozinku:
            <input type="password" name="lozinka" id="lozinka" minlength="8" maxlength="20" placeholder="Lozinka" required ><br>
            <label for="zapamti_me">Zapamti me</label> <input type="checkbox" id="zapamcen" checked>
            <p class="submit"> <input type="submit" value="Prijavite se"> </p>
        </form>
    </div>
    <p id="errorMessage"></p>
</body>
</html>