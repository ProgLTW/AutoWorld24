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
    // Controlla se l'utente è loggato e recupera l'email
    if ($loggato) {
        // Recupera l'email dell'utente in sessione
        $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

        // Controlla se l'email è stata recuperata correttamente
        if ($email) {
            // Connettiti al database
            $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                or die('Could not connect: ' . pg_last_error());

            // Verifica la connessione al database
            if ($dbconn) {
                // Esegui la query per recuperare gli annunci preferiti dell'utente
                $query_preferiti = "SELECT preferiti FROM utente WHERE email = $1";
                $result_preferiti = pg_query_params($dbconn, $query_preferiti, array($email));

                // Controlla se la query è stata eseguita correttamente
                if ($result_preferiti) {
                    // Estrai l'array degli ID degli annunci preferiti
                    $row_preferiti = pg_fetch_assoc($result_preferiti);
                    $preferiti = $row_preferiti['preferiti'];

                    // Trasforma la stringa JSON in un array PHP se non è vuota
                    if ($preferiti) {
                        $preferiti_array = json_decode($preferiti, true);
                    } else {
                        // Se l'array dei preferiti è vuoto, inizializza un array vuoto
                        $preferiti_array = array();
                    }
                } else {
                    // Gestisci eventuali errori nella query
                    echo "Errore durante l'esecuzione della query per recuperare gli annunci preferiti: " . pg_last_error($dbconn);
                }

                // Chiudi la connessione al database
                pg_close($dbconn);
            } else {
                // Gestisci eventuali errori nella connessione al database
                echo "Connessione al database non riuscita.";
            }
        } else {
            // Gestisci il caso in cui l'email dell'utente non è stata recuperata correttamente dalla sessione
            echo "Email dell'utente non trovata nella sessione.";
        }
    }


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
    </script>
    <script>
        $(document).ready(function() {
    $('.heart-icon').click(function() {
        var annuncioId = $(this).data('annuncio-id');
        var isFavorite = $(this).hasClass('filled');
        var isLogged = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;
        
        // Se l'utente non è loggato, reindirizzalo alla pagina di login
        if (!isLogged) {
            window.location.href = '../login/index.html';
            return;
        }

        // Cambia lo stato del cuore (pieno o vuoto)
        $(this).toggleClass('filled');

        // Invia una richiesta AJAX per aggiungere o rimuovere l'annuncio dai preferiti
        $.ajax({
            url: '../aggiorna_preferito.php',
            type: 'POST',
            data: { id: annuncioId, checked: !isFavorite }, // Inverti lo stato del preferito
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
            const navbarToggle = document.getElementById('navbar-toggle');
            const navbarMenu = document.getElementById('navbar-menu');

            navbarToggle.addEventListener('click', () => {
                navbarMenu.classList.toggle('active');
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
            font-size: 1em;
            font-family: 'Formula1 Display', sans-serif;
        }
       
        input {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        button {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        .item1{grid-area: filtri; }
        .item2{grid-area: annunci; }

        .col-container {
            display: flex;
            gap: 10px;
            /*background-color: #2c2c2c96;*/
            padding: 10px;
            margin-top: 50px;
            margin: 30px;
        }

        .item1{
            font-family: 'Formula1 Display';
            font-size: 15px;
            margin-top: 5vh;
            text-align: left;
            color: black;
            width: 25em; /* Larghezza fissa per la colonna sinistra */
            padding: 20px;
            background-color: orange;
            border-radius: 10px;
        }
        
        .item2 {
            border-radius: 10px;
            font-family: 'Formula1 Display';
            font-size: 20px;
            margin-top: 5vh;
            text-align: left;
            color: white;
            flex: 1;
            padding: 0.5em;
            border: 1px solid orange;
            background-color: #2c2c2c96;
        }

        #searchForm {            
            border-radius: 10px;
            margin: 0 auto; /* Centra il form orizzontalmente */
        }


        .container3 {
            display: flex;
            border-radius: 10px;
            font-family: 'Formula1 Display';
            color: orange;
            margin: 0 auto;
            background-color: #2c2c2cea;
            border: 1px solid orange;
        }

        .foto {
            /* Imposta la larghezza massima al 30% del contenitore */
            margin-right: 1vw;
        }

        .foto img {
            margin-top: 20px;
            width: 100%; /* Immagine al 100% della larghezza del contenitore */
            border-radius: 10px;
        }

        .caratteristiche {
            flex: 1; /* Le caratteristiche occupano il 50% dello spazio */
            padding: 1vh;
            font-size: 1em;
        }
        

        .buy-button {
            background-color: orange;
            border: none;
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 5px;
            float: right;
            right: 0;
            top: 0px; /* Altezza del pulsante Details */
        }

        .details-button {
            background-color: orange;
            border: none;
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 5px;
            position: relative;
        }
        .btn-primary:not(.details-button) {
            margin-left: 18vw; 
            margin-top: 4vh; 
            margin-bottom: 10vh;
        }
        
        @media only screen and (max-width: 768px) {
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
            }
            .item1{
                width: 5em; /* Larghezza fissa per la colonna sinistra */
            }
            .form-signin option {
                width: 0.5vw;
            }
            .caratteristiche { /* Le caratteristiche occupano il 50% dello spazio */
                font-size: 0.5em;
            }
            .item2 {
                padding: 0.25em;
            }
            .car-logos-container {
                height: 2em;
            }
            .btn-primary:not(.details-button) {
                margin-left: 18vw; 
                margin-top: 4vh; 
                margin-bottom: 10vh;
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


    <div class="col-container">
        <div class="item1">
            <?php
                // Recupera i valori inviati dal form
                $marca = isset($_POST['marca']) ? $_POST['marca'] : '';
                $modello = isset($_POST['modello']) ? $_POST['modello'] : '';
                $prezzoDa = isset($_POST['PrezzoDa']) ? $_POST['PrezzoDa'] : null;
                $prezzoA = isset($_POST['PrezzoA']) ? $_POST['PrezzoA'] : null;
                $carrozzeria = isset($_POST['carrozzeria']) ? $_POST['carrozzeria'] : '';
                $annoDa = isset($_POST['AnnoDa']) ? $_POST['AnnoDa'] : '';
                $annoA = isset($_POST['AnnoA']) ? $_POST['AnnoA'] : '';
                $kmDa = isset($_POST['KmDa']) ? $_POST['KmDa'] : '';
                $kmA = isset($_POST['KmA']) ? $_POST['KmA'] : '';
                $carburante = isset($_POST['carburante']) ? $_POST['carburante'] : '';
                $cambio = isset($_POST['cambio']) ? $_POST['cambio'] : '';
                $potenzaDa = isset($_POST['PotenzaDa']) ? $_POST['PotenzaDa'] : '';
                $potenzaA = isset($_POST['PotenzaA']) ? $_POST['PotenzaA'] : '';
            ?>


            <form name="searchForm" action="vedi-annunci.php" method="POST" class="form-signin m-auto" style="margin-left: 0;">

                    <label for="marca">Marca:</label>
                    <select id="marca" name="marca" onchange="updateModelloOptions(this.value)" style="width: 12vw">
                        <option value="" <?php if($marca == '') echo 'selected="selected"'; ?>>Seleziona</option>
                        <option value="Audi" <?php if($marca == 'Audi') echo 'selected="selected"'; ?>>Audi</option>
                        <option value="BMW" <?php if($marca == 'BMW') echo 'selected="selected"'; ?>>BMW</option>
                        <option value="Mercedes" <?php if($marca == 'Mercedes') echo 'selected="selected"'; ?>>Mercedes-Benz</option>
                        <option value="Volkswagen" <?php if($marca == 'Volkswagen') echo 'selected="selected"'; ?>>Volkswagen</option>
                        <option value="Toyota" <?php if($marca == 'Toyota') echo 'selected="selected"'; ?>>Toyota</option>
                        <option value="Honda" <?php if($marca == 'Honda') echo 'selected="selected"'; ?>>Honda</option>
                        <option value="Ford" <?php if($marca == 'Ford') echo 'selected="selected"'; ?>>Ford</option>
                        <option value="Chevrolet" <?php if($marca == 'Chevrolet') echo 'selected="selected"'; ?>>Chevrolet</option>
                        <option value="Nissan" <?php if($marca == 'Nissan') echo 'selected="selected"'; ?>>Nissan</option>
                        <option value="Hyundai" <?php if($marca == 'Hyundai') echo 'selected="selected"'; ?>>Hyundai</option>
                        <option value="Mazda" <?php if($marca == 'Mazda') echo 'selected="selected"'; ?>>Mazda</option>
                    </select> <br>
                    <label for="modello">Modello:</label>
                    <select id="modello" style="width: 12vw" name="modello" disabled>
                        <option value="" <?php if($modello == '') echo 'selected="selected"'; ?>>Seleziona</option>
                    </select><br>
                    <?php
                        // Funzione per generare le opzioni del campo select per un intervallo di prezzi con l'opzione "Da" o "A"
                        function generatePriceOptions($min, $max, $selectedValue, $isFromField) {
                            $selectOptions = '';
                            $defaultOption = $isFromField ? 'Da' : 'A';
                            $selectOptions .= "<option value=\"\">$defaultOption</option>";
                            for ($i = $min; $i <= $max; $i += 500) {
                                $isSelected = ($selectedValue !== null && $selectedValue == $i) ? 'selected="selected"' : '';
                                $selectOptions .= "<option value=\"$i\" $isSelected>$i €</option>";
                            }
                            return $selectOptions;
                        }

                        // Definisci gli estremi dell'intervallo di prezzo
                        $prezzoMin = 500;
                        $prezzoMax = 100000;

                        // Recupera i valori inviati dal form per il prezzo
                        $prezzoDa = isset($_POST['prezzo_da']) ? $_POST['prezzo_da'] : '';
                        $prezzoA = isset($_POST['prezzo_a']) ? $_POST['prezzo_a'] : '';

                        // Genera le opzioni per i campi select di prezzo
                        $prezzoDaOptions = generatePriceOptions($prezzoMin, $prezzoMax, $prezzoDa, true);
                        $prezzoAOptions = generatePriceOptions($prezzoMin, $prezzoMax, $prezzoA, false);
                    ?>
                    <label for="prezzo">Prezzo:</label>
                    <select id="prezzo_da" name="prezzo_da" onchange="updateMassimo('prezzo_da', 'prezzo_a')">
                        <?php echo $prezzoDaOptions; ?>
                    </select>
                    <select id="prezzo_a" name="prezzo_a" style="margin-left: 0px;" onchange="updateMinimo('prezzo_da', 'prezzo_a')">
                        <?php echo $prezzoAOptions; ?>
                    </select><br>
                    <label for="carrozzeria">Carrozzeria:</label>
                    <select id="carrozzeria" name="carrozzeria" style="width: 12vw">
                        <option value="" <?php if($carrozzeria == '') echo 'selected="selected"'; ?>>Seleziona</option>
                        <option value="City Car" <?php if($carrozzeria == 'City Car') echo 'selected="selected"'; ?>>City Car</option>
                        <option value="Cabrio" <?php if($carrozzeria == 'Cabrio') echo 'selected="selected"'; ?>>Cabrio</option>
                        <option value="Suv/Fuoristrada/Pick-up" <?php if($carrozzeria == 'Suv/Fuoristrada/Pick-up') echo 'selected="selected"'; ?>>Suv/Fuoristrada/Pick-up</option>
                        <option value="Station Wagon" <?php if($carrozzeria == 'Station Wagon') echo 'selected="selected"'; ?>>Station Wagon</option>
                        <option value="Berlina" <?php if($carrozzeria == 'Berlina') echo 'selected="selected"'; ?>>Berlina</option>
                        <option value="Monovolume" <?php if($carrozzeria == 'Monovolume') echo 'selected="selected"'; ?>>Monovolume</option>
                    </select><br>
                    <?php
                        // Funzione per generare le opzioni del campo select per un intervallo di anni con l'opzione "Da" o "A"
                        function generateYearOptions($min, $max, $selectedValue, $isFromField) {
                            $selectOptions = '';
                            $defaultOption = $isFromField ? 'Da' : 'A';
                            $selectOptions .= "<option value=\"\">$defaultOption</option>";
                            for ($i = $max; $i >= $min; $i--) {
                                $isSelected = ($selectedValue !== null && $selectedValue == $i) ? 'selected="selected"' : '';
                                $selectOptions .= "<option value=\"$i\" $isSelected>$i</option>";
                            }
                            return $selectOptions;
                        }

                        // Definisci gli estremi dell'intervallo di anni
                        $annoMin = 1950;
                        $annoMax = date("Y");

                        // Recupera i valori inviati dal form per l'anno
                        $annoDa = isset($_POST['anno_da']) ? $_POST['anno_da'] : '';
                        $annoA = isset($_POST['anno_a']) ? $_POST['anno_a'] : '';

                        // Genera le opzioni per i campi select di anno
                        $annoDaOptions = generateYearOptions($annoMin, $annoMax, $annoDa, true);
                        $annoAOptions = generateYearOptions($annoMin, $annoMax, $annoA, false);
                    ?>

                    <label for="anno">Anno:</label>
                    <select id="anno_da" name="anno_da" onchange="updateMassimo('anno_da', 'anno_a')">
                        <?php echo $annoDaOptions; ?>
                    </select>
                    <select id="anno_a" name="anno_a" style="margin-left: 0px;" onchange="updateMinimo('anno_da', 'anno_a')">
                        <?php echo $annoAOptions; ?>
                    </select><br>
                    <?php
                        // Funzione per generare le opzioni del campo select per un intervallo di chilometraggio con l'opzione "Da" o "A"
                        function generateKilometerOptions($min, $max, $selectedValue, $isFromField) {
                            $selectOptions = '';
                            $defaultOption = $isFromField ? 'Da' : 'A';
                            $selectOptions .= "<option value=\"\">$defaultOption</option>";
                            for ($i = $min; $i <= $max; $i += 25000) {
                                $isSelected = ($selectedValue !== null && $selectedValue == $i) ? 'selected="selected"' : '';
                                $selectOptions .= "<option value=\"$i\" $isSelected>$i</option>";
                            }
                            return $selectOptions;
                        }

                        // Definisci gli estremi dell'intervallo di chilometraggio
                        $kmMin = 0;
                        $kmMax = 200000;

                        // Recupera i valori inviati dal form per il chilometraggio
                        $kmDa = isset($_POST['km_da']) ? $_POST['km_da'] : '';
                        $kmA = isset($_POST['km_a']) ? $_POST['km_a'] : '';

                        // Genera le opzioni per i campi select di chilometraggio
                        $kmDaOptions = generateKilometerOptions($kmMin, $kmMax, $kmDa, true);
                        $kmAOptions = generateKilometerOptions($kmMin, $kmMax, $kmA, false);
                    ?>

                    <label for="chilometraggio">Chilometraggio:</label>
                    <select id="km_da" name="km_da" onchange="updateMassimo('km_da', 'km_a')">
                        <?php echo $kmDaOptions; ?>
                    </select>
                    <select id="km_a" name="km_a" style="margin-left: 0px;" onchange="updateMinimo('km_da', 'km_a')">
                        <?php echo $kmAOptions; ?>
                    </select><br>
                    <label for="carburante">Carburante:</label>
                    <select name="carburante" style="width: 12vw">
                        <option value="" <?php if($carburante == '') echo 'selected="selected"'; ?>>Seleziona</option>
                        <option value="Benzina" <?php if($carburante == 'Benzina') echo 'selected="selected"'; ?>>Benzina</option>
                        <option value="Diesel" <?php if($carburante == 'Diesel') echo 'selected="selected"'; ?>>Diesel</option>
                        <option value="Ibrida" <?php if($carburante == 'Ibrida') echo 'selected="selected"'; ?>>Ibrida</option>
                        <option value="GPL" <?php if($carburante == 'GPL') echo 'selected="selected"'; ?>>GPL</option>
                        <option value="Metano" <?php if($carburante == 'Metano') echo 'selected="selected"'; ?>>Metano</option>
                    </select><br>
                    <label for="cambio">Cambio:</label>
                    <select name="cambio" style="width: 12vw">
                        <option value="" <?php if($cambio == '') echo 'selected="selected"'; ?>>Seleziona</option>
                        <option value="Manuale" <?php if($cambio == 'Manuale') echo 'selected="selected"'; ?>>Manuale</option>
                        <option value="Automatico" <?php if($cambio == 'Automatico') echo 'selected="selected"'; ?>>Automatico</option>
                        <option value="Semiautomatico" <?php if($cambio == 'Semiautomatico') echo 'selected="selected"'; ?>>Semiautomatico</option>
                    </select><br>
                    <?php
                        // Funzione per generare le opzioni del campo select per un intervallo di numeri con l'opzione "Da" o "A"
                        function generateNumberOptions($min, $max, $selectedValue, $isFromField) {
                            $selectOptions = '';
                            $defaultOption = $isFromField ? 'Da' : 'A';
                            $selectOptions .= "<option value=\"\">$defaultOption</option>";
                            for ($i = $min; $i <= $max; $i += 25) { // L'intervallo per l'anno e la potenza è di 1
                                $isSelected = ($selectedValue !== null && $selectedValue == $i) ? 'selected="selected"' : '';
                                $selectOptions .= "<option value=\"$i\" $isSelected>$i</option>";
                            }
                            return $selectOptions;
                        }

                        // Definisci gli estremi dell'intervallo per i campi di anno, chilometraggio e potenza
                        $potenzaMin = 0;
                        $potenzaMax = 1000;

                        // Recupera i valori inviati dal form per anno, chilometraggio e potenza
                        $potenzaDa = isset($_POST['potenza_da']) ? $_POST['potenza_da'] : '';
                        $potenzaA = isset($_POST['potenza_a']) ? $_POST['potenza_a'] : '';

                        // Genera le opzioni per i campi select di anno, chilometraggio e potenza
                        $potenzaDaOptions = generateNumberOptions($potenzaMin, $potenzaMax, $potenzaDa, true);
                        $potenzaAOptions = generateNumberOptions($potenzaMin, $potenzaMax, $potenzaA, false);
                    ?>
                    <label for="potenza">Potenza (CV):</label>
                    <select id="potenza_da" name="potenza_da" onchange="updateMassimo('potenza_da', 'potenza_a')">
                        <?php echo $potenzaDaOptions; ?>
                    </select>
                    <select id="potenza_a" name="potenza_a" style="margin-left: 0px;" onchange="updateMinimo('potenza_da', 'potenza_a')">
                        <?php echo $potenzaAOptions; ?>
                    </select><br>
                    <button type="submit" class="btn btn-primary">Cerca</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>

        <div id="searchResult"></div>

        <div class="item2" >
            <div class="annunci-block">
                <?php
                    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                        or die('Could not connect: ' . pg_last_error());

                    if ($dbconn) {
                        // Query per recuperare gli annunci dalla tabella annuncio in base ai filtri
                        $query = "SELECT * FROM annuncio WHERE nascosto is false"; // Inizia la query con un'istruzione true 
                        
                        // Aggiungi filtri sulla marca e sul modello solo se sono stati specificati
                        if (!empty($marca)) {
                            $query .= " AND marca = '$marca'";
                        }
                        if (!empty($modello)) {
                            $query .= " AND modello = '$modello'";
                        }
                        if (!empty($prezzoDa)) {
                            $query .= " AND prezzo >= $prezzoDa";
                        }
                        if (!empty($prezzoA)) {
                            $query .= " AND prezzo <= $prezzoA";
                        }
                        if (!empty($carrozzeria)) {
                            $query .= " AND carrozzeria = '$carrozzeria'";
                        }
                        if (!empty($annoDa)) {
                            $query .= " AND anno >= $annoDa";
                        }
                        if (!empty($annoA)) {
                            $query .= " AND anno <= $annoA";
                        }
                        if (!empty($kmDa)) {
                            $query .= " AND chilometraggio >= $kmDa";
                        }
                        if (!empty($kmA)) {
                            $query .= " AND chilometraggio <= $kmA";
                        }
                        if (!empty($carburante)) {
                            $query .= " AND carburante = '$carburante'";
                        }
                        if (!empty($cambio)) {
                            $query .= " AND cambio = '$cambio'";
                        }
                        if (!empty($potenzaDa)) {
                            $query .= " AND potenza >= $potenzaDa";
                        }
                        if (!empty($potenzaA)) {
                            $query .= " AND potenza <= $potenzaA";
                        }
                        $query .= " AND nascosto IS not true";
                        // Esecuzione della query
                        $result = pg_query($dbconn, $query);
                        $query_preferiti = "SELECT id FROM annuncio WHERE id IN (SELECT UNNEST(preferiti) FROM utente WHERE email = '$email')";
                        $result_preferiti = pg_query($dbconn, $query_preferiti);
                        if ($result_preferiti) {
                            // Inizializza un array per memorizzare gli ID degli annunci preferiti
                            $preferiti_array = array();

                            // Itera sui risultati della query e aggiungi gli ID all'array dei preferiti
                            while ($row_preferiti = pg_fetch_assoc($result_preferiti)) {
                                $preferiti_array[] = $row_preferiti['id'];
                            }

                            // Libera la memoria del risultato della query
                            pg_free_result($result_preferiti);
                        } else {
                            // Gestisci eventuali errori nella query per recuperare gli annunci preferiti
                            echo "Errore durante l'esecuzione della query per recuperare gli annunci preferiti: " . pg_last_error($dbconn);
                        }

                        if ($result) {
                            
                                // Iterazione sui risultati della query per visualizzare gli annunci
                                while ($row = pg_fetch_assoc($result)) {
                                    // Inizio di un nuovo annuncio
                                    echo "<div class='container3'>";
                                    // Visualizzazione dell'immagine dell'annuncio
                                    echo "<div class='foto'>";
                                    echo "<img src='../vendi/{$row['foto']}' alt='Foto auto' style='border-top-left-radius: 10px; border-top-right-radius: 10px; margin-left:1vw; margin-bottom:1vh; width:20vw'>";
                                    echo "</div>";

                                    // Inizio delle caratteristiche dell'annuncio
                                    echo "<div class='caratteristiche'>";
                                    echo "<h2><u>{$row['marca']} {$row['modello']}</u></h2><br>";
                                    echo "<p>Chilometraggio:  {$row['chilometraggio']}</p>";
                                    echo "<p>Prezzo:  {$row['prezzo']}</p>";
                                    echo "<p>Anno: {$row['anno']}</p>";
                                    echo "<p>Carburante: {$row['carburante']}</p>";
                                    echo "<p>Cambio: {$row['cambio']}</p>";
                                    echo "<p>Potenza: {$row['potenza']} CV</p>";
                                    // Aggiungi altre caratteristiche dell'annuncio qui...
                                    echo "<a href='big-annuncio.php?id={$row['id']}' class='btn btn-primary btn-lg details-button' role='button' style='width: 8vw; height: 2.4vh; font-size: 0.8em'>VEDI DETTAGLI</a>";
                                    echo "</div>";                   
                                    echo "<div class='preferito' style='margin-right: 1vw; margin-top: 1vh'>";
                                    // Aggiunta della stella per contrassegnare come preferito
                                    if (isset($preferiti_array) && is_array($preferiti_array)) {
                                        // Controllo se l'annuncio è nei preferiti
                                        $isFavorite = in_array($row['id'], $preferiti_array);
                                    } else {
                                        // Inizializzo $isFavorite a false in caso di problemi con l'array dei preferiti
                                        $isFavorite = false;
                                    }

                                    echo "<span class='heart-icon " . ($isFavorite ? 'filled' : '') . "' data-annuncio-id='{$row['id']}'></span><br>";
                                    echo "</div>";
                                    // Fine dell'annuncio
                                    echo "</div>";
                                
                                }
                            


                            // Rilascio della risorsa del risultato
                            pg_free_result($result);
                        } else {
                            echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
                        }
                    }else {
                        echo "Connessione al database non riuscita.";
                    }
                    // Chiusura della connessione al database
                    pg_close($dbconn);
                ?>  
                </div>
            </div>
        </div>
        </div>
    </div>


    <div class="car-logos-container">
            <div class="car-logos animation">
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
