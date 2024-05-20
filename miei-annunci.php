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

   // Verifica se l'utente è loggato
   $loggato = isset($_SESSION['loggato']) ? $_SESSION['loggato'] : false;
   // URL a cui reindirizzare l'utente
   $redirectURL = $loggato ? '../preferiti.php' : '../login/index.html';
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
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
    $('.heart-icon').click(function() {
        var annuncioId = $(this).data('annuncio-id');
        var isFavorite = $(this).hasClass('filled');
        var isLogged = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;
        
        // Se l'utente non è loggato, reindirizzalo alla pagina di login
        if (!isLogged) {
            window.location.href = 'login/index.html';
            return;
        }

        // Cambia lo stato del cuore (pieno o vuoto)
        $(this).toggleClass('filled');

        // Invia una richiesta AJAX per aggiungere o rimuovere l'annuncio dai preferiti
        $.ajax({
            url: 'aggiorna_preferito.php',
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
    <script>
$(document).ready(function() {
    // Gestisci il clic sul link "Chi siamo" nella navbar
    $('a[href="#footer"]').click(function(event) {
        // Previene il comportamento predefinito del link
        event.preventDefault();
        
        // Calcola la posizione verticale del footer
        var targetOffset = $('#footer').offset().top;
        
        // Anima lo scorrimento della pagina fino al footer con una durata di 1000ms (1 secondo)
        $('html, body').animate({
            scrollTop: targetOffset
        }, 1000);
    });
});
</script>
<script>
    $(document).ready(function() {
        $('.contrassegna-venduto').click(function() {
            var annuncioId = $(this).data('annuncio-id');
            
            // Invia una richiesta AJAX per contrassegnare l'annuncio come venduto
            $.ajax({
                url: 'contrassegna_venduto.php',
                type: 'POST',
                data: { id: annuncioId, nascosto: "t" },
                success: function(response) {
                    // Se l'aggiornamento ha avuto successo, esegui le azioni desiderate
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
    $(document).ready(function() {
        $('.rendi-visibile').click(function() {
            var annuncioId = $(this).data('annuncio-id');
            
            // Invia una richiesta AJAX per contrassegnare l'annuncio come visibile
            $.ajax({
                url: 'contrassegna_venduto.php',
                type: 'POST',
                data: { id: annuncioId, nascosto: "f" }, // Imposta lo stato di nascosto a false
                success: function(response) {
                    // Aggiorna la pagina o esegui altre azioni necessarie
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
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
        .container-contattaci {
            display: flex;
            flex-wrap: wrap;
            font-family: 'Formula1 Display';
            padding-top: 50px; /* Aumenta lo spazio sopra il footer */
            padding-bottom: 50px; /* Aumenta lo spazio sotto il footer */
        }

        .footer-column {
            flex: 1;
            margin-right: 100px;
            margin-bottom: 20px;
            margin-left: 100px;
            
        }
        .footer-column a {
            color: black; /* Imposta il colore del testo dei link su nero */
            text-decoration: none; /* Rimuove il sottolineato dai link, se presente */
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
            margin-top: auto;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 5px;
            position: relative;
        }

        
.container3 {
    display: flex;
    flex-direction: column;
}

.foto {
    flex: 1;
}

.caratteristiche {
    flex: 2;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px;
}

.details-button {
    align-self: flex-end;
}


        @media only screen and (max-width: 768px) {
            .car-logos-container {
                height: 2em;
            }
            .container-contattaci{
                bottom: 3vh;
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
                        $query = "SELECT * FROM annuncio WHERE email = '$email'";
                        $result = pg_query($dbconn, $query);
                        // Esegui la query per recuperare gli ID degli annunci preferiti dell'utente loggato
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
                            // Dentro il loop degli annunci
                            while ($row = pg_fetch_assoc($result)) {
                                // Inizio di un nuovo annuncio
                                echo "<div class='container3'>";
                                // Visualizzazione dell'immagine dell'annuncio
                                echo "<div class='foto'>";
                                echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='250' style='border-top-left-radius: 10px; border-top-right-radius: 10px;'>";
                                echo "</div>";

                                // Inizio delle caratteristiche dell'annuncio
                                echo "<div class='caratteristiche'>";
                                echo "<h2><u><a href='../ricerca/big-annuncio.php?id={$row['id']}' style='color: orange;'>{$row['marca']} {$row['modello']}</a></u></h2><br>";
                                echo "<p>km {$row['chilometraggio']}</p>";
                                echo "<p>€ {$row['prezzo']}</p>";
                                echo "<p><img src=\"immagini/calendario.png\" width='20px'>&nbsp;{$row['anno']}</p>";
                                echo "<p><img src=\"immagini/carburante.png\" width='20px'>&nbsp;{$row['carburante']}</p>";
                                echo "<p><img src=\"immagini/cambio.png\" width='20px'>&nbsp;{$row['cambio']}</p>";
                                echo "<p><img src=\"immagini/potenza.png\" width='20px'>&nbsp;{$row['potenza']} CV</p>";
                                if (isset($row['nascosto'])) {
                                    if ($row['nascosto'] == 'f') {
                                        // Se l'annuncio non è contrassegnato come venduto, mostra il pulsante "CONTRASSEGNA COME VENDUTO"
                                        echo "<a class='btn btn-primary btn-lg details-button contrassegna-venduto' role='button' style='margin-bottom: 0px' data-annuncio-id='{$row['id']}'>CONTRASSEGNA COME VENDUTO</a>";
                                    } else {
                                        // Se l'annuncio è contrassegnato come venduto, mostra un messaggio diverso
                                        echo "<a class='btn btn-primary btn-lg details-button rendi-visibile' role='button' style='margin-bottom: 0px' data-annuncio-id='{$row['id']}'>RENDI VISIBILE</a>";
                                    }
                                }
                                // Fine dell'annuncio
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
            <p><a href="https://www.instagram.com/"><img src="immagini/instagram.png" alt="Instagram" style="width: 20px; height: 20px;">&nbsp;INSTAGRAM</a></p>
            <p><a href="https://twitter.com/"><img src="immagini/twitter.png" alt="Twitter" style="width: 20px; height: 20px;">&nbsp;TWITTER</a></p>
            <p><a href="https://www.facebook.com/"><img src="immagini/facebook.png" alt="Facebook" style="width: 20px; height: 20px;">&nbsp;FACEBOOK</a></p>
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
