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
            <li><a href="ricambi.php"><b>RICAMBI</b></a></li>
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
    <a href="logout.php">Logout</a>
</header>

<main>
    <!-- Elenco degli annunci -->
    <div class="annunci">
    // Aggiungi questo blocco prima della sezione "I miei annunci"


    <?php
        $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
            or die('Could not connect: ' . pg_last_error());

        if ($dbconn) {
            // Query per recuperare tutti gli annunci dalla tabella annuncio
            $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        if ($email) {
            // Eseguire la query per recuperare gli annunci dell'utente corrente
            $query = "SELECT * FROM annuncio WHERE email = $1";
            $result = pg_query_params($dbconn, $query, array($email));
            if ($result) {
                // Visualizza gli annunci recuperati
                while ($row = pg_fetch_assoc($result)) {
                    echo "<div class='container3'>";
                    // Visualizzazione dell'immagine dell'annuncio
                    echo "<div class='foto'>";
                    echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='250' style='border-top-left-radius: 10px; border-top-right-radius: 10px;'>";
                    echo "</div>";

                    // Inizio delle caratteristiche dell'annuncio
                    echo "<div class='caratteristiche'>";
                    echo "<h2><u>{$row['marca']} {$row['modello']}</u></h2><br>";
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
                pg_free_result($result);

            } else {
                echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
            }
        } else {
            // Gestisci il caso in cui l'utente non è autenticato
            echo "L'utente non è autenticato.";
        }
        // Inizio di un nuovo annuncio
                    

    }// Rilascio della risorsa del risultato
   
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
