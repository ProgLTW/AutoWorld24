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
        
        /* Add your CSS styles here */
        .block{
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
            background-color: white;
        }
        .column{
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            margin: 10px;
        }
        .section {
            flex: 1;
            margin-right: 20px; /* Add spacing between sections */
        }
        .section:last-child {
            margin-right: 0; /* Remove margin from last section */
        }
        .section-header {
            font-size: 1.5em;
            font-weight: bold;
        }
        .section-content {
            /* Add your styles for section content */
        }
    
    </style>
</head>
<body class="text-center">
    <nav>
        <ul>
        <li><a href="../index.php"><b>AUTOWORLD</b></a></li>
        <li class="dropdown">
            <a class="btn btn-primary btn-lg dropbtn" role="button"><b>RICERCA</b></a>
            <div class="dropdown-menu">
                <a href="../ricerca/ricerca-personalizzata.php">Ricerca Personalizzata</a>
                <a href="../ricerca/vedi-annunci.php">Vedi Annunci</a>
            </div>
            </li>
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
    <div class="container">
    <div class="column">
        <!-- Content for the first column -->
        <h2>Column 1</h2>
        <p>This is the content of the first column.</p>
    </div>
    <div class="column">
        <!-- Content for the second column -->
        <h2>Column 2</h2>
        <p>This is the content of the second column.</p>
    </div>
    
        <!-- Filtri Section -->
        <section class="section" id="filtri">
            <div class="section-header">Filtri</div>
            <div class="section-content">
                <!-- Add your filter options here -->
                <label for="filter-category">Category:</label>
                <select id="filter-category">
                    <option value="all">All</option>
                    <option value="cars">Cars</option>
                    <option value="bikes">Bikes</option>
                    <!-- Add more options as needed -->
                </select>
                <!-- Add more filter options as needed -->
            </div>
        </section>

        <!-- Annunci Section -->
        <section class="section" id="annunci">
            <div class="section-header">Annunci</div>
            <div class="section-content">
                <!-- Add your advertisements here -->
                <div class="advertisement">
                    <h2>Advertisement 1</h2>
                    <p>Description of advertisement 1</p>
                </div>
                <div class="advertisement">
                    <h2>Advertisement 2</h2>
                    <p>Description of advertisement 2</p>
                </div>
                <!-- Add more advertisements as needed -->
            </div>
        </section>
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
</body>
</html>
