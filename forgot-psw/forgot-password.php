<!DOCTYPE html>
<html lang="it">
<head>
    <title>AutoWorld - Reset password</title>
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
            margin-top: 5px;
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
            margin-top: 4vh;
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
        .box input[type="email"] {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 18px;
            border-radius: 10px;
        }
        h1 {
            font-size: x-large;
        }
        h2 {
            margin-top: 20px;
            font-size: xx-large;
            margin-bottom: 200px;
        }
        table {
            background-color: orange;
            color: black;
            border-radius: 30px;
            margin: auto;
            width: 80%;
            height: 400px;
        }
        a {
            text-decoration: none;
            color: black;
        }
        .logo-container a {
            color: orange;
        }
    </style>
</head>
<body class="text-center">
    <div class="logo-container">
        <h2><a href="../index.php">AUTOWORLD</a></h2>
    </div>
    <div class="box">
        <h1>Recupera Password</h1>
        <form name="myForm" action="../forgot-psw/send-mail.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit" class="btn btn-primary">Invia mail</button>
        </form>
    </div>
    <script>
        function alertRmb() {
        }
    </script>
</body>
</html>
