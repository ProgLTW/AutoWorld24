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
        .item1{grid-area: filtri; }
        .item2{grid-area: annunci; }

        .col-container {
            display: flex;
            gap: 10px;
            background-color: #2c2c2c96;
            padding: 10px;
            margin-top: 50px;
            margin: 30px;
        }

        .item1{
            font-family: 'Formula1 Display';
            font-size: 20px;
            margin-top: 100px;
            text-align: left;
            color: black;
            width: 400px; /* Larghezza fissa per la colonna sinistra */
            padding: 20px;
            background-color: orange;
            border-radius: 10px;
        }
        
        .item2 {
            border-radius: 10px;
            font-family: 'Formula1 Display';
            font-size: 20px;
            margin-top: 100px;
            text-align: left;
            color: white;
            flex: 1;
            padding: 20px;
            border: 1px solid orange;
            
        }

        #searchForm {            
            border-radius: 10px;
            margin: 0 auto; /* Centra il form orizzontalmente */
        }


        .container3 {
            display: flex;
            border-radius: 10px;
            background-color: white;
            font-family: 'Formula1 Display';
            color: orange;
            margin: 0 auto;
            background-color: #2c2c2c96;
            border: 1px solid orange;
        }


        .foto {
            width: 400px; /* Imposta la larghezza massima al 30% del contenitore */
            margin-right: 20px;
        }

        .foto img {
            margin-top: 20px;
            width: 100%; /* Immagine al 100% della larghezza del contenitore */
            border-radius: 10px;
        }

        .caratteristiche {
            flex: 1; /* Le caratteristiche occupano il 50% dello spazio */
            padding: 10px;
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
    </style>
    <div class="col-container">
        <div class="item1">
            <?php
                // Recupera i valori inviati dal form
                $marca = isset($_POST['marca']) ? $_POST['marca'] : '';
                $modello = isset($_POST['modello']) ? $_POST['modello'] : '';
                $prezzoDa = isset($_POST['PrezzoDa']) ? $_POST['PrezzoDa'] : '';
                $prezzoA = isset($_POST['PrezzoA']) ? $_POST['PrezzoA'] : '';
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
            <form id="searchForm" class="form-signin m-auto">
                <label for="marca">Marca:</label>
                <select id="marca" name="marca" onchange="updateModelloOptions(this.value)">
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
                <select id="modello" name="modello" disabled>
                    <option value="" <?php if($modello == '') echo 'selected="selected"'; ?>>Seleziona</option>
                </select><br>
                <label for="prezzo">Prezzo:</label>
                <select id="prezzo_da" type="number" name="Da" onchange="updateMassimo('prezzo_da', 'prezzo_a')">
                    <option value="" <?php if($prezzoDa == '') echo 'selected="selected"'; ?>>Da</option>
                    <option type="number" value="500" <?php if($prezzoDa == '500') echo 'selected="selected"'; ?>>500€</option>
                    <option type="number" value="1000" <?php if($prezzoDa == '1000') echo 'selected="selected"'; ?>>1000€</option>
                    <option type="number" value="1500" <?php if($prezzoDa == '1500') echo 'selected="selected"'; ?>>1500€</option>
                    <option type="number" value="2000" <?php if($prezzoDa == '2000') echo 'selected="selected"'; ?>>2000€</option>
                    <option type="number" value="2500" <?php if($prezzoDa == '2500') echo 'selected="selected"'; ?>>2500€</option>
                    <option type="number" value="3000" <?php if($prezzoDa == '3000') echo 'selected="selected"'; ?>>3000€</option>
                    <option type="number" value="4000" <?php if($prezzoDa == '4000') echo 'selected="selected"'; ?>>4000€</option>
                    <option type="number" value="5000" <?php if($prezzoDa == '5000') echo 'selected="selected"'; ?>>5000€</option>
                    <option type="number" value="6000" <?php if($prezzoDa == '6000') echo 'selected="selected"'; ?>>6000€</option>
                    <option type="number" value="7000" <?php if($prezzoDa == '7000') echo 'selected="selected"'; ?>>7000€</option>
                    <option type="number" value="8000" <?php if($prezzoDa == '8000') echo 'selected="selected"'; ?>>8000€</option>
                    <option type="number" value="9000" <?php if($prezzoDa == '9000') echo 'selected="selected"'; ?>>9000€</option>
                </select>
                <select id="prezzo_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('prezzo_da', 'prezzo_a')">
                    <option value="" <?php if($prezzoA == '') echo 'selected="selected"'; ?>>A</option>
                    <option type="number" value="500" <?php if($prezzoA == '500') echo 'selected="selected"'; ?>>500€</option>
                    <option type="number" value="1000" <?php if($prezzoA == '1000') echo 'selected="selected"'; ?>>1000€</option>
                    <option type="number" value="1500" <?php if($prezzoA == '1500') echo 'selected="selected"'; ?>>1500€</option>
                    <option type="number" value="2000" <?php if($prezzoA == '2000') echo 'selected="selected"'; ?>>2000€</option>
                    <option type="number" value="2500" <?php if($prezzoA == '2500') echo 'selected="selected"'; ?>>2500€</option>
                    <option type="number" value="3000" <?php if($prezzoA == '3000') echo 'selected="selected"'; ?>>3000€</option>
                    <option type="number" value="4000" <?php if($prezzoA == '4000') echo 'selected="selected"'; ?>>4000€</option>
                    <option type="number" value="5000" <?php if($prezzoA == '5000') echo 'selected="selected"'; ?>>5000€</option>
                    <option type="number" value="6000" <?php if($prezzoA == '6000') echo 'selected="selected"'; ?>>6000€</option>
                    <option type="number" value="7000" <?php if($prezzoA == '7000') echo 'selected="selected"'; ?>>7000€</option>
                    <option type="number" value="8000" <?php if($prezzoA == '8000') echo 'selected="selected"'; ?>>8000€</option>
                    <option type="number" value="9000" <?php if($prezzoA == '9000') echo 'selected="selected"'; ?>>9000€</option>
                </select><br>
                <label for="carrozzeria">Carrozzeria:</label>
                <select id="carrozzeria" name="carrozzeria">
                    <option value="" <?php if($carrozzeria == '') echo 'selected="selected"'; ?>>Seleziona</option>
                    <option value="City Car" <?php if($carrozzeria == 'City Car') echo 'selected="selected"'; ?>>City Car</option>
                    <option value="Cabrio" <?php if($carrozzeria == 'Cabrio') echo 'selected="selected"'; ?>>Cabrio</option>
                    <option value="Suv/Fuoristrada/Pick-up" <?php if($carrozzeria == 'Suv/Fuoristrada/Pick-up') echo 'selected="selected"'; ?>>Suv/Fuoristrada/Pick-up</option>
                    <option value="Station Wagon" <?php if($carrozzeria == 'Station Wagon') echo 'selected="selected"'; ?>>Station Wagon</option>
                    <option value="Berlina" <?php if($carrozzeria == 'Berlina') echo 'selected="selected"'; ?>>Berlina</option>
                    <option value="Monovolume" <?php if($carrozzeria == 'Monovolume') echo 'selected="selected"'; ?>>Monovolume</option>
                </select><br>
                <label for="anno">Anno:</label>
                <select id="anno_da" type="number" name="Da" onchange="updateMassimo('anno_da', 'anno_a')">
                    <option value="" <?php if($annoDa == '') echo 'selected="selected"'; ?>>Da</option>
                        <?php
                        // Loop per generare le opzioni di anno
                        for ($i = 2024; $i >= 1900; $i--) {
                            // Imposta l'attributo selected per l'opzione corrispondente al valore inviato
                            echo '<option value="' . $i . '"';
                            if ($annoDa == $i) {
                                echo ' selected="selected"';
                            }
                            echo '>' . $i . '</option>';
                        }
                        ?>
                </select>
                <select id="anno_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('anno_da', 'anno_a')">
                    <option value="" <?php if($annoA == '') echo 'selected="selected"'; ?>>A</option>
                        <?php
                        // Loop per generare le opzioni di anno
                        for ($i = 2024; $i >= 1900; $i--) {
                            // Imposta l'attributo selected per l'opzione corrispondente al valore inviato
                            echo '<option value="' . $i . '"';
                            if ($annoA == $i) {
                                echo ' selected="selected"';
                            }
                            echo '>' . $i . '</option>';
                        }
                        ?>
                </select><br>
                <label for="chilometraggio">Chilometraggio:</label>
                <select id="km_da" type="number" name="Da" onchange="updateMassimo('km_da', 'km_a')">
                    <option value="" <?php if($kmDa == '') echo 'selected="selected"'; ?>>Da</option>
                    <?php
                    // Loop per generare le opzioni di chilometraggio
                    for ($i = 0; $i <= 200000; $i += 25000) {
                        // Imposta l'attributo selected per l'opzione corrispondente al valore inviato
                        echo '<option value="' . $i . '"';
                        if ($kmDa == $i) {
                            echo ' selected="selected"';
                        }
                        echo '>' . $i . '</option>';
                    }
                    ?>
                </select>
                <select id="km_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('km_da', 'km_a')">
                    <option value="" <?php if($kmA == '') echo 'selected="selected"'; ?>>A</option>
                    <?php
                    // Loop per generare le opzioni di chilometraggio
                    for ($i = 0; $i <= 200000; $i += 25000) {
                        // Imposta l'attributo selected per l'opzione corrispondente al valore inviato
                        echo '<option value="' . $i . '"';
                        if ($kmA == $i) {
                            echo ' selected="selected"';
                        }
                        echo '>' . $i . '</option>';
                    }
                    ?>
                </select><br>
                <label for="carburante">Carburante:</label>
                <select name="carburante">
                    <option value="" <?php if($carburante == '') echo 'selected="selected"'; ?>>Seleziona</option>
                    <option value="Benzina" <?php if($carburante == 'Benzina') echo 'selected="selected"'; ?>>Benzina</option>
                    <option value="Diesel" <?php if($carburante == 'Diesel') echo 'selected="selected"'; ?>>Diesel</option>
                    <option value="Ibrida" <?php if($carburante == 'Ibrida') echo 'selected="selected"'; ?>>Ibrida</option>
                    <option value="GPL" <?php if($carburante == 'GPL') echo 'selected="selected"'; ?>>GPL</option>
                    <option value="Metano" <?php if($carburante == 'Metano') echo 'selected="selected"'; ?>>Metano</option>
                </select><br>
                <label for="cambio">Cambio:</label>
                <select name="cambio">
                    <option value="" <?php if($cambio == '') echo 'selected="selected"'; ?>>Seleziona</option>
                    <option value="Manuale" <?php if($cambio == 'Manuale') echo 'selected="selected"'; ?>>Manuale</option>
                    <option value="Automatico" <?php if($cambio == 'Automatico') echo 'selected="selected"'; ?>>Automatico</option>
                    <option value="Semiautomatico" <?php if($cambio == 'Semiautomatico') echo 'selected="selected"'; ?>>Semiautomatico</option>
                </select><br>
                <label for="potenza">Potenza (CV):</label>
                <input type="number" id="potenza_da" name="Da" onchange="updateMassimo('potenza_da', 'potenza_a')" placeholder="Da" min="0" max="1000" value="<?php echo isset($_POST['PotenzaDa']) ? htmlspecialchars($_POST['PotenzaDa']) : ''; ?>">
                <input type="number" id="potenza_a" name="A" style="margin-left: 0%;" onchange="updateMinimo('potenza_da', 'potenza_a')" placeholder="A" min="0" max="1000" value="<?php echo isset($_POST['PotenzaA']) ? htmlspecialchars($_POST['PotenzaA']) : ''; ?>"><br>
                <button type="submit" class="btn btn-primary" style="margin-left: 150px; margin-top: 30px; margin-bottom: 30px;">Cerca</button>
            </form>
        </div>

        <div id="searchResult"></div>

        <div class="item2" >
            <div class="annunci-block">
                <?php
                    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                        or die('Could not connect: ' . pg_last_error());

                    if ($dbconn) {
                        // Query per recuperare tutti gli annunci dalla tabella annuncio
                        $query = "SELECT * FROM annuncio";

                        // Esecuzione della query
                        $result = pg_query($dbconn, $query);

                        if ($result) {
                            // Iterazione sui risultati della query per visualizzare gli annunci
                            while ($row = pg_fetch_assoc($result)) {
                                // Inizio di un nuovo annuncio
                                echo "<div class='container3'>";
                                // Visualizzazione dell'immagine dell'annuncio
                                echo "<div class='foto'>";
                                echo "<img src='../vendi/{$row['foto']}' alt='Foto auto' width='250' style='border-top-left-radius: 10px; border-top-right-radius: 10px;'>";
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

                                // Aggiunta della stella per contrassegnare come preferito
                                $checked = $row['preferito'] ? 'checked' : ''; // Se il preferito è true, il checkbox sarà selezionato
                                $stellaVuota = $row['preferito'] ? '' : 'stella-vuota'; // Se il preferito è false, applica la classe stella-vuota
                                echo "<p>";
                                echo "<input type='checkbox' class='preferito-checkbox' id='preferito{$row['id']}' data-id='{$row['id']}' $checked>"; // Checkbox nascosto
                                echo "<label for='preferito{$row['id']}' class='stella $stellaVuota'>&#9734;</label>"; // Etichetta personalizzata per l'icona della stella
                                echo "</p>";

                                echo "<a href='../ricerca/big-annuncio.php?id={$row['id']}' class='btn btn-primary btn-lg details-button' role='button'>VEDI DETTAGLI</a>";

                                echo "<a href='#' class='btn btn-primary btn-lg buy-button' role='button'>COMPRA</a>";
                                
                              
                                echo "</div>";                    
                                
                                // Fine dell'annuncio
                                echo "</div>";
                            }

                            // Rilascio della risorsa del risultato
                            pg_free_result($result);
                        } else {
                            echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
                        }
                    } else {
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
