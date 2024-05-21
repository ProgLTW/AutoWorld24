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
            height: 100%; 
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url(../immagini/sfondologin.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Formula1 Display';
            color: orange;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-container {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .box-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .box {
            margin-top: 20vh;
            background-color: orange;
            color: black;
            border-radius: 30px;
            width: 70%;
            max-width: 600px;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .box form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .box input[type="password"] {
            width: 100%;
            margin-bottom: 20px;
        }
        h1 {
            font-size: x-large;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: black;
        }
        .logo-container a {
            color: orange;
            font-size: xx-large;
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
            <input type="password" name="inputConfermaPassword" class="form-control" placeholder="Conferma Nuova Password" required/>
            <button type="submit" class="btn btn-primary">Conferma!</button>
        </form>
    </div>
    <script>
        function alertRmb() {
        }
    </script>
</body>
</html>
