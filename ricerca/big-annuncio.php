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
            <li><a href="../preferiti.php"><b>PREFERITI</b></a></li>
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
            margin-right: 20px;
        }

        .foto img {
            margin-top: 20px;
            width: 60%; /* Immagine al 100% della larghezza del contenitore */
            border-radius: 10px;
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
            font-size: 25px;
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

            echo "<h1><u>{$annuncio['marca']} {$annuncio['modello']}</u></h1><br>";

            // Visualizzazione dell'immagine dell'annuncio
            echo "<div class='foto'>";
            echo "<img src='../vendi/{$annuncio['foto']}' alt='Foto auto' width='500'>";
            echo "</div>";
            echo "<br>";

            // Inizio delle caratteristiche dell'annuncio
            echo "<div class='caratteristiche'>";
            echo "<p>Chilometraggio:  {$annuncio['chilometraggio']}</p>";
            echo "<p>Prezzo:  {$annuncio['prezzo']}</p>";
            echo "<p>Anno: {$annuncio['anno']}</p>";
            echo "<p>Carburante: {$annuncio['carburante']}</p>";
            echo "<p>Cambio: {$annuncio['cambio']}</p>";
            echo "<p>Potenza: {$annuncio['potenza']} CV</p>";
            // Aggiungi altre caratteristiche dell'annuncio qui...
            
            // Aggiunta della stella per contrassegnare come preferito
            $checked = $annuncio['preferito'] ? 'checked' : ''; // Se il preferito è true, il checkbox sarà selezionato
            $stellaVuota = $annuncio['preferito'] ? '' : 'stella-vuota'; // Se il preferito è false, applica la classe stella-vuota
            echo "<p>";
            echo "<input type='checkbox' class='preferito-checkbox' id='preferito{$annuncio['id']}' data-id='{$annuncio['id']}' $checked>"; // Checkbox nascosto
            echo "<label for='preferito{$annuncio['id']}' class='stella $stellaVuota'>&#9734;</label>"; // Etichetta personalizzata per l'icona della stella
            echo "</p>";
            echo "<br>";
            echo "<div class='descr'>";
            echo "<p>Descrizione: {$annuncio['descrizione']}";
            echo "</div>";
            echo "<br>";
            echo "<br>";
            // Aggiungi altri dettagli dell'annuncio qui...
            echo "<a href='#' class='btn btn-primary btn-lg buy-button' role='button'>COMPRA</a>";

            echo "</div>";                    
            
            // Fine dell'annuncio
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
