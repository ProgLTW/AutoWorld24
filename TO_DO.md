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
submit in vendi funziona solo se loggato
database aggiornato (email linkata tra le due tabelle)
miei annunci funziona
aggiunto pulsante compra in vedi annunci
ricerca peronalizzata deve ricondurre a vedi annunci coi filtri gia compilati(forse) --FATTO--
Preferiti deve ricondurre ai preferiti se loggato, al login se non loggato --FATTO--
password dimenticata ma chiaramente non manda mail --FATTO--

---DA FARE---

Login effettuato

far funzionare filtri-annunci

preferiti + stellina

responsivenss (IMPORTANTE)

implementare l'acquisto (e relativa eliminazione annuncio + i miei acquisti)

password dimenticata 

aggiustare pagine errore e dati inseriti correttamente (sono brutti) -> ho messo un alert su vendi

big annuncio: 
    gestire foto multiple
    aggiungere altri dettagli(?)


IDEA "GIOCO" 
Simulatore di finanziamento:
Implementa uno strumento che consente agli utenti di inserire informazioni sul finanziamento desiderato, come il tasso di interesse, la durata del prestito, l'importo del finanziamento, ecc.
Mostra loro un piano di pagamento approssimativo e calcola le rate mensili per l'acquisto dell'auto usata nel tuo inventario. Da mettere sotto il prezzo in big-annuncio.