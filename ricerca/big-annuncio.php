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
    function toggleInfo() {
    var infoElements = document.querySelectorAll('.info'); // Seleziona tutti gli elementi con la classe 'info'
    infoElements.forEach(function(element) {
        element.classList.toggle('hidden'); // Aggiunge o rimuove la classe 'hidden' per nascondere o mostrare le informazioni
    });
}


    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("searchForm");
        const searchResult = document.getElementById("searchResult");

        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Evita il comportamento predefinito di invio del modulo

            // Raccogli i valori dei filtri
            const formData = new FormData(form);
            const formDataObject = {};
            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });

            // Invia una richiesta POST al server con i dati dei filtri
            fetch("filtro-annunci.php", {
                method: "POST",
                body: JSON.stringify(formDataObject),
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.text())
            .then(data => {
                // Aggiorna la sezione degli annunci con i nuovi risultati
                searchResult.innerHTML = data;
            })
            .catch(error => {
                console.error("Errore durante la richiesta:", error);
            });
        });
    });


    //finanziamento
    function calcola() {
    // Prendi i valori di input
    var prestito = parseFloat(document.getElementById('importo_prestito').value);
    var interessi_annui = parseFloat(document.getElementById('tasso_interesse').value);
    var anni = parseFloat(document.getElementById('durata_prestito').value);
    
    // Calcola gli interessi totali
    var interessi_totali = (prestito * interessi_annui * anni) / 100;

    // Calcola il tasso di interesse mensile e il numero totale delle rate
    var tasso_interesse_mensile = interessi_annui / 12 / 100; // Tasso di interesse mensile
    var num_rate = anni * 12; // Numero totale delle rate

    // Calcola la rata mensile
    var rata_mese = (prestito * tasso_interesse_mensile) / (1 - Math.pow(1 + tasso_interesse_mensile, -num_rate));

    // Calcola l'importo totale del prestito
    var tot_prestito = prestito + interessi_totali;

    // Mostra il risultato
    document.getElementById('risultato_tot_prestito').innerHTML = tot_prestito.toFixed(2) + "€";
    document.getElementById('risultato_tot_interessi').innerHTML = interessi_totali.toFixed(2) + "€";
    document.getElementById('risultato_num_rate').innerHTML = num_rate;
    document.getElementById('risultato_rata_mese').innerHTML = rata_mese.toFixed(2) + "€";
}

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


    </style>
