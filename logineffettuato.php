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
        /* Stili per il menu a tendina */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
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
            <li><a href="ricerca/index.html" class="btn btn-primary btn-lg" role="button"><b>RICERCA</b></a></li>
            <li><a href="vendi/index.html"><b>VENDI</b></a></li>
            <li><a href="ricambi.php"><b>RICAMBI</b></a></li>
            <li><a href="preferiti.php"><b>PREFERITI</b></a></li>
            <!-- Modifica per mostrare il nome utente e il menu a tendina -->
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
                    or die('Could not connect: ' . pg_last_error());
                if ($dbconn) {
                    $email = $_GET['email'];                
                    $query = "SELECT nome FROM utente WHERE email = '$email'";
                    // Esecuzione della query
                    $result = pg_query($dbconn, $query);
                    if ($result) {
                        $num_rows = pg_num_rows($result);
                        while($num_rows > 0) {
                            echo "<li class='dropdown'><a href='#' class='btn btn-primary btn-lg' role='button'><b>Ciao, " . $row["nome"] . "</b></a>";
                        }
                    } else {
                        echo "<li><a href='login/index.html' class='btn btn-primary btn-lg' role='button'>LOGIN</a></li>";
                    }
                } else {
                    echo "Connessione al database non riuscita.";
                }
                pg_close($dbconn);
            ?>
            <div class="dropdown-content">
                <a href="#">I miei annunci</a>
                <a href="#">Salvati</a>
                <a href="#">Modifica password</a>
                <a href="#">Esci</a>
            </div>
            </li>
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

    <div class="scroll-big-container">
        <button class="scroll-button scroll-left">
            <img src="immagini/leftarrow.png" alt="Scroll Left">
        </button>
        <div class="scroll-container">
            
            <div class="scroll-content">
                <div class="annuncio">
                    <img src="immagini/prova1.png" alt="Annuncio 1">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova2.png" alt="Annuncio 2">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova1.png" alt="Annuncio 1">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova2.png" alt="Annuncio 2">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova1.png" alt="Annuncio 1">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova2.png" alt="Annuncio 2">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova1.png" alt="Annuncio 1">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova2.png" alt="Annuncio 2">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova1.png" alt="Annuncio 1">
                </div>
                <div class="annuncio">
                    <img src="immagini/prova2.png" alt="Annuncio 2">
                </div>
            </div>
            
        </div>
        <button class="scroll-button scroll-right">
                <img src="immagini/rightarrow.png" alt="Scroll Right">
            </button>
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
