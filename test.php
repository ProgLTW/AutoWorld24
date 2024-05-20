<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carosello Orizzontale</title>
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    .car-carousel {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px; /* Altezza del carosello */
        overflow: hidden;
        white-space: nowrap;
        display: flex; /* Utilizzo di Flexbox per distribuire le caselle */
    }

    .car-carousel .box {
        flex: 1; /* Flessibilità delle caselle per distribuirle uniformemente */
        height: 100px; /* Altezza della casella */
        background-color: #ccc; /* Colore di sfondo della casella */
        margin-right: 20px; /* Spazio tra le caselle */
        animation: carousel 10s linear infinite; /* Velocità e loop dell'animazione */
    }

    .car-carousel .box1 {
        flex: 1; /* Flessibilità delle caselle per distribuirle uniformemente */
        height: 100px; /* Altezza della casella */
        background-color: red; /* Colore di sfondo della casella */
        margin-right: 20px; /* Spazio tra le caselle */
    }

    @keyframes carousel {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
</style>
</head>
<body>

<div class="car-carousel">
    <div class="box1"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>

</body>
</html>
