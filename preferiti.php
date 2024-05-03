<!DOCTYPE html> 
<html>
<head>
    <title>AutoWorld</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="./assets/favicon-32x32.png"/>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style> 
        .icon-auto {
            width: 150px; /* Larghezza desiderata */
            height: auto; /* Altezza automaticamente ridimensionata in base alla larghezza */
        }
        .small-logo {
            margin-top: -70px; /* Modifica il valore del margine superiore in base alle tue esigenze */
        }
    </style>
</head>
<body class="text-center">
    <nav>
        <ul>
            <li><a href="index.php"><b>AUTOWORLD</b></a></li>
            <li class="dropdown">
            <a class="btn btn-primary btn-lg dropbtn" role="button"><b>RICERCA</b></a>
            <div class="dropdown-menu">
                <a href="ricerca/ricerca-personalizzata.php">Ricerca Personalizzata</a>
                <a href="ricerca/vedi-annunci.php">Vedi Annunci</a>
            </div>
        </li>
            <li><a href="vendi/index.html"><b>VENDI</b></a></li>
            <li><a href="ricambi.php"><b>RICAMBI</b></a></li>
            <li><a href="preferiti.php"><b>PREFERITI</b></a></li>
            <li><a href="login/index.html" class="btn btn-primary btn-lg" role="button">LOGIN</a></li>
            <li><a href="registrazione/index.html" class="btn btn-primary btn-lg" role="button">REGISTRATI</a></li>
        </ul>
    </nav>
    <div class="scroll-big-container">
        <button class="scroll-button scroll-left">
            <img src="immagini/leftarrow.png" alt="Scroll Left">
        </button>
        <div class="scroll-container">
            <div class="scroll-content">
                <?php 
                $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                    or die('Could not connect: ' . pg_last_error());

                if ($dbconn) {
                    // Query per recuperare tutti gli annunci dalla tabella annuncio
                    $query = "SELECT * FROM annuncio WHERE preferito is true";

                    // Esecuzione della query
                    $result = pg_query($dbconn, $query);

                    if ($result) {
                        // Iterazione sui risultati della query per visualizzare gli annunci
                        while ($row = pg_fetch_assoc($result)) {
                            // Visualizzazione di ciascun annuncio
                            echo "<div class='col'>";
                            echo "<div class='ad'>";
                            echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='150'>";
                            echo "<div class='text'>";
                            echo "<h2 style='color: white'>{$row['marca']} {$row['modello']}</h2>";
                            echo "<p style='color: white'>Prezzo: {$row['prezzo']}</p>";
                            echo "<p style='color: white'>Trattabile: " . ($row['trattabile'] ? 'Sì' : 'No') . "</p>";
                            echo "<p style='color: white'>Carrozzeria: {$row['carrozzeria']}</p>";
                            // Aggiungi altri dettagli dell'annuncio qui...
                            echo "</div>";
                            echo "</div>";
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
            <button class="scroll-button scroll-right">
                <img src="immagini/rightarrow.png" alt="Scroll Right">
            </button>
    </div>
    <div class="container-contattaci">
        <h2>Indirizzo Email</h2>
        <p>Puoi contattarci inviando un'email a:</p>
        <a href="mailto:info@autoworld.com">info@autoworld.com</a>
        <h2>Seguici sui Social</h2>
        <div class="social-icons">
            <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <!-- Aggiungi altre icone dei social secondo necessità -->
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
    <script>
        $(document).ready(function() {
            $(".scroll-left").click(function() {
                console.log("clicked left");
                $(".scroll-container").animate({scrollLeft: "-=100px"}, "slow");
            });

            $(".scroll-right").click(function() {
                console.log("clicked right");
                $(".scroll-container").animate({scrollLeft: "+=100px"}, "slow");
            });
        });
    </script>     
</body>
</html>