</head>
<body class="text-center">
<nav>
        <ul>
            <li><a href="../index.php"><b>AUTOWORLD</b></a></li>
            <li class="dropdown">
                <a class="btn btn-primary btn-lg dropbtn" role="button"><b>RICERCA</b></a>
                <div class="dropdown-menu">
                    <a href="../ricerca/ricerca-personalizzata.php">Ricerca Personalizzata</a>
                    <a href="../ricerca/vedi-annunci.php">Vedi Annunci</a>
                </div>
            </li>
            <li><a href="../vendi/index.php"><b>VENDI</b></a></li>
            <li><a href="../ricambi.php"><b>RICAMBI</b></a></li>
            <li><a href="<?php echo $redirectURL; ?>"><b>PREFERITI</b></a></li>
            <?php
                $loggato = isset($_SESSION['loggato']) ? $_SESSION['loggato'] : false;
                $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
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
                                echo "<li class='dropdown'><a href='#' class='btn btn-primary btn-lg' role='button'><b>Ciao, " . $row["nome"] . "</b></a>";
                                // Qui inizia la sezione del dropdown
                                echo "<div class='dropdown-menu'>";
                                echo "<a href='#'>I miei annunci</a>";
                                echo "<a href='../preferiti.php'>Preferiti</a>";
                                echo "<a href='../modifica-password.php'>Modifica password</a>";
                                echo "<a href='?logout=true' class='btn btn-primary btn-lg' role='button'>ESCI</a>";
                                echo "</div>"; // Chiudi dropdown-content
                                echo "</li>"; // Chiudi dropdown
                            } else {
                                echo "<li><a href='../login/index.html' class='btn btn-primary btn-lg' role='button'>LOGIN</a></li>";
                            }
                        } else {
                            echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
                        }
                    } else {
                        echo "Connessione al database non riuscita.";
                    }
                    pg_close($dbconn);
                } else {
                    echo "<li><a href='../login/index.html' class='btn btn-primary btn-lg' role='button'>LOGIN</a></li>";
                    echo "<li><a href='../registrazione/index.html' class='btn btn-primary btn-lg' role='button'>REGISTRATI</a></li>";
                }
            ?>
        </ul>
    </nav>

    <style>

        .page2 {
            width: 80%;
            margin: 100px auto; /* Questo imposta i margini superiori e inferiori a 100px e i margini laterali a 'auto', che centrerà l'elemento */
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
             /* Imposta la larghezza massima al 30% del contenitore */
            white-space: nowrap;
        }

        .foto img {
            
            width: 100%; /* Immagine al 100% della larghezza del contenitore */
            border-radius: 10px;
            border: 1px solid orange;
        }

        .caratteristiche {
            font-size: 25px;
            padding: 10px;
        }

        .descr {
            border: 1px solid orange;
            padding: 20px; /* Imposta il padding desiderato */
            text-align: left; /* Allinea il testo a sinistra */
            max-width: 100%; /* Imposta la larghezza massima della descrizione */
            height: auto; /* Altezza automatica in base al contenuto */
            overflow: auto; /* Aggiungi uno scroll se necessario */
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
            border-radius: 10px; /* Aggiungi il border-radius anche qui */
        }
                
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 20px; /* Aggiunge spazio intorno al contenuto all'interno delle celle */
            /*border: 1px solid orange;*/
        }
        td.img-cell {
            width: 800px; /* Imposta una larghezza fissa per la cella contenente l'immagine */
        }
        td.price-cell {
            vertical-align: top; /* Imposta l'allineamento verticale in alto */
        }

        tr:not(:last-child) td {
            margin-bottom: 10px; /* Aggiunge spazio solo alle righe eccetto l'ultima */
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
            font-size: 20px; /* Imposta la dimensione del carattere */
            vertical-align: middle; /* Allinea il pulsante verticalmente rispetto al testo */
            margin-right: 5px; /* Aggiunge uno spazio tra l'icona e il testo */
            margin-left: 5px;
        }
        td.info-column {
            width: 50%; /* Imposta la larghezza della colonna al 50% della larghezza della tabella */
            padding-right: 100px; /* Aggiunge un padding a destra per separare questa colonna dalla colonna precedente */
            text-align: center; /* Allinea il testo a destra all'interno della colonna */
        }
    </style>

