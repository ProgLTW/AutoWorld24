<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Responsive</title>
    <link rel="stylesheet" href="styles.css">


    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.navbar {
    background-color: orange;
    padding: 10px;
    position: relative;
}

.navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-logo {
    font-size: 24px;
    color: black;
    text-decoration: none;
}

.navbar-menu {
    display: flex;
    align-items: center;
}

.navbar-item {
    margin: 0 10px;
    color: black;
    text-decoration: none;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.navbar-item:hover {
    color: white;
    transform: scale(1.1);
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: orange;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-menu a {
    color: black;
    padding: 10px;
    display: block;
    text-decoration: none;
}

.dropdown-menu a:hover {
    background-color: white;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.navbar-toggle {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.navbar-toggle span {
    background-color: black;
    height: 3px;
    margin: 4px 0;
    width: 25px;
}

@media (max-width: 768px) {
    .navbar-menu {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        background-color: orange;
    }

    .navbar-menu.active {
        display: flex;
    }

    .navbar-toggle {
        display: flex;
    }

    .navbar-item {
        margin: 10px 0;
    }

    .dropdown-menu {
        position: static;
        box-shadow: none;
        background-color: inherit;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    const authMenu = document.getElementById('auth-menu');

    let isLoggedIn = true; // This should be replaced with actual authentication check
    let username = 'John Doe'; // This should be replaced with actual username from database

    if (isLoggedIn) {
        authMenu.innerHTML = `
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">Ciao, ${username}</a>
                <div class="dropdown-menu">
                    <a href="#">I miei annunci</a>
                    <a href="#">Preferiti</a>
                    <a href="#">Modifica password</a>
                    <a href="#">Esci</a>
                </div>
            </div>
        `;
    } else {
        authMenu.innerHTML = `
            <a href="#" class="navbar-item">Login</a>
            <a href="#" class="navbar-item">Registrati</a>
        `;
    }

    navbarToggle.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
    });
});

</script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-logo">Autoworld</a>
            <div class="navbar-menu" id="navbar-menu">
                <div class="navbar-item dropdown">
                    <a href="#" class="dropdown-toggle">Ricerca</a>
                    <div class="dropdown-menu">
                        <a href="#">Ricerca personalizzata</a>
                        <a href="#">Vedi annunci</a>
                    </div>
                </div>
                <a href="#" class="navbar-item">Vendi</a>
                <a href="#" class="navbar-item">Chi siamo</a>
                <a href="#" class="navbar-item">Preferiti</a>
                <div class="navbar-item dropdown" id="auth-menu">
                    <!-- Content will be inserted via JavaScript -->
                </div>
            </div>
            <div class="navbar-toggle" id="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <script src="script.js"></script>
</body>
</html>
