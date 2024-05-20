<?php
session_start();
// Logout logic
if(isset($_GET['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the homepage
    header("Location: ../index.php");
    exit();
    
}

    $loggato = isset($_SESSION['loggato']) ? $_SESSION['loggato'] : false;
    // URL a cui reindirizzare l'utente
    $redirectURL = $loggato ? '../preferiti.php' : '../login/index.html';
   
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

$navbarContent = "";

if ($loggato) {
    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
        or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        $query = "SELECT nome FROM utente WHERE email = $1";
        $result = pg_query_params($dbconn, $query, array($email));

        if ($result) {
            $num_rows = pg_num_rows($result);
            if ($num_rows > 0) {
                $row = pg_fetch_assoc($result);
                $nome = htmlspecialchars($row["nome"], ENT_QUOTES, 'UTF-8');
                $navbarContent = "
                    <div class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Ciao, $nome</a>
                        <div class='dropdown-menu'>
                            <a href='../miei-annunci.php'>I miei annunci</a>
                            <a href='../preferiti.php'>Preferiti</a>
                            <a href='../modifica-password.php'>Modifica password</a>
                            <a href='?logout=true' class='btn btn-primary btn-lg' role='button'>Esci</a>
                        </div>
                    </div>
                ";
            } else {
                $navbarContent = "
                    <a href='../login/index.html' class='navbar-item'>LOGIN</a>
                    
                ";
            }
        } else {
            $navbarContent = "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
        }
        
    } else {
        $navbarContent = "Connessione al database non riuscita.";
    }pg_close($dbconn);
} else {
    $navbarContent = "
        <a href='../login/index.html' class='navbar-item'>LOGIN</a>
        <a href='../registrazione/index.html' class='navbar-item'>REGISTRATI</a>
    ";
}
?>
<!DOCTYPE html> 
<html>
<head>
    <title>AutoWorld</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="./assets/favicon-32x32.png"/>
    <link rel="stylesheet" href="sign-in.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="application/javascript">
         

        const modelliPerMarca = {
            "Audi": ["ModelloA1", "ModelloA3", "ModelloA4"],
            "BMW": ["Serie1", "Serie3", "Serie5"],
        };
        function updateModelloOptions(marcaSelezionata) {
            const modelloSelect = document.getElementById("modello");
            modelloSelect.innerHTML = '<option value="">Seleziona</option>';
            const modelli = modelliPerMarca[marcaSelezionata];
            if (modelli) {
                modelli.forEach(modello => {
                    const option = document.createElement('option');
                    option.text = modello;
                    option.value = modello;
                    modelloSelect.add(option);
                });
                modelloSelect.disabled = false;
            } else {
                modelloSelect.disabled = true;
            }
        }
        function updateMassimo(fromId, toId) {
            var fromValue = document.getElementById(fromId).value;
            var toSelect = document.getElementById(toId);
            var toValue = toSelect.value;

            if (fromValue !== "") {
                toSelect.querySelectorAll("option").forEach(function(option) {
                    if (parseInt(option.value) < parseInt(fromValue)) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            } else {
                toSelect.querySelectorAll("option").forEach(function(option) {
                    option.disabled = false;
                });
            }

            if (toValue !== "" && parseInt(toValue) < parseInt(fromValue)) {
                toSelect.value = fromValue;
            }
        }

        function updateMinimo(fromId, toId) {
            var fromSelect = document.getElementById(fromId);
            var fromValue = fromSelect.value;
            var toValue = document.getElementById(toId).value;

            if (toValue !== "") {
                fromSelect.querySelectorAll("option").forEach(function(option) {
                    if (parseInt(option.value) > parseInt(toValue)) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            } else {
                fromSelect.querySelectorAll("option").forEach(function(option) {
                    option.disabled = false;
                });
            }

            if (fromValue !== "" && parseInt(fromValue) > parseInt(toValue)) {
                fromSelect.value = toValue;
            }
    }

    document.addEventListener('DOMContentLoaded', () => {
            const navbarToggle = document.getElementById('navbar-toggle');
            const navbarMenu = document.getElementById('navbar-menu');

            navbarToggle.addEventListener('click', () => {
                navbarMenu.classList.toggle('active');
            });
        });

$(document).ready(function() {
    // Gestisci il clic sul link "Chi siamo" nella navbar
    $('a[href="#footer"]').click(function(event) {
        // Previene il comportamento predefinito del link
        event.preventDefault();
        
        // Calcola la posizione verticale del footer
        var targetOffset = $('#footer').offset().top;
        
        // Anima lo scorrimento della pagina fino al footer con una durata di 1000ms (1 secondo)
        $('html, body').animate({
            scrollTop: targetOffset
        }, 1000);
    });
});
</script>
    <style> 
        .icon-auto {
            width: 150px; /* Larghezza desiderata */
            height: auto; /* Altezza automaticamente ridimensionata in base alla larghezza */
        }
        form {
            margin: auto;
            font-family: 'Formula1 Display';
            width: 120%;
            margin-left: 150px;
        }
        form label {
            display: inline-block;
            margin-bottom: 5px; /* Riduce lo spazio inferiore dell'etichetta */
        }
        form select {
            margin-top: 5px; /* Sposta la casella di selezione verso l'alto */
        }
        select {
            font-family: 'Formula1 Display', sans-serif; /* Cambia il font delle caselle di selezione */
            font-size: 16px; /* Regola la dimensione del font se necessario */
        }
        input {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        button {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        .container-contattaci {
            display: flex;
            flex-wrap: wrap;
            font-family: 'Formula1 Display';
            padding-top: 50px; /* Aumenta lo spazio sopra il footer */
            padding-bottom: 50px; /* Aumenta lo spazio sotto il footer */
            margin-top: 250px;
        }

        .footer-column {
            flex: 1;
            margin-right: 100px;
            margin-bottom: 20px;
            margin-left: 100px;
            
        }
        .footer-column a {
            color: black; /* Imposta il colore del testo dei link su nero */
            text-decoration: none; /* Rimuove il sottolineato dai link, se presente */
        }
        @media only screen and (max-width: 768px) {
            .form-signin {
                margin-left: 2vw;
            }
            .car-logos-container {
                height: 2em;
            }
            .container-contattaci{
                bottom: 3vh;
            }
            .form-signin input[type="text"],
            .form-signin input[type="number"],
            .form-signin input[type="email"],
            .form-signin input[type="password"],
            .form-signin textarea,
            .form-signin select, 
            .form-signin button[type="submit"],
            .form-signin button[type="reset"] {
                border-radius: 10px; /* Imposta il raggio dell'arrotondamento del bordo */
                padding: 10px; /* Aggiungi spazio intorno al contenuto */
                margin-bottom: 10px; /* Aggiungi spazio tra le caselle */
                border: 1px solid #ccc; /* Aggiungi un bordo */
                margin-left: 0;
                font-family: 'Formula1 Display', sans-serif;
                font-size: 0.5em;
                margin-top: 5px;
            }
            .form-signin label {
                font-size: 0.5em;
                margin-left: 0;
            }
            .container2 {
                margin-left: 20vw;
                margin-right: 20vw;
            }
            .car-logos img {
                margin-top: 1vh;
                height: 2vh;
                margin-right: 0.8vw;
            }
        }
    </style>
</head>
<body class="text-center">

<nav class="navbar">
        <div class="navbar-container">
            <a href="../index.php" class="navbar-logo"><b>AUTOWORLD</b></a>
            <div class="navbar-menu" id="navbar-menu">
                <div class="navbar-item dropdown">
                    <a class="dropdown-toggle"><b>RICERCA</b></a>
                    <div class="dropdown-menu">
                        <a href="../ricerca/ricerca-personalizzata.php">Ricerca personalizzata</a>
                        <a href="../ricerca/vedi-annunci.php">Vedi annunci</a>
                    </div>
                </div>
                <a href="../vendi/index.php" class="navbar-item"><b>VENDI</b></a>
                <a href="#footer" class="navbar-item"><b>CHI SIAMO</b></a>
                <a href="<?php echo $redirectURL; ?>" class="navbar-item"><b>PREFERITI</b></a>
                <div class="navbar-item dropdown" id="auth-menu">
                    <?php echo $navbarContent; ?>
                </div>
            </div>
            <div class="navbar-toggle" id="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <script src="script.js"></script>


    <div class="container2">
        <form name="myForm" action="vedi-annunci.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
            <label for="marca">Marca:</label>
            <select id="marca" name="marca" onchange="updateModelloOptions(this.value)">
                <option value="">Seleziona</option>
                <option value="Audi">Audi</option>
                <option value="BMW">BMW</option>
                <option value="Mercedes">Mercedes-Benz</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Ford">Ford</option>
                <option value="Chevrolet">Chevrolet</option>
                <option value="Nissan">Nissan</option>
                <option value="Hyundai">Hyundai</option>
                <option value="Mazda">Mazda</option>
                <option value="Kia">Kia</option>
                <option value="Subaru">Subaru</option>
                <option value="Fiat">Fiat</option>
                <option value="Volvo">Volvo</option>
                <option value="Jeep">Jeep</option>
                <option value="Land Rover">Land Rover</option>
                <option value="Renault">Renault</option>
                <option value="Peugeot">Peugeot</option>
                <option value="Citroën">Citroën</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Alfa Romeo">Alfa Romeo</option>
                <option value="Lancia">Lancia</option>
                <option value="Maserati">Maserati</option>
                <option value="Jaguar">Jaguar</option>
                <option value="Ferrari">Ferrari</option>
                <option value="Porsche">Porsche</option>
                <option value="Acura">Acura</option>
                <option value="Bentley">Bentley</option>
                <option value="Buick">Buick</option>
                <option value="Cadillac">Cadillac</option>
                <option value="Chrysler">Chrysler</option>
                <option value="Dodge">Dodge</option>
                <option value="Fiat">Fiat</option>
                <option value="GMC">GMC</option>
                <option value="Infiniti">Infiniti</option>
                <option value="Lamborghini">Lamborghini</option>
                <option value="Lexus">Lexus</option>
                <option value="Lincoln">Lincoln</option>
                <option value="Lotus">Lotus</option>
                <option value="Mini">Mini</option>
                <option value="Rolls-Royce">Rolls-Royce</option>
                <option value="Smart">Smart</option>
                <option value="Tesla">Tesla</option>
                <option value="Vauxhall">Vauxhall</option>
                <option value="Volvo">Volvo</option>
            </select> <br>
            <label for="modello">Modello:</label>
            <select id="modello" name="modello" disabled>
                <option value="">Seleziona</option>
            </select><br>
            <label for="prezzo">Prezzo:</label>
            <select id="prezzo_da" type="number" name="prezzo_da" onchange="updateMassimo('prezzo_da', 'prezzo_a')">
                <option value="">Da</option>
                <?php
                for ($i = 500; $i <= 100000; $i += 1000) {
                    echo "<option type='number' value='$i'>" . $i . "€</option>";
                }
                ?>
            </select>
            <select id="prezzo_a" type="number" name="prezzo_a" style="margin-left: 0%;" onchange="updateMinimo('prezzo_da', 'prezzo_a')">
                <option value="">A</option>
                <?php
                for ($i = 500; $i <= 100000; $i += 1000) {
                    echo "<option type='number' value='$i'>" . $i . "€</option>";
                }
                ?>
            </select><br>
            <label for="carrozzeria">Carrozzeria:</label>
            <select id="carrozzeria" name="carrozzeria">
                <option value="">Seleziona</option>
                <option value="City Car">City Car</option>
                <option value="Cabrio">Cabrio</option>
                <option value="Suv/Fuoristrada/Pick-up">Suv/Fuoristrada/Pick-up</option>
                <option value="Station Wagon">Station Wagon</option>
                <option value="Berlina">Berlina</option>
                <option value="Monovolume">Monovolume</option>
            </select><br>
            <label for="anno">Anno:</label>
            <select id="anno_da" type="number" name="anno_da" onchange="updateMassimo('anno_da', 'anno_a')">
                <option value="">Da</option>
                <script>
                    for (let i = 2024; i >= 1900; i--) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select>
            <select id="anno_a" type="number" name="anno_a" style="margin-left: 0%;" onchange="updateMinimo('anno_da', 'anno_a')">
                <option value="">A</option>
                <script>
                    for (let i = 2024; i >= 1900; i--) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select><br>
            <label for="chilometraggio">Chilometraggio:</label>
            <select id="km_da" type="number" name="km_da" onchange="updateMassimo('km_da', 'km_a')">
                <option value="">Da</option>
                <script>
                    for (let i = 0; i <= 200000; i = i + 25000) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select>
            <select id="km_a" type="number" name="km_a" style="margin-left: 0%;" onchange="updateMinimo('km_da', 'km_a')">
                <option value="">A</option>
                <script>
                    for (let i = 0; i <= 200000; i = i + 25000) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select><br>
            <label for="carburante">Carburante:</label>
            <select name="carburante">
                <option value="">Seleziona</option>
                <option value="Benzina">Benzina</option>
                <option value="Diesel">Diesel</option>
                <option value="Ibrida">Ibrida</option>
                <option value="GPL">GPL</option>
                <option value="Metano">Metano</option>
            </select><br>
            <label for="cambio">Cambio:</label>
            <select name="cambio">
                <option value="">Seleziona</option>
                <option value="Manuale">Manuale</option>
                <option value="Automatico">Automatico</option>
                <option value="Semiautomatico">Semiautomatico</option>
            </select><br>
            <label for="potenza">Potenza (CV):</label>
            <input type="number" id="potenza_da" name="potenza_da" onchange="updateMassimo('potenza_da', 'potenza_a')" placeholder="Da" min="0" max="1000">
            <input type="number" id="potenza_a" name="potenza_a" style="margin-left: 0%;" onchange="updateMinimo('potenza_da', 'potenza_a')" placeholder="A" min="0" max="1000"><br>
            <button type="submit" class="btn btn-primary" style="margin-left: 150px; margin-top: 30px; margin-bottom: 30px;">Cerca</button>
            <button type="reset" class="btn btn-primary" style="margin-left: 10px; margin-top: 30px; margin-bottom: 30px;">Reset</button>
        </form>
    </div>

    <div class="container-contattaci" id="footer">
        <div class="footer-column">
            <h2>Chi siamo</h2>
            <p>Our commitment is to provide you with the highest quality products and the best value in the mobile tool industry. Thank you for your continued support of Cornwell Quality Tools and our franchise owners.</p><br><br>
            <p><b>© 2024 Autoworld. All Rights Reserved.</b></p>
        </div>
        <div class="footer-column">
            <h2>Contatti</h2>
            <p>Indirizzo: Via delle Stelle, 123</p>
            <p>Telefono: 0123-456789</p>
            <p>Email: <a href="mailto:info@autoworld.com">info@autoworld.com</a></p>
        </div>
        <div class="footer-column">
            <h2>SEGUICI:</h2>
            <p><a href="https://www.instagram.com/"><img src="../immagini/instagram.png" alt="Instagram" style="width: 20px; height: 20px;">&nbsp;INSTAGRAM</a></p>
            <p><a href="https://twitter.com/"><img src="../immagini/twitter.png" alt="Twitter" style="width: 20px; height: 20px;">&nbsp;TWITTER</a></p>
            <p><a href="https://www.facebook.com/"><img src="../immagini/facebook.png" alt="Facebook" style="width: 20px; height: 20px;">&nbsp;FACEBOOK</a></p>
        </div>
    </div>

    <div class="car-logos-container">
        <div class="car-logos">
            <img src="../immagini/loghiauto/audi.png">
            <img src="../immagini/loghiauto/bmw.png">
            <img src="../immagini/loghiauto/ford.png">
            <img src="../immagini/loghiauto/honda.png">
            <img src="../immagini/loghiauto/kia.png">
            <img src="../immagini/loghiauto/mazda.png">
            <img src="../immagini/loghiauto/mercedes.png">
            <img src="../immagini/loghiauto/toyota.png">
            <img src="../immagini/loghiauto/volkswagen.png">
            <img src="../immagini/loghiauto/hyundai.png">
            <img src="../immagini/loghiauto/fiat.png">
            <img src="../immagini/loghiauto/mg.png">
            <img src="../immagini/loghiauto/peugeot.png">
            <img src="../immagini/loghiauto/opel.png">
            <img src="../immagini/loghiauto/nissan.png">
            <img src="../immagini/loghiauto/renault.png">
            <img src="../immagini/loghiauto/audi.png">
            <img src="../immagini/loghiauto/bmw.png">
            <img src="../immagini/loghiauto/ford.png">
            <img src="../immagini/loghiauto/honda.png">
            <img src="../immagini/loghiauto/kia.png">
            <img src="../immagini/loghiauto/mazda.png">
            <img src="../immagini/loghiauto/mercedes.png">
            <img src="../immagini/loghiauto/toyota.png">
            <img src="../immagini/loghiauto/volkswagen.png">
            <img src="../immagini/loghiauto/hyundai.png">
            <img src="../immagini/loghiauto/fiat.png">
            <img src="../immagini/loghiauto/mg.png">
            <img src="../immagini/loghiauto/peugeot.png">
            <img src="../immagini/loghiauto/opel.png">
            <img src="../immagini/loghiauto/nissan.png">
            <img src="../immagini/loghiauto/renault.png">
        </div>
        <div class="car-logos">
            <img src="../immagini/loghiauto/audi.png">
            <img src="../immagini/loghiauto/bmw.png">
            <img src="../immagini/loghiauto/ford.png">
            <img src="../immagini/loghiauto/honda.png">
            <img src="../immagini/loghiauto/kia.png">
            <img src="../immagini/loghiauto/mazda.png">
            <img src="../immagini/loghiauto/mercedes.png">
            <img src="../immagini/loghiauto/toyota.png">
            <img src="../immagini/loghiauto/volkswagen.png">
            <img src="../immagini/loghiauto/hyundai.png">
            <img src="../immagini/loghiauto/fiat.png">
            <img src="../immagini/loghiauto/mg.png">
            <img src="../immagini/loghiauto/peugeot.png">
            <img src="../immagini/loghiauto/opel.png">
            <img src="../immagini/loghiauto/nissan.png">
            <img src="../immagini/loghiauto/renault.png">
            <img src="../immagini/loghiauto/audi.png">
            <img src="../immagini/loghiauto/bmw.png">
            <img src="../immagini/loghiauto/ford.png">
            <img src="../immagini/loghiauto/honda.png">
            <img src="../immagini/loghiauto/kia.png">
            <img src="../immagini/loghiauto/mazda.png">
            <img src="../immagini/loghiauto/mercedes.png">
            <img src="../immagini/loghiauto/toyota.png">
            <img src="../immagini/loghiauto/volkswagen.png">
            <img src="../immagini/loghiauto/hyundai.png">
            <img src="../immagini/loghiauto/fiat.png">
            <img src="../immagini/loghiauto/mg.png">
            <img src="../immagini/loghiauto/peugeot.png">
            <img src="../immagini/loghiauto/opel.png">
            <img src="../immagini/loghiauto/nissan.png">
            <img src="../immagini/loghiauto/renault.png">
        </div>
    </div>     
</body>
</html>
