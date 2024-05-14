
    <div class="container2">
    <form name="myMotoForm" action="vedi-annunci-moto.php" method="POST" class="form-signin m-auto" onsubmit="alertRmb()">
        <label for="marca">Marca:</label>
        <select id="marca" name="marca" onchange="updateModelloOptions(this.value)">
            <option value="">Seleziona</option>
            <option value="Ducati">Ducati</option>
            <option value="Harley-Davidson">Harley-Davidson</option>
            <option value="Honda">Honda</option>
            <option value="Kawasaki">Kawasaki</option>
            <option value="KTM">KTM</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Yamaha">Yamaha</option>
            <option value="Triumph">Triumph</option>
            <option value="Aprilia">Aprilia</option>
            <option value="Piaggio">Piaggio</option>
            <option value="Vespa">Vespa</option>
            <option value="MotoGuzzi">Moto Guzzi</option>
            <option value="BMWMotorrad">BMW Motorrad</option>
            <option value="IndianMotorcycle">Indian Motorcycle</option>
            <option value="RoyalEnfield">Royal Enfield</option>
            <option value="Husqvarna">Husqvarna</option>
            <option value="CFMoto">CFMoto</option>
            <option value="MVAgusta">MV Agusta</option>
            <option value="Benelli">Benelli</option>
            <option value="ZeroMotorcycles">Zero Motorcycles</option>
            
        </select> <br>
        
        <label for="modello">Modello:</label>
        <select id="modello" name="modello" disabled>
            <option value="">Seleziona</option>
        </select><br>
        
        <label for="prezzo">Prezzo:</label>
        <select id="prezzo_da" type="number" name="PrezzoDa" onchange="updateMassimo('prezzo_da', 'prezzo_a')">
            <option value="">Da</option>
            <?php
            for ($i = 500; $i <= 100000; $i += 1000) {
                echo "<option type='number' value='$i'>" . $i . "€</option>";
            }
            ?>
        </select>
        <select id="prezzo_a" type="number" name="PrezzoA" style="margin-left: 0%;" onchange="updateMinimo('prezzo_da', 'prezzo_a')">
            <option value="">A</option>
            <?php
            for ($i = 500; $i <= 100000; $i += 1000) {
                echo "<option type='number' value='$i'>" . $i . "€</option>";
            }
            ?>
        </select><br>
        
        <label for="carrozzeria">Carrozzeria:</label>
            <select id="carrozzeria" name="carrozzeria">
                <option value="">Seleziona</option>
                <option value="Naked">Naked</option>
                <option value="Sport">Sport</option>
                <option value="Touring">Touring</option>
                <option value="Cruiser">Cruiser</option>
                <option value="Adventure">Adventure</option>
                <option value="Dual-Sport">Dual-Sport</option>
            </select><br>

        <label for="anno">Anno:</label>
        <select id="anno_da" type="number" name="AnnoDa" onchange="updateMassimo('anno_da', 'anno_a')">
            <option value="">Da</option>
            <script>
                for (let i = 2024; i >= 1900; i--) {
                    document.write(`<option value="${i}">${i}</option>`);
                }
            </script>
        </select>
        <select id="anno_a" type="number" name="AnnoA" style="margin-left: 0%;" onchange="updateMinimo('anno_da', 'anno_a')">
            <option value="">A</option>
            <script>
                for (let i = 2024; i >= 1900; i--) {
                    document.write(`<option value="${i}">${i}</option>`);
                }
            </script>
        </select><br>
        
        <label for="chilometraggio">Chilometraggio:</label>
        <select id="km_da" type="number" name="KmDa" onchange="updateMassimo('km_da', 'km_a')">
            <option value="">Da</option>
            <script>
                for (let i = 0; i <= 200000; i = i + 25000) {
                    document.write(`<option value="${i}">${i}</option>`);
                }
            </script>
        </select>
        <select id="km_a" type="number" name="KmA" style="margin-left: 0%;" onchange="updateMinimo('km_da', 'km_a')">
            <option value="">A</option>
            <script>
                for (let i = 0; i <= 200000; i = i + 25000) {
                    document.write(`<option value="${i}">${i}</option>`);
                }
            </script>
        </select><br>
        
        <label for="carburante">Carburante:</label>
        <select name="carburante">
            <option value="">Seleziona</option>
            <option value="Benzina">Benzina</option>
            <option value="Elettrico">Elettrico</option>
            <option value="Diesel">Diesel</option>
            <option value="Ibrida">Ibrida</option>
            <option value="GPL">GPL</option>
        </select><br>
        
        <label for="cambio">Cambio:</label>
        <select name="cambio">
            <option value="">Seleziona</option>
            <option value="Manuale">Manuale</option>
            <option value="Automatico">Automatico</option>
            <option value="Semiautomatico">Semiautomatico</option>
        </select><br>
        
        <label for="potenza">Potenza (CV):</label>
        <input type="number" id="potenza_da" name="PotenzaDa" onchange="updateMassimo('potenza_da', 'potenza_a')" placeholder="Da" min="0" max="1000">
        <input type="number" id="potenza_a" name="PotenzaA" style="margin-left: 0%;" onchange="updateMinimo('potenza_da', 'potenza_a')" placeholder="A" min="0" max="1000"><br>
        
        <button type="submit" class="btn btn-primary" style="margin-left: 150px; margin-top: 30px; margin-bottom: 30px;">Cerca</button>
    </form>
</div>