<div class="page2">
    <?php
    // Verifica se è stato passato un ID di annuncio come parametro nell'URL
    if(isset($_GET['id'])) {
        $annuncio_id = $_GET['id'];

        // Connessione al database
        $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                    or die('Could not connect: ' . pg_last_error());

        // Query per recuperare l'annuncio dal database utilizzando l'ID fornito
        $query = "SELECT * FROM annuncio WHERE id = $1";
        $result = pg_query_params($dbconn, $query, array($annuncio_id));

        // Verifica se la query ha restituito un risultato valido
        if ($result && pg_num_rows($result) > 0) {
            $annuncio = pg_fetch_assoc($result);

            // Visualizza i dettagli dell'annuncio
            echo "<div class='container3'>";
            echo "<table>";

            // Prima riga: Marca e modello
            echo "<tr>";
            echo "<td colspan='2'><h1><u>{$annuncio['marca']} {$annuncio['modello']}</u></h1></td>";
            echo "</tr>";

            // Seconda riga: Immagine e prezzo
            echo "<tr>";
            echo "<td class='img-cell'>";
            echo "<div class='foto'><img src='../vendi/{$annuncio['foto']}' alt='Foto auto'></div>";
            echo "</td>";

            echo "<td rowspan='2' class='price-cell'>";
            $trattabilita = $annuncio['trattabile'] ? "<small style='font-size: 15px;'>-trattabile</small>" : "<small style='font-size: 15px;'>-non trattabile</small>";
            echo "<p class='prezzo'>€  {$annuncio['prezzo']} {$trattabilita}</p>";
            
            
            echo "<table class='finanziamento'>";
            //finanziamento
            echo "<tr>";
            //prima colonna -> input
            echo "<td>";
            
            echo "<h3 style='width: 400px; margin-bottom: 20px'>Calcola la rata del tuo prestito</h3>";

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
            echo "<option value='mensili'>Mensili</option>"; // Opzione obbligatoria per le rate mensili
            echo "</select><br><br>";

            echo "<button onclick='calcola()'>Calcola Rata</button>";
            echo "<br>";
            echo "</td>";

            //seconda colonna -> risultati
            echo "<td class='info-column'>";
            
            echo "<p style='font-size: 25px;'><b><u>Rata mensile: <span id='risultato_rata_mese'></span></u></b><button onclick='toggleInfo()'><span class=\"info-button\"> i</span></button></p>";
            echo "<p class='info hidden'>Importo totale prestito: <b><span id='risultato_tot_prestito'></span></b></p>";
            echo "<p class='info hidden'>Totale interessi: <b><span id='risultato_tot_interessi'></span></b></p>";
            echo "<p class='info hidden'>Numero di rate: <b><span id='risultato_num_rate'></span></b></p>";
            echo "</td>";
           
            echo "</tr>";

            echo "</table>";
            echo "</td>";
            echo "<table>";
            // Terza riga: Chilometraggio, anno, carburante
            echo "<tr>";
            echo "<td>";
            echo "<p class='caratteristiche'><span style='color: orange; font-size: 30px'>km</span>  Chilometraggio:  <b>{$annuncio['chilometraggio']}</b></p>";
            echo "</td>";
            echo "<td>";
            echo "<p class='caratteristiche'><img src=\"../immagini/calendario.png\" width='30px'>&nbsp; Anno: <b>{$annuncio['anno']}</b></p>";
            echo "</td>";
            echo "<td>";
            echo "<p class='caratteristiche'><img src=\"../immagini/carburante.png\" width='30px'>&nbsp; Carburante: <b>{$annuncio['carburante']}</b></p>";
            echo "</td>";
            echo "</tr>";

            // Quarta riga: Cambio, potenza, aggiungi ai preferiti
            echo "<tr>";
            echo "<td>";
            echo "<p class='caratteristiche'><img src=\"../immagini/cambio.png\" width='30px'>&nbsp; Cambio: <b>{$annuncio['cambio']}</b></p>";
            echo "</td>";
            echo "<td>";
            echo "<p class='caratteristiche'><img src=\"../immagini/potenza.png\" width='30px'>&nbsp; Potenza: <b>{$annuncio['potenza']} CV</b></p>";
            echo "</td>";
            echo "<td>";
            $checked = $annuncio['preferito'] ? 'checked' : '';
            $stellaVuota = $annuncio['preferito'] ? '' : 'stella-vuota';
            echo "<p class='caratteristiche'><input type='checkbox' class='preferito-checkbox' id='preferito{$annuncio['id']}' data-id='{$annuncio['id']}' $checked>";
            echo "<label for='preferito{$annuncio['id']}' class='stella $stellaVuota'>&#9734;</label>";
            echo " <u>Aggiungi ai preferiti</u></p>";
            echo "</td>";
            echo "</tr>";

            // Quinta riga: Descrizione
            echo "<tr>";
            echo "<td colspan='2'>Descrizione:</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2' class='descr'>{$annuncio['descrizione']}</td>";
            echo "</tr>";

            // Sesta riga: Bottone
            echo "<tr>";
            echo "<td colspan='2'><a href='#' class='btn btn-primary btn-lg buy-button' role='button'>COMPRA</a></td>";
            echo "</tr>";

            echo "</table>";
            echo "</div>";

            

            // Rilascio della risorsa del risultato
            pg_free_result($result);
        } else {
            echo "Annuncio non trovato.";
        }

        // Chiusura della connessione al database
        pg_close($dbconn);
    } else {
        echo "ID dell'annuncio non specificato.";
    }
    ?>
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
