<!DOCTYPE html>
<html lang="it">
<head>
    <title>AutoWorld - Pagina di login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/favicon-32x32.png">
    <link rel="stylesheet" href="./signin.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <script src="./rememberMe.js" type="application/javascript"></script>
</head>
<body class="text-center">
    <form name="myForm" action="login.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
        <img src="../assets/apple-touch-icon.png" class="mb-2" width="50px">
        <h1 class="mb-3">Loggati!</h1>
        <input type="email" placeholder="Indirizzo e-mail" name="inputEmail" class="form-control" required autofocus>
        <input type="password" placeholder="Password" name="inputPassword" class="form-control" required>
        <div id="divRemember" class="checkbox mb-2">
            <input type="checkbox" name="remember" id="rmb">
            <label for="rmb">Ricordami</label>
        </div>
        <button type="submit" class="btn btn-primary">Invia!</button>
    </form>
    <?php
        $dbconn = pg_connect ( "host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9" ) or die ('Could not connect : '. pg_last_error());
        $query = 'SELECT * FROM organizzazione';
        $result = pg_query ( $query ) or die ( 'Query failed : ' . pg_last_error ());
        // Printing results in HTML
        echo "<table>\n";
        while ( $line = pg_fetch_array ($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        pg_free_result($result);
        pg_close($dbconn);
    ?>
</body>
</html>
