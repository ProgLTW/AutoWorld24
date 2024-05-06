<!DOCTYPE html> 
<html>
<head>
    <title>AutoWorld</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="./assets/favicon-32x32.png"/>
    <link rel="stylesheet" href="sign-in.css">
    <link rel="stylesheet" href="../style.css">
    <script type="application/javascript">
        const modelliPerMarca = {
            "Audi": ["ModelloA1", "ModelloA3", "ModelloA4"],
            "BMW": ["Serie1", "Serie3", "Serie5"],
        };
        function updateModelloOptions(marcaSelezionata) {
            const modelloSelect = document.getElementById("modello");
            modelloSelect.innerHTML = '<option value="">Seleziona</option>';
            const modelli = modelliPerMarca[marcaSelezionata];
            if (modelli) {
                modelli.forEach(modello => {
                    const option = document.createElement('option');
                    option.text = modello;
                    option.value = modello;
                    modelloSelect.add(option);
                });
                modelloSelect.disabled = false;
            } else {
                modelloSelect.disabled = true;
            }
        }
        function updateMassimo(fromId, toId) {
            var fromValue = document.getElementById(fromId).value;
            var toSelect = document.getElementById(toId);
            var toValue = toSelect.value;

            if (fromValue !== "") {
                toSelect.querySelectorAll("option").forEach(function(option) {
                    if (parseInt(option.value) < parseInt(fromValue)) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            } else {
                toSelect.querySelectorAll("option").forEach(function(option) {
                    option.disabled = false;
                });
            }

            if (toValue !== "" && parseInt(toValue) < parseInt(fromValue)) {
                toSelect.value = fromValue;
            }
        }

        function updateMinimo(fromId, toId) {
            var fromSelect = document.getElementById(fromId);
            var fromValue = fromSelect.value;
            var toValue = document.getElementById(toId).value;

            if (toValue !== "") {
                fromSelect.querySelectorAll("option").forEach(function(option) {
                    if (parseInt(option.value) > parseInt(toValue)) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            } else {
                fromSelect.querySelectorAll("option").forEach(function(option) {
                    option.disabled = false;
                });
            }

            if (fromValue !== "" && parseInt(fromValue) > parseInt(toValue)) {
                fromSelect.value = toValue;
            }
    }
    </script>
    <style> 
        .icon-auto {
            width: 150px; /* Larghezza desiderata */
            height: auto; /* Altezza automaticamente ridimensionata in base alla larghezza */
        }
        form {
            margin: auto;
            font-family: 'Formula1 Display';
            width: 120%;
            margin-left: 150px;
        }
        form label {
            display: inline-block;
            margin-bottom: 5px; /* Riduce lo spazio inferiore dell'etichetta */
        }
        form select {
            margin-top: 5px; /* Sposta la casella di selezione verso l'alto */
        }
        select {
            font-family: 'Formula1 Display', sans-serif; /* Cambia il font delle caselle di selezione */
            font-size: 16px; /* Regola la dimensione del font se necessario */
        }
        input {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
        button {
            font-family: 'Formula1 Display';
            font-size: 16px;
        }
    </style>
</head>
<body class="text-center">
    <nav>
        <ul>
        <li><a href="../index.php"><b>AUTOWORLD</b></a></li>
        <li><a href="index.html" class="btn btn-primary btn-lg" 
            role="button">
            <b>RICERCA</b>
        </a></li>
        <li><a href="../vendi/index.html"><b>VENDI</b></a></li>
        <li><a href="ricambi.php"><b>RICAMBI</b></a></li>
        <li><a href="preferiti.php"><b>PREFERITI</b></a></li>
        <li><a href="../login/index.html" class="btn btn-primary btn-lg" 
            role="button">
            LOGIN
        </a></li>
        <li><a href="../registrazione/index.html" class="btn btn-primary btn-lg" 
            role="button">
            REGISTRATI
        </a></li>
        </ul>
    </nav>
    <div class="container2">
        <form name="myForm" action="ricerca.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
            <label for="marca">Marca:</label>
            <select id="marca" name="marca" required onchange="updateModelloOptions(this.value)">
                <option value="">Seleziona</option>
                <option value="Audi">Audi</option>
                <option value="BMW">BMW</option>
                <option value="Mercedes">Mercedes-Benz</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Ford">Ford</option>
                <option value="Chevrolet">Chevrolet</option>
                <option value="Nissan">Nissan</option>
                <option value="Hyundai">Hyundai</option>
                <option value="Mazda">Mazda</option>
                <option value="Kia">Kia</option>
                <option value="Subaru">Subaru</option>
                <option value="Fiat">Fiat</option>
                <option value="Volvo">Volvo</option>
                <option value="Jeep">Jeep</option>
                <option value="Land Rover">Land Rover</option>
                <option value="Renault">Renault</option>
                <option value="Peugeot">Peugeot</option>
                <option value="Citroën">Citroën</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Alfa Romeo">Alfa Romeo</option>
                <option value="Lancia">Lancia</option>
                <option value="Maserati">Maserati</option>
                <option value="Jaguar">Jaguar</option>
                <option value="Ferrari">Ferrari</option>
                <option value="Porsche">Porsche</option>
                <option value="Acura">Acura</option>
                <option value="Bentley">Bentley</option>
                <option value="Buick">Buick</option>
                <option value="Cadillac">Cadillac</option>
                <option value="Chrysler">Chrysler</option>
                <option value="Dodge">Dodge</option>
                <option value="Fiat">Fiat</option>
                <option value="GMC">GMC</option>
                <option value="Infiniti">Infiniti</option>
                <option value="Lamborghini">Lamborghini</option>
                <option value="Lexus">Lexus</option>
                <option value="Lincoln">Lincoln</option>
                <option value="Lotus">Lotus</option>
                <option value="Mini">Mini</option>
                <option value="Rolls-Royce">Rolls-Royce</option>
                <option value="Smart">Smart</option>
                <option value="Tesla">Tesla</option>
                <option value="Vauxhall">Vauxhall</option>
                <option value="Volvo">Volvo</option>
            </select> <br>
            <label for="modello">Modello:</label>
            <select id="modello" name="modello" disabled required>
                <option value="">Seleziona</option>
            </select><br>
            <label for="prezzo">Prezzo:</label>
            <select id="prezzo_da" type="number" name="Da" onchange="updateMassimo('prezzo_da', 'prezzo_a')">
                <option value="">Da</option>
                <option type="number" value="500">500€</option>
                <option type="number" value="1000">1000€</option>
                <option type="number" value="1500">1500€</option>
                <option type="number" value="2000">2000€</option>
                <option type="number" value="2500">2500€</option>
                <option type="number" value="3000">3000€</option>
                <option type="number" value="4000">4000€</option>
                <option type="number" value="5000">5000€</option>
                <option type="number" value="6000">6000€</option>
                <option type="number" value="7000">7000€</option>
                <option type="number" value="8000">8000€</option>
                <option type="number" value="9000">9000€</option>
            </select>
            <select id="prezzo_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('prezzo_da', 'prezzo_a')">
                <option value="">A</option>
                <option type="number" value="500">500€</option>
                <option type="number" value="1000">1000€</option>
                <option type="number" value="1500">1500€</option>
                <option type="number" value="2000">2000€</option>
                <option type="number" value="2500">2500€</option>
                <option type="number" value="3000">3000€</option>
                <option type="number" value="4000">4000€</option>
                <option type="number" value="5000">5000€</option>
                <option type="number" value="6000">6000€</option>
                <option type="number" value="7000">7000€</option>
                <option type="number" value="8000">8000€</option>
                <option type="number" value="9000">9000€</option>
            </select><br>
            <label for="carrozzeria">Carrozzeria:</label>
            <select id="carrozzeria" name="carrozzeria" required>
                <option value="">Seleziona</option>
                <option value="City Car">City Car</option>
                <option value="Cabrio">Cabrio</option>
                <option value="Suv/Fuoristrada/Pick-up">Suv/Fuoristrada/Pick-up</option>
                <option value="Station Wagon">Station Wagon</option>
                <option value="Berlina">Berlina</option>
                <option value="Monovolume">Monovolume</option>
            </select><br>
            <label for="anno">Anno:</label>
            <select id="anno_da" type="number" name="Da" onchange="updateMassimo('anno_da', 'anno_a')">
                <option value="">Da</option>
                <script>
                    for (let i = 2024; i >= 1900; i--) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select>
            <select id="anno_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('anno_da', 'anno_a')">
                <option value="">A</option>
                <script>
                    for (let i = 2024; i >= 1900; i--) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select><br>
            <label for="chilometraggio">Chilometraggio:</label>
            <select id="km_da" type="number" name="Da" onchange="updateMassimo('km_da', 'km_a')">
                <option value="">Da</option>
                <script>
                    for (let i = 0; i <= 200000; i = i + 25000) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select>
            <select id="km_a" type="number" name="A" style="margin-left: 0%;" onchange="updateMinimo('km_da', 'km_a')">
                <option value="">A</option>
                <script>
                    for (let i = 0; i <= 200000; i = i + 25000) {
                        document.write(`<option value="${i}">${i}</option>`);
                    }
                </script>
            </select><br>
            <label for="carburante">Carburante:</label>
            <select name="carburante" required>
                <option value="">Seleziona</option>
                <option value="Benzina">Benzina</option>
                <option value="Diesel">Diesel</option>
                <option value="Ibrida">Ibrida</option>
                <option value="GPL">GPL</option>
                <option value="Metano">Metano</option>
            </select><br>
            <label for="cambio">Cambio:</label>
            <select name="cambio" required>
                <option value="">Seleziona</option>
                <option value="manuale">Manuale</option>
                <option value="automatico">Automatico</option>
                <option value="semiautomatico">Semiautomatico</option>
            </select><br>
            <label for="potenza">Potenza (CV):</label>
            <input type="number" id="potenza_da" name="Da" onchange="updateMassimo('potenza_da', 'potenza_a')" placeholder="Da" min="0" max="1000">
            <input type="number" id="potenza_a" name="A" style="margin-left: 0%;" onchange="updateMinimo('potenza_da', 'potenza_a')" placeholder="A" min="0" max="1000"><br>
            <button type="submit" class="btn btn-primary" style="margin-left: 150px; margin-top: 30px; margin-bottom: 30px;">Cerca</button>
        </form>
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
</body>
</html>
