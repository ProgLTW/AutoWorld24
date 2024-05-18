<!DOCTYPE html>
<html lang="it">
<head>
    <title>AutoWorld - Pagina di login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./sign-in.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <script src="./rememberMe.js" type="application/javascript"></script>
    <style> 
        html, body {
            height: 100%; /* Assicura che il body e l'html occupino l'intera altezza del viewport */
            margin: 0; /* Rimuove margini predefiniti */
            padding: 0; /* Rimuove padding predefinito */
        }

        body {
            background-image: url(../immagini/sfondologin.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Formula1 Display';
            color: orange;
            display: flex; /* Usa flexbox per garantire che il contenuto si adatti */
            flex-direction: column; /* Dispone gli elementi in colonna */
            align-items: center; /* Centra il contenuto orizzontalmente */
        }

        .logo-container {
            width: 100%; /* Occupa tutta la larghezza disponibile */
            text-align: center; /* Centra il contenuto orizzontalmente */
            margin-top: 20px; /* Aggiunge un margine superiore per separare il logo dal bordo */
            margin-bottom: 20px; /* Aggiunge un margine inferiore per separare il logo dal contenuto */
        }

        .box-container {
            flex: 1; /* Permette al contenitore di espandersi per riempire lo spazio disponibile */
            display: flex;
            justify-content: center; /* Centra il contenuto verticalmente */
            align-items: center; /* Centra il contenuto orizzontalmente */
            width: 100%; /* Assicura che il contenitore occupi tutta la larghezza */
        }

        .box {
            margin-top: 20vh;
            background-color: orange;
            color: black;
            border-radius: 30px;
            width: 70%;
            max-width: 600px; /* Limita la larghezza massima */
            height: auto; /* Permette al box di adattarsi al contenuto */
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Aggiunge un'ombra per migliorare la visibilità */
        }

        .box form {
            width: 100%; /* Occupa tutta la larghezza del box */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .box input[type="password"] {
            width: 100%; /* Occupa tutta la larghezza del form */
            margin-bottom: 20px;
        }

        h1 {
            font-size: x-large;
            margin-bottom: 20px; /* Aggiungi un margine inferiore per separare meglio gli elementi */
        }

        a {
            text-decoration: none;
            color: black;
        }

        .logo-container a {
            color: orange;
            font-size: xx-large; /* Aumenta la dimensione del testo per renderlo più visibile */
        }


    </style>
</head>
<body class="text-center">
    <div class="logo-container">
        <h2><a href="../index.php">AUTOWORLD</a></h2>
    </div>
        <div class="box">
            <h1>Modifica Password</h1>
            <form name="myForm" action="new-password.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
                <input type="password" placeholder="Vecchia Password" name="inputOldPassword" class="form-control" required>
                <input type="password" name="inputNewPassword" class="form-control" placeholder="Nuova Password" required />
                <input type="password" name="inputConfermaPassword" class="form-control" placeholder="Conferma Nuova Password" required />

                <button type="submit" class="btn btn-primary">Conferma!</button>
            </form>
        </div>

        <script>
        function alertRmb() {
            // Funzione di alert personalizzata se necessario
        }
    </script>
</body>
</html>
