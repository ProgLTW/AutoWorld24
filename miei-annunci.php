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
                    var_dump($preferiti_array);
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

    </script>
    <style>
        .annunci{
            width: 80%;
            background-color: #2c2c2c96;
            border-radius: 10px;
            font-family: 'Formula1 Display';
            font-size: 20px;
            margin-top: 100px;
            text-align: left;
            color: white;
            z-index: 2;
            flex: 1;
            padding: 20px;
            border: 1px solid orange;
            margin-left: 50px;
        }

        .container3 {
            display: flex;
            border-radius: 10px;
            background-color: white;
            font-family: 'Formula1 Display';
            font-size: 32px;
            color: orange;
            margin: 0 auto;
            background-color: #2c2c2c96;
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
            padding: 50px;
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
            <li><a href="vendi/index.php"><b>VENDI</b></a></li>
            <li><a href="ricambi.php"><b>CHI SIAMO</b></a></li>
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


<header>
    <!-- Intestazione della pagina -->
    <h1>I Miei Annunci</h1>
</header>

<main>
    <!-- Elenco degli annunci -->
    <div class="annunci">
    
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
                                
                                // Checkbox per il preferito
                                // Controllo se l'array dei preferiti è stato correttamente inizializzato
                                if (isset($preferiti_array) && is_array($preferiti_array)) {
                                    // Controllo se l'annuncio è nei preferiti
                                    $isFavorite = in_array($row['id'], $preferiti_array);
                                } else {
                                    // Inizializzo $isFavorite a false in caso di problemi con l'array dei preferiti
                                    $isFavorite = false;
                                }

                                echo "<span class='heart-icon " . ($isFavorite ? 'filled' : '') . "' data-annuncio-id='{$row['id']}'></span>";

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
</main>
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
