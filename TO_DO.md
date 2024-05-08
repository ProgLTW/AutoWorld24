1. Start
-Nella cartella principale
git clone git@github.com:ProgLTW/AutoWorld24.git
-Nella cartella AutoWorld (after changes)
cd AutoWorld
git add .
git commit -m "message..."
git push
2. Before changes
git fetch origin main
git merge origin/main
3. After changes
git add .
git commit -m "message..."
git push



---FATTO---
Pagina di login: le caselle login e registrazione sono a cazzo di cane --FATTO--
alla pagina degli annunci aggiungere filtri. --FATTO--
pagina ricerca personalizzata --FATTO--
BARRA ANNUNCI NELLA HOMEPAGE(INDEX.PHP), COLLEGARE CON ANNUNCI DATABASE --FATTO--
pulsante RICERCA a tendina: -RICERCA PERSONALIZZATA -VEDI ANNUNCI --FATTO-
pulsante logout --FATTO--
sistemare barra in alto --FATTO--
modifica password --FATTO--
design vedi annunci --FATTO--
quando clicco preferiti non resta loggato --FATTO--
Preferiti deve ricondurre ai preferiti se loggato, al login se non loggato --FATTO--

    
---DA FARE---
Pagina "Il mio profilo"
    ->menù a tendina con "i miei annunci", "salvati"

Login effettuato

ricerca peronalizzata deve ricondurre a vedi annunci coi filtri gia compilati(forse)

far funzionare filtri-annunci

in vedi annunci dropdown-menu non si sovrappone a tutto

I miei annunci: Query failed: ERROR: column "email" does not exist LINE 1: SELECT * FROM annuncio WHERE email = $1 