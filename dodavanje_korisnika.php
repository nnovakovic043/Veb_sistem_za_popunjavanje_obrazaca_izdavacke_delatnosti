<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kreiranje naloga</title>
    <link href="stilovi.css" type="text/css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
    <script src="script.js" type="text/javascript"></script>
</head>
<body id="registracija">
    <p id="odgovor2"></p>
    <div id="div2">
        <form action="" onsubmit="return validate2()" method="post" id="form2" class="forme1">  /* Forma za unos podataka za dodavanje korisnika */
            Unestite korisnicko ime:
            <input type="text" id="ime2" placeholder="Korisnicko ime" minlength="3" maxlength="30" required> <br>
            Unesite lozinku:
            <input type="password" id="lozinka2" minlength="8" maxlength="20" placeholder="Lozinka" required > <br>
            Potvrdite lozinku:
            <input type="password" id="potvrda" minlength="8" maxlength="20" placeholder="Potvrda lozinke" required>
            <p class="submit"> <input type="submit" value="Registrujte se"> </p>
            <p id="napomena"> 
                Napomena: Moguce je registrovati se samo kao izdavac.<br>
                Dodavanje novih administratora moze da vrsi samo korisnik koji je i sam administrator.
            </p>
        </form>
    </div>
    <p id="errorMessage2"></p>
</body>
</html>