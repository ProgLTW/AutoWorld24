<?php
session_start();
if(isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
$loggato = isset($_SESSION['loggato']) ? $_SESSION['loggato'] : false;
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
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="application/javascript">
        const modelliPerMarca = {
            "Audi": ["A1", "A3", "A4"],
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
            $('a[href="#footer"]').click(function(event) {
                event.preventDefault();
                var targetOffset = $('#footer').offset().top;
                $('html, body').animate({
                    scrollTop: targetOffset
                }, 1000);
            });
        });
    </script>
    <style> 
        .container2{
            border-radius: 30px;
            margin-top: 150px;
            margin-bottom: 150px;
            font-family: 'Formula1 Display';
        }
        .icon-auto {
            width: 150px;
            height: auto;
        }
        form {
            margin: auto;
            font-family: 'Formula1 Display';
            width: 120%;
            margin-left: 150px;
        }
        form label {
            display: inline-block;
            margin-bottom: 5px;
        }
        form select {
            margin-top: 5px;
        }
        select {
            font-family: 'Formula1 Display', sans-serif;
            font-size: 16px;
        }
        input {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        button {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        textarea {
            font-family: 'Formula1 Display';
        }
        input[type=file]::-webkit-file-upload-button {
            font-family: 'Formula1 Display'; 
        }
        .container-contattaci {
            display: flex;
            flex-wrap: wrap;
            font-family: 'Formula1 Display';
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .footer-column {
            flex: 1;
            margin-right: 100px;
            margin-bottom: 20px;
            margin-left: 100px;
        }
        .footer-column a {
            color: black;
            text-decoration: none;
        }
        @media only screen and (max-width: 768px) {
            .car-logos-container {
                height: 2em;
            }
            .container-contattaci{
                bottom: 3vh;
            }
            .form-signin {
                margin-left: 2vw;
            }
            .form-signin input[type="text"],
            .form-signin input[type="number"],
            .form-signin input[type="email"],
            .form-signin input[type="password"],
            .form-signin textarea,
            .form-signin select, 
            .form-signin button[type="submit"],
            .form-signin button[type="reset"] {
                border-radius: 10px;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                margin-left: 0;
                font-family: 'Formula1 Display', sans-serif;
                font-size: 0.5em;
                margin-top: 5px;
            }
            .form-signin input[type="file"] {
                font-size: 0.5em;
                margin-left: 0;
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
        <form name="myForm" action="<?php echo isset($_SESSION['loggato']) ? 'vendi.php' : '#'; ?>" method="POST" enctype="multipart/form-data" class="form-signin m-auto" onsubmit="alertRmb()">
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
            <select id="modello" name="modello" disabled required>
                <option value="">Seleziona</option>
            </select><br>
            <label for="prezzo">Prezzo:</label>
            <input id="prezzo" type="number" name="prezzo">
            <label for="trattabile">Trattabile:</label>
            <input type="checkbox" name="trattabile" id="trattabile"><br>
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
            <select id="anno" type="number" name="anno">
                <option value="">Seleziona</option>
                <script>
                    for (let i = 2024; i >= 1900; i--) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select><br>
            <label for="chilometraggio">Chilometraggio:</label>
            <input id="chilometraggio" type="number" name="chilometraggio"><br>
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
            <input id="potenza" type="number" name="potenza"><br>
            <label for="foto">Inserisci immagini:</label>
            <input type="file" name="foto" accept="image/*" multiple><br>
            <label for="descrizione">Inserisci descrizione:</label><br>
            <textarea name="descrizione" cols="30" rows="6" placeholder="es. tagliandi, stato carrozzeria, stato motore, ecc."></textarea><br>

            <button type="submit" class="btn btn-primary"<?php echo isset($_SESSION['loggato']) ? '' : 'disabled'; ?>>Conferma</button>
            <button type="reset" class="btn btn-secondary">Reset</button>

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
            <img src="../immagini/loghiauto/lexus.png">
            <img src="../immagini/loghiauto/mini.png">
            <img src="../immagini/loghiauto/volvo.png">
            <img src="../immagini/loghiauto/landrover.png">
            <img src="../immagini/loghiauto/skoda.png">
            <img src="../immagini/loghiauto/subaru.png">
            <img src="../immagini/loghiauto/alfaromeo.png">
            <img src="../immagini/loghiauto/tesla.png">
            <img src="../immagini/loghiauto/ferrari.png">
            <img src="../immagini/loghiauto/porsche.png">
            <img src="../immagini/loghiauto/lotus.png">
            <img src="../immagini/loghiauto/lamborghini.png">
            <img src="../immagini/loghiauto/lancia.png">
            <img src="../immagini/loghiauto/acura.png">
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
            <img src="../immagini/loghiauto/lexus.png">
            <img src="../immagini/loghiauto/mini.png">
            <img src="../immagini/loghiauto/volvo.png">
            <img src="../immagini/loghiauto/landrover.png">
            <img src="../immagini/loghiauto/skoda.png">
            <img src="../immagini/loghiauto/subaru.png">
            <img src="../immagini/loghiauto/alfaromeo.png">
            <img src="../immagini/loghiauto/tesla.png">
            <img src="../immagini/loghiauto/ferrari.png">
            <img src="../immagini/loghiauto/porsche.png">
            <img src="../immagini/loghiauto/lotus.png">
            <img src="../immagini/loghiauto/lamborghini.png">
            <img src="../immagini/loghiauto/lancia.png">
            <img src="../immagini/loghiauto/acura.png">
        </div>
    </div>
</body>
</html>
