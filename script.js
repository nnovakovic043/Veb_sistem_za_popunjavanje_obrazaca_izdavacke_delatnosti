const spec_karakteri=[" ", "!", ".","+", "-","*", "/", "\'", "\"", "\\", "&", "$", "^", "?", "[", "]", ";", "#", "%", "<", ">", ":", "{", "}"];
function basic_validation(kor_ime, lozinka, error){
    let ima_gresaka=false;
    if(!isNaN(kor_ime.charAt(0)) && kor_ime!==""){
        error.innerHTML+="Korisnicko ime ne sme da pocinje cifrom!<br>";
        ima_gresaka=true;
    }
    if(!isNaN(lozinka.charAt(0)) && lozinka!==""){
        error.innerHTML+="Lozinka ne sme da pocinje cifrom!<br>";
        ima_gresaka=true;
    }
    for(let i=0;i<kor_ime.length;i++){
        if(spec_karakteri.includes(kor_ime.charAt(i))){
            error.innerHTML+="Korisnicko ime ne sme da sadrzi specijalne karaktere (!, %, ?, #, itd)<br>";
            ima_gresaka=true;
            break;
        }
    }
    for(let i=0;i<lozinka.length;i++){
        if(spec_karakteri.includes(lozinka.charAt(i))){
            error.innerHTML+="Lozinka ne sme da sadrzi specijalne karaktere (!, %, ?, #, itd)<br>";
            ima_gresaka=true;
            break;
        }
    }
    return ima_gresaka;
}
function validate(){
    let forma=document.getElementById("form");
    let selekcija=document.getElementById("select").value;
    let kor_ime=document.getElementById("ime").value.trim();
    let lozinka=document.getElementById("lozinka").value.trim();
    let error=document.getElementById("errorMessage");
    error.innerHTML="";
    let ima_gresaka=basic_validation(kor_ime, lozinka, error);
    if(selekcija==""){
        error.innerHTML+="Niste izabrali tip korisnika!";
        ima_gresaka=true;
    }
    if(!ima_gresaka){
        forma.action="prijava.php";
        return true;
    }
    return false;
}
function validate2(){
    let forma=document.getElementById("form2");
    let kor_ime=document.getElementById("ime2").value.trim();
    let lozinka=document.getElementById("lozinka2").value.trim();
    let potvrda=document.getElementById("potvrda").value.trim();
    let error=document.getElementById("errorMessage2");
    let ima_gresaka=false;
    error.innerHTML="";
    ima_gresaka=basic_validation(kor_ime, lozinka, error);
    if(potvrda!=lozinka){
        error.innerHTML+="Potvrda lozinke se ne slaze sa unetom lozinkom!";
        ima_gresaka=true;
    }
    if(!ima_gresaka){
        forma.action="registracija.php";
        return true;
    }
    return false;
}
function prosiri(){
    let dugmad=document.querySelectorAll(".dugme");
    let kartice=document.querySelectorAll(".kartica");
    let radio=document.querySelectorAll(".radio");
    for(let i=0;i<dugmad.length;i++){
        dugmad[i].addEventListener("click", function(){
            let trenutna="card"+(i+1);
            let kartica=document.getElementById(trenutna);
            kartica.style.height="100%";
            kartica.style.width="100%";
            for(let j=0;j<kartice.length;j++){
                if(kartice[j]!==kartica){
                    kartice[j].style.height="50px";
                    kartice[j].style.width="500px";
                }
            }
        });
    }
    for(let i=0;i<radio.length;i++){
        radio[i].addEventListener("click", function(){
            let izbor=parseInt(radio[i].value);
            let container=document.getElementById("container");
            let autor=document.getElementById("autor");
            let radio_dugmad=document.getElementById("radio_dugmad");
            container.innerHTML="";
            container.appendChild(radio_dugmad);
            for(let j=1;j<=izbor;j++){
                let kopija=autor.cloneNode(true);
                container.appendChild(kopija);
            }
        });
    }   
}
function dodaj(){
    let kriterijum=document.getElementById("kriterijum_za_brisanje").value;
    let container2=document.getElementById("kriterijum");
    container2.innerHTML="";
    if(kriterijum=="naziv"){
        container2.innerHTML="Unesite pun naziv publikacije:";
        let naziv=document.createElement("input");
        naziv.type="text";
        naziv.name="naziv_publikacije";
        naziv.id="naziv";
        naziv.className="obrasci";
        container2.appendChild(naziv);

    }
    if(kriterijum=="vrsta"){
        container2.innerHTML+="<p>Napomena: Ovom opcijom se brisu sve Vase publikacije koje pripadaju izabranoj vrsti</p>";
        container2.innerHTML+="Izaberite vrstu publikacije:";
        let nazivi=["--izaberite--", "knjiga", "udzbenik", "casopis", "ostalo"];
        let vrsta=document.createElement("select");
        vrsta.className="obrasci";
        vrsta.id="vrsta";
        for(let i=0;i<nazivi.length;i++){
            let opcija=document.createElement("option");
            if(i==0) opcija.value="";
            else opcija.value=nazivi[i];
            opcija.text=nazivi[i];
            vrsta.appendChild(opcija);
        }
        container2.appendChild(vrsta);
    }
}
function potvrda(){
        potvrdite=confirm("Da li ste sigurni da zelite da obrisete navedenog korisnika?"+ 
            "(ako to uradite bice obrisani i svi podaci o publikacijama koje je taj korisnik uneo)");
    return potvrdite;
}
$(document).ready(function(){
    $("#form").submit(function(e){
        e.preventDefault();
        if(!validate()) return;
        let tip_korisnika=$("#select").val();
        let ime=$("#ime").val();
        let lozinka=$("#lozinka").val();
        let zapamcen=$("#zapamcen").val();
        $.post("prijava.php",
            {
                korisnik: tip_korisnika, 
                ime: ime, 
                lozinka:lozinka,
                zapamti_me: zapamcen 
            },
            function(response){
                if(response=="izdavac"){
                    window.location="izdavac.php";
                }
                else if(response=="administrator"){
                    window.location="admin.php";
                }
                else{
                    $("#odgovor").html(response);
                    $("#odgovor").show();
                    $("#odgovor").delay(5000);
                    $("#odgovor").fadeOut(2000);
                }
            });
    });
    $("#form2").submit(function(e){
        e.preventDefault();
        if(!validate2()) return;
        let ime=$("#ime2").val();
        let lozinka=$("#lozinka2").val();
        let potvrda=$("#potvrda").val();
        $.post("registracija.php",
        {ime2: ime, lozinka2:lozinka, potvrda: potvrda},
        function(response){
            if(response=="uspesno"){
                response="Uspesno ste se registrovali na sistem!";
                $("#odgovor2").addClass("uspesno");
            }
            else if(response=="neuspesno"){
                response="Neuspesno dodavanje korisnika, probajte ponovo";
                $("#odgovor2").removeClass("uspesno");
                $("#odgovor2").css("background-color","red");
            }
            else if(response=="vec postoji"){
                $("#odgovor2").removeClass("uspesno");
                response="Uneto korisnicko ime i/ili lozinka vec postoji";
            }
            $("#odgovor2").html(response);
            $("#odgovor2").show();
            $("#odgovor2").delay(5000);
            $("#odgovor2").fadeOut(2000);
        });
    });
    let stranica;
    if(window.location=="http://localhost//PVA_projekat/izdavac.php"){
        stranica=1;   
    }
    else if(window.location=="http://localhost//PVA_projekat/admin.php"){
        stranica=2;
    }
    $("#obrazac"+stranica).submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "obrasci.php",
            type: "post",
            data: 
                $(this).serialize(),
            success: function(response){
                $("#potvrda_servera"+stranica).addClass("uspesno");
                $("#potvrda_servera"+stranica).html(response);
            },
            error: function(xhr, status, error){
                $("#potvrda_servera"+stranica).removeClass("uspesno");
                $(this).css("background-color", "red");
                $(this).html(error);
            } 
        }); 
    });
    $("#brisanje"+stranica).submit(function(e){
        e.preventDefault();
        let kriterijum=$("#kriterijum_za_brisanje").val();
        let podatak, poslato;
        if(kriterijum=="naziv"){
            podatak=$("#naziv").val();
            poslato="naziv";
        }
        else{
            podatak=$("#vrsta").val();
            poslato="vrsta";
        }
        $.get(
            "obrasci.php",
            {brisanje: true, podatak: podatak, poslato: poslato},
            function(response){
                if(response=="uspesno"){
                    response="Uspesno ste obrisali po zadatom kriterijumu!";
                    switch(stranica){
                        case 1: $("#potvrda_servera3").css("background-color", "none");
                                $("#potvrda_servera3").addClass("uspesno"); break;
                        case 2: $("#potvrda_servera5").css("background-color", "none");
                                $("#potvrda_servera5").addClass("uspesno"); break;
                    }
                }
                if(response=="neuspesno"){
                    response="Greska prilikom brisanja podataka, probajte ponovo";
                    switch(stranica){
                    case 1: $("#potvrda_servera3").removeClass("uspesno");
                            $("#potvrda_servera3").css("background-color", "red"); break;
                    case 2: $("#potvrda_servera5").removeClass("uspesno");
                            $("#potvrda_servera5").css("background-color", "red"); break;
                    }
                }
                switch(stranica){
                    case 1: $("#potvrda_servera3").html(response); break;
                    case 2: $("#potvrda_servera5").html(response); break;
                }
            }
        );
    });
    $("#promena_lozinke").submit(function(e){
        e.preventDefault();
        let stara_lozinka=$("#stara_lozinka").val();
        let nova_lozinka=$("#nova_lozinka").val();
        let potvrda=$("#nova_potvrda").val();
        $.post("obrasci.php",
            {promena_lozinke: true, stara_lozinka: stara_lozinka, nova_lozinka: nova_lozinka, nova_potvrda: potvrda},
            function(response){
                if(response=="uspesno"){
                    response="Uspesno ste izvrsili promenu lozinke!";
                    $("#potvrda_servera4").css("background-color", "none");
                    $("#potvrda_servera4").addClass("uspesno");
                }
                if(response=="neuspesno"){
                    response="Greska prilikom promene lozinke, pokusajte ponovo";
                     $("#potvrda_servera4").removeClass("uspesno");
                     $("#potvrda_servera4").css("background-color", "red");
                }
                if(response=="greska"){
                    response="Uneli ste pogresnu staru lozinku";
                     $("#potvrda_servera4").removeClass("uspesno");
                     $("#potvrda_servera4").css("background-color", "red");
                }
                $("#potvrda_servera4").html(response);
            }
        );
    });
    $("#brisanje_izdavaca").submit(function(e){
        e.preventDefault();
        if(!potvrda()) return;
        let korisnicko_ime=$("#kor_ime").val();
        $.get("obrasci.php",
            {brisanje_korisnika: true, korisnicko_ime: korisnicko_ime},
            function(response){
                if(response=="uspesno"){
                    response="Uspesno ste obrisali trazenog izdavaca";
                    $("#potvrda_servera6").css("background-color", "none");
                    $("#potvrda_servera6").addClass("uspesno");
                }
                if(response=="administrator"){
                    response="Ne mozete da obrisete korisnika koji je registrovan kao administrator!";
                    $("#potvrda_servera6").removeClass("uspesno");
                    $("#potvrda_servera6").css("background-color", "red");
                }
                if(response=="ne postoji"){
                    response="Ne postoji izdavac sa unetim korisnickim imenom";
                    $("#potvrda_servera6").removeClass("uspesno");
                    $("#potvrda_servera6").css("background-color", "red");
                }
                if(response=="neuspesno"){
                    response="Greska prilikom brisanja izdavaca, pokusajte ponovo";
                    $("#potvrda_servera6").removeClass("uspesno");
                    $("#potvrda_servera6").css("background-color", "red");
                }
                $("#potvrda_servera6").html(response);
            }
        );
    });
    $("#promena_uloge").submit(function(e){
        e.preventDefault();
        let ime_korisnika=$("#ime_kor").val();
        $.get("obrasci.php",
            {admin_dodavanje: true, ime_korisnika: ime_korisnika},
            function(response){
                if(response=="uspesno"){
                    response="Uspesno ste dodelili korisniku ulogu administratora!";
                     $("#potvrda_servera7").addClass("uspesno");
                }
                if(response=="neuspesno"){
                    response="Greska prilikom dodavanja uloge administratora, pokusajte ponovo";
                     $("#potvrda_servera7").removeClass("uspesno");
                     $("#potvrda_servera7").css("background-color", "red");
                }
                if(response=="vec je administrator"){
                    response="Uneti korisnik vec ima ulogu administatora";
                    $("#potvrda_servera7").removeClass("uspesno");
                    $("#potvrda_servera7").css("background-color", "none");
                }
                if(response=="ne postoji"){
                    response="Ne postoji izdavac sa unetim korisnickim imenom";
                    $("#potvrda_servera7").removeClass("uspesno");
                    $("#potvrda_servera7").css("background-color", "red");

                }
                $("#potvrda_servera7").html(response);
            }           
        );
    });
});

