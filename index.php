<!DOCTYPE html> 
<html>
<head>
    <title>AutoWorld</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="./assets/favicon-32x32.png"/>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
        // Gestione del clic sull'icona della stella
        $('.stella').click(function(){
            var annuncioId = $(this).prev('.preferito-checkbox').data('id'); // ID dell'annuncio
            var preferito = !$(this).prev('.preferito-checkbox').prop('checked'); // Inverti lo stato del preferito
            $.ajax({
                url: 'aggiorna_preferito.php', // Pagina PHP per l'aggiornamento del database
                type: 'POST',
                data: { id: annuncioId, preferito: preferito },
                success: function(response) {
                    console.log(response); // Stampa eventuali messaggi di successo o errore nella console
                    // Aggiorna l'icona della stella
                    if (preferito) {
                        $(this).addClass('preferito-attivo');
                    } else {
                        $(this).removeClass('preferito-attivo');
                    }
                    // Aggiorna lo stato del checkbox
                    $(this).prev('.preferito-checkbox').prop('checked', preferito);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
    </script>
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
            margin-left: 20px;
            margin-right: 20px;
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
    <div class="ad-container">
        <div class="ad">
            <a href="https://auto-esperienza.com/2024/03/05/controllare-auto-usata-allacquisto/" target="_blank">
                <img src="immagini/pubblicita1.jpg" alt="Pubblicità 1" style="height: 200px; width: 245px;">
            </a>
        </div>
        <div class="ad">
            <a href="https://auto-esperienza.com/2023/08/09/come-vendere-la-propria-auto-usata-facilmente-velocemente-a-buon-prezzo-e-in-sicurezza-tutti-i-metodi-noicompriamoauto-concessionaria-online-tra-privati-passaggio-proprieta-pagamento-preparazione/" target="_blank">
                <img src="immagini/pubblicita2.jpg" alt="Pubblicità 2" style="height: 200px; width: 245px;">
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
                                echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='250' style='border-top-left-radius: 10px; border-top-right-radius: 10px;'>";
                                echo "</div>";

                                // Inizio delle caratteristiche dell'annuncio
                                echo "<div class='caratteristiche'>";
                                echo "<h2>{$row['marca']} {$row['modello']}</h2>";
                                echo "<p>km {$row['chilometraggio']}</p>";
                                echo "<p>€ {$row['prezzo']}</p>";
                                echo "<p><img src=\"immagini/calendario.png\" width='20px'>&nbsp;{$row['anno']}</p>";
                                echo "<p><img src=\"immagini/carburante.png\" width='20px'>&nbsp;{$row['carburante']}</p>";
                                echo "<p><img src=\"immagini/cambio.png\" width='20px'>&nbsp;{$row['cambio']}</p>";
                                echo "<p><img src=\"immagini/potenza.png\" width='20px'>&nbsp;{$row['potenza']} CV</p>";
                                // Aggiungi altre caratteristiche dell'annuncio qui...

                                // Aggiunta della stella per contrassegnare come preferito
                                $checked = $row['preferito'] ? 'checked' : ''; // Se il preferito è true, il checkbox sarà selezionato
                                $stellaVuota = $row['preferito'] ? '' : 'stella-vuota'; // Se il preferito è false, applica la classe stella-vuota
                                echo "<p>";
                                echo "<input type='checkbox' class='preferito-checkbox' id='preferito{$row['id']}' data-id='{$row['id']}' $checked>"; // Checkbox nascosto
                                echo "<label for='preferito{$row['id']}' class='stella $stellaVuota'>&#9734;</label>"; // Etichetta personalizzata per l'icona della stella
                                echo "</p>";

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
