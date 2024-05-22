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
if ($loggato) {
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
    if ($email) {
        $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
            or die('Could not connect: ' . pg_last_error());
        if ($dbconn) {
            $query_preferiti = "SELECT preferiti FROM utente WHERE email = $1";
            $result_preferiti = pg_query_params($dbconn, $query_preferiti, array($email));
            if ($result_preferiti) {
                $row_preferiti = pg_fetch_assoc($result_preferiti);
                $preferiti = $row_preferiti['preferiti'];
                if ($preferiti) {
                    $preferiti_array = json_decode($preferiti, true);
                    var_dump($preferiti_array);
                } else {
                    $preferiti_array = array();
                }
            } else {
                echo "Errore durante l'esecuzione della query per recuperare gli annunci preferiti: " . pg_last_error($dbconn);
            }
            pg_close($dbconn);
        } else {
            echo "Connessione al database non riuscita.";
        }
    } else {
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
        function toggleInfo() {
            var infoElements = document.querySelectorAll('.info');
            infoElements.forEach(function(element) {
                element.classList.toggle('hidden');
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            const navbarToggle = document.getElementById('navbar-toggle');
            const navbarMenu = document.getElementById('navbar-menu');
            navbarToggle.addEventListener('click', () => {
                navbarMenu.classList.toggle('active');
            });
        });
        function calcola() {
            var prestito = parseFloat(document.getElementById('importo_prestito').value);
            var interessi_annui = parseFloat(document.getElementById('tasso_interesse').value);
            var anni = parseFloat(document.getElementById('durata_prestito').value);
            var interessi_totali = (prestito * interessi_annui * anni) / 100;
            var tasso_interesse_mensile = interessi_annui / 12 / 100;
            var num_rate = anni * 12;
            var rata_mese = (prestito * tasso_interesse_mensile) / (1 - Math.pow(1 + tasso_interesse_mensile, -num_rate));
            var tot_prestito = prestito + interessi_totali;
            document.getElementById('risultato_tot_prestito').innerHTML = tot_prestito.toFixed(2) + "€";
            document.getElementById('risultato_tot_interessi').innerHTML = interessi_totali.toFixed(2) + "€";
            document.getElementById('risultato_num_rate').innerHTML = num_rate;
            document.getElementById('risultato_rata_mese').innerHTML = rata_mese.toFixed(2) + "€";
        }
        $(document).ready(function() {
            $('.heart-icon').click(function() {
                console.log($(this));
                var annuncioId = $(this).data('annuncio-id');
                var isFavorite = $(this).hasClass('filled');
                var isLogged = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;
                if (!isLogged) {
                    window.location.href = 'login/index.html';
                    return;
                }
                $(this).toggleClass('filled');
                var newText = isFavorite ? 'Aggiungi ai preferiti' : 'Rimuovi dai preferiti';
                $('#preferitiText').text(newText);
                $.ajax({
                    url: '../aggiorna_preferito.php',
                    type: 'POST',
                    data: { id: annuncioId, checked: !isFavorite },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
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
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.buy-button').addEventListener("click", function(event) {
                event.preventDefault();
                var isLogged = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;
                if (!isLogged) {
                    window.location.href = '../login/index.html';
                } else {
                    $('#emailVenditore').fadeIn(1000);
                }
            });
        });
    </script>
    <style> 
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
        .page2 {
            width: 80%;
            margin: 100px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            border: 3px solid orange;
        }
        .container3 {
            border-radius: 10px;
            background-color: white;
            font-family: 'Formula1 Display';
            color: black;
            margin: 0 auto;
        }
        .foto {
            white-space: nowrap;
        }
        .foto img {
            width: 100%;
            border-radius: 10px;
            border: 1px solid orange;
        }
        .caratteristiche {
            font-size: 25px;
            padding: 10px;
        }
        .descr {
            border: 1px solid orange;
            padding: 20px;
            text-align: left;
            max-width: 100%;
            height: auto;
            overflow: auto;
            font-size: 15px;
            border-radius: 10px;
        }
        .buy-button {
            background-color: orange;
            color: black;
            padding: 15px 30px;
            text-align: center;
            font-size: 20px;
            cursor: pointer;
            border-radius: 5px;
            overflow: hidden;
        }
        .prezzo {
            font-size: 30px;
        }
        .caratteristiche {
            font-size: 20px;
            width: auto;
        }
        table.finanziamento {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            background-color: orange;
            margin-top: 20px;
            border-radius: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 20px;
        }
        td.img-cell {
            width: 800px;
        }
        td.price-cell {
            vertical-align: top;
        }
        tr:not(:last-child) td {
            margin-bottom: 10px;
        }
        input {
            border-radius: 5px;
        }
        select {
            border-radius: 5px;
        }
        button {
            border-radius: 5px;
        }
        .hidden {
            display: none;
        }
        .info-button {
            font-size: 20px;
            vertical-align: middle;
            margin-right: 5px;
            margin-left: 5px;
        }
        td.info-column {
            width: 50%;
            padding-right: 100px;
            text-align: center;
        }
        #emailVenditore {
            display: none;
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
        .input-column, .info-column {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .info-column {
            font-size: 1em;
        }
        .flex-row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }
        .flex-item {
            flex: 1;
            min-width: 200px;
            box-sizing: border-box;
        }
        @media only screen and (max-width: 768px) {
            .car-logos-container {
                height: 2em;
            }
            .container-contattaci{
                bottom: 3vh;
            }
            .img-cell,
            .price-cell {
                display: block;
            }
            .foto img {
                width: 68vw;
                border-radius: 10px;
                border: 1px solid orange;
            }
            .price-cell {
                margin-top: 20px;
                flex-direction: column;
            }
            table.finanziamento {
                margin-top: 20px;
                width: 50vw;
            }
            .input-column, .info-column {
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 10px;
                font-size: 0.7em;
            }
            .input-column {
                order: 1;
            }

            .info-column {
                order: 2;
                margin-right: 0;
            }
            .input-column input,
            .input-column select,
            .input-column button {
                font-size: 1em;
            }
            .carburante,
            .preferiti {
                display: column;
                width: 100%;
                margin-bottom: 10px;
            }
            .flex-item {
                min-width: 100%;
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
    <div class="page2">
        <?php
        if(isset($_GET['id'])) {
            $annuncio_id = $_GET['id'];
            $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                        or die('Could not connect: ' . pg_last_error());
            $query = "SELECT * FROM annuncio WHERE id = $1";
            $result = pg_query_params($dbconn, $query, array($annuncio_id));
            $query_preferiti = "SELECT id FROM annuncio WHERE id IN (SELECT UNNEST(preferiti) FROM utente WHERE email = '$email')";
            $result_preferiti = pg_query($dbconn, $query_preferiti);
            if ($result_preferiti) {
                $preferiti_array = array();
                while ($row_preferiti = pg_fetch_assoc($result_preferiti)) {
                    $preferiti_array[] = $row_preferiti['id'];
                }
                pg_free_result($result_preferiti);
            } else {
                echo "Errore durante l'esecuzione della query per recuperare gli annunci preferiti: " . pg_last_error($dbconn);
            }
            if ($result && pg_num_rows($result) > 0) {
                $annuncio = pg_fetch_assoc($result);
                echo "<div class='container3'>";
                echo "<table>";
                echo "<tr>";
                echo "<td colspan='2'><h1><u>{$annuncio['marca']} {$annuncio['modello']}</u></h1></td>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td class='img-cell'>";
                echo "<div class='foto'><img src='../vendi/{$annuncio['foto']}' alt='Foto auto'></div>";
                echo "</td>";
                echo "<td rowspan='2' class='price-cell'>";
                $trattabilita = $annuncio['trattabile'] ? "<small style='font-size: 15px;'>-trattabile</small>" : "<small style='font-size: 15px;'>-non trattabile</small>";
                echo "<p class='prezzo'>€  {$annuncio['prezzo']} {$trattabilita}</p>";

                echo "<table class='finanziamento'>";
                echo "<tr>";
                echo "<td class='input-column'>";
                echo "<h3 style='width: 20vw; margin-bottom: 2vh'>Calcola la rata del tuo prestito</h3>";
                echo "<label for='importo_prestito'> Importo prestito (€): </label><br>";
                echo "<input type='number' name='importo_prestito' id='importo_prestito' required><br><br>";
                echo "<label for='tasso_interesse'>Tasso di interesse (%): </label><br>";
                echo "<input type='number' name='tasso_interesse' id='tasso_interesse' required><br><br>";
                echo "<label for='durata_prestito'> Durata (anni): </label><br>";
                echo "<select name='durata_prestito' id='durata_prestito' required>";
                for ($i = 1; $i <= 10; $i++) {
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                }
                echo "</select><br><br>";
                echo "<label for='rate'>Rate: </label><br>";
                echo "<select name='rate' id='rate' required>";
                echo "<option value='mensili'>Mensili</option>";
                echo "</select><br><br>";
                echo "<button onclick='calcola()'>Calcola Rata</button>";
                echo "<br>";
                echo "</td>";
                echo "<td class='info-column'>";
                echo "<p><b><u>Rata mensile: <span id='risultato_rata_mese'></span></u></b><button onclick='toggleInfo()'><span class=\"info-button\"> info </span></button></p>";
                echo "<p class='info hidden'>Importo totale prestito: <b><span id='risultato_tot_prestito'></span></b></p>";
                echo "<p class='info hidden'>Totale interessi: <b><span id='risultato_tot_interessi'></span></b></p>";
                echo "<p class='info hidden'>Numero di rate: <b><span id='risultato_num_rate'></span></b></p>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";

                echo "</td>";

                echo "<table>";
                
                echo "<tr class='flex-row'>";
                echo "<td class='flex-item'>";
                echo "<p class='caratteristiche'><span style='color: orange; font-size: 30px'>km</span>  Chilometraggio:  <b>{$annuncio['chilometraggio']}</b></p>";
                echo "</td>";
                echo "<td class='flex-item'>";
                echo "<p class='caratteristiche'><img src=\"../immagini/calendario.png\" width='30px'>&nbsp; Anno: <b>{$annuncio['anno']}</b></p>";
                echo "</td>";
                echo "<td class='flex-item'>";
                echo "<p class='caratteristiche'><img src=\"../immagini/carburante.png\" width='30px'>&nbsp; Carburante: <b>{$annuncio['carburante']}</b></p>";
                echo "</td>";
                echo "</tr>";

                echo "<tr class='flex-row'>";
                echo "<td class='flex-item'>";
                echo "<p class='caratteristiche'><img src=\"../immagini/cambio.png\" width='30px'>&nbsp; Cambio: <b>{$annuncio['cambio']}</b></p>";
                echo "</td>";
                echo "<td class='flex-item'>";
                echo "<p class='caratteristiche'><img src=\"../immagini/potenza.png\" width='30px'>&nbsp; Potenza: <b>{$annuncio['potenza']} CV</b></p>";
                echo "</td>";
                echo "<td class='flex-item'>";
                if (isset($preferiti_array) && is_array($preferiti_array)) {
                    $isFavorite = in_array($annuncio['id'], $preferiti_array);
                } else {
                    $isFavorite = false;
                }
                echo "<span class='heart-icon " . ($isFavorite ? 'filled' : '') . "' data-annuncio-id='{$annuncio['id']}'></span><p class='caratteristiche'><b id='preferitiText'>" . ($isFavorite ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti') . "</b></p>";
                echo "</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td colspan='2'>Descrizione:</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2' class='descr'>{$annuncio['descrizione']}</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td colspan='2'><a href='#' class='btn btn-primary btn-lg buy-button' role='button'>CONTATTA VENDITORE</a></td>";
                echo "</tr>";
                $loggato = isset($_SESSION['loggato']) ? $_SESSION['loggato'] : false;
                if ($loggato) {
                    echo "<tr>";
                    echo "<td colspan='2' id='emailVenditore' style='display: none;'>Email del venditore: <b>{$annuncio['email']}</b></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";

                pg_free_result($result);
            } else {
                echo "Annuncio non trovato.";
            }
            pg_close($dbconn);
        } else {
            echo "ID dell'annuncio non specificato.";
        }
        ?>
    </div>
    <div class="container-contattaci" id="footer">
        <div class="footer-column">
            <h2>Chi siamo</h2>
            <p>AutoWorld si dedica a fornire un'assistenza eccellente per la compravendita di auto, assicurando esperienze soddisfacenti, trasparenti e vantaggiose. Che cerchiate l'auto ideale o vogliate vendere la vostra al miglior prezzo, il team è a disposizione con professionalità e dedizione. Grazie per il vostro continuo supporto!</p><br><br>
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
