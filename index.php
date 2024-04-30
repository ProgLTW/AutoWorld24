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
        /* Aggiungi stili per gli annunci pubblicitari */
        .ad-container {
            margin-top: 120px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px; /* Aggiungi spazio tra gli annunci e il contenuto principale */
        }
        .ad {
            width: 200px; /* Modifica la larghezza degli annunci in base alle tue esigenze */
            height: 200px; /* Modifica l'altezza degli annunci in base alle tue esigenze */
            background-color: black; /* Colore di sfondo degli annunci */
        }
        .container {
            margin-top: -250px; /* Riduci il margine superiore per eliminare lo spazio vuoto */
        }
    </style>
</head>
<body class="text-center">
    <nav>
        <ul>
            <li><a href="index.php"><b>AUTOWORLD</b></a></li>
            <li><a href="ricerca/index.html" class="btn btn-primary btn-lg" role="button"><b>RICERCA</b></a></li>
            <li><a href="vendi/index.html"><b>VENDI</b></a></li>
            <li><a href="ricambi.php"><b>RICAMBI</b></a></li>
            <li><a href="preferiti.php"><b>PREFERITI</b></a></li>
            <li><a href="login/index.html" class="btn btn-primary btn-lg" role="button">LOGIN</a></li>
            <li><a href="registrazione/index.html" class="btn btn-primary btn-lg" role="button">REGISTRATI</a></li>
        </ul>
    </nav>
    <div class="ad-container">
        <div class="ad">
            <a href="https://auto-esperienza.com/2024/03/05/controllare-auto-usata-allacquisto/" target="_blank">
                <img src="immagini/pubblicita1.jpg" alt="Pubblicità 1" style="height: 200px; width: 200px;">
            </a>
        </div>
        <div class="ad">
            <a href="https://auto-esperienza.com/2023/08/09/come-vendere-la-propria-auto-usata-facilmente-velocemente-a-buon-prezzo-e-in-sicurezza-tutti-i-metodi-noicompriamoauto-concessionaria-online-tra-privati-passaggio-proprieta-pagamento-preparazione/" target="_blank">
                <img src="immagini/pubblicita2.jpg" alt="Pubblicità 2" style="height: 200px; width: 200px;">
            </a>
        </div>
    </div>
    <div class="container">
        <h1>COSA CERCHI?</h1>
        <div class="box">
            <h2>AUTO</h2>
            <button class="icon-auto-button" onclick="location.href='ricerca/index.html';">
                <img src="immagini/iconauto.png" alt="Auto Icon" class="icon-auto">
            </button>
            
        </div>
        <div class="box">
            <h2>RICAMBI</h2>
            <button class="icon-auto-button" onclick="location.href='link_to_auto_page.html';">
                <img src="immagini/iconaruota.png" alt="Auto Icon" class="icon-auto">
            </button>
            
        </div>
    </div>
    <div class="scroll-container">
        <button class="scroll-button scroll-left">&lt;</button>
        <div class="scroll-content">
            <div class="container3">
                <div class="row">
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
                                    // Visualizzazione di ciascun annuncio
                                    echo "<div class='col'>";
                                    echo "<div class='ad'>";
                                    echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='150'>";
                                    echo "<h2>{$row['marca']} {$row['modello']}</h2>";
                                    echo "<p>Prezzo: {$row['prezzo']}</p>";
                                    echo "<p>Trattabile: " . ($row['trattabile'] ? 'Sì' : 'No') . "</p>";
                                    echo "<p>Carrozzeria: {$row['carrozzeria']}</p>";
                                    // Aggiungi altri dettagli dell'annuncio qui...
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
        </div>
        <button class="scroll-button scroll-right">&gt;</button>
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
            </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".scroll-left").click(function() {
                $(".scroll-content").animate({scrollLeft: "-=100px"}, "slow");
            });

            $(".scroll-right").click(function() {
                $(".scroll-content").animate({scrollLeft: "+=100px"}, "slow");
            });
        });
    </script>     
</body>
</html>
