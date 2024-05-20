Progetto: Autoworld - sito web di compra-vendita di auto usate
Gruppo: Enrico Bosco, Giulia Cianci

Autoworld è cotituita da 5 sezioni principali: Autoworld(homepage), Ricerca, Vendi, Preferiti, Ciao utente. A queste si aggiungono le pagine di login e registrazione.

Stile.css: foglio di stile incluso in vari file del progetto con specifiche per le varie classi che modificano il layout di alcune pagine.

Autoworld: homepage del sito, questa pagina offre un'interfaccia intuitiva per gli utenti per navigare, visualizzare annunci di auto, gestire preferiti e accedere a informazioni e servizi offerti dal sito.
Contiene una navbar (presente in tutte le pagine del sito), così come sul fondo un footer, con le informazioni commerciali del sito, e una barra animata in cui scorrono i loghi delle principali marche di automobili.
Le informazioni uniche di questa pagina sono due sezioni pubblicitarie con link ad articoli su come acquistare o vendere auto usate e una sezione che consente di scegliere tra le due azioni fondamentali del sito, vendere o comprare.
Gli annunci di auto vengono visualizzati in una sezione centrale sfruttando un carosello.
Ogni annuncio include dettagli come marca, modello, chilometraggio, prezzo, anno, carburante e potenza.
Gli utenti possono aggiungere o rimuovere gli annunci dai loro preferiti cliccando su un'icona a forma di cuore. Se l'utente non è loggato, viene reindirizzato alla pagina di login.

La navbar:

    Ricerca:
    1. Ricerca personalizzata: contiene un form con opzioni compilabili quali marca, modello, chilometraggio, prezzo, anno, carburante e potenza.
    Il pulsante "Reset" cancella le sezioni del form compilate dall'utente.
    Il pulsante "Cerca" reindirizza alla pagina "Vedi annunci" in cui nella sezione degli annunci saranno presenti solo gli annunci con le caratteristiche inserite dall'utente, che le ritroverà già inserite nei filtri di "Vedi annunci"
    2. Vedi annunci: contiene 2 zone principali, la prima a sinistra presenta un form simile a ricerca personalizzata per filtrare gli annunci in base alle caratteristiche inserite; la seconda contiene una lista di tutti gli annunci disponibili. Cliccando sul cuore è possibile agiungere ai preferiti l'annuncio desiderato. 
    Se si clicca su "vedi dettagli" si viene reindirizzati alla pagina "Big annuncio".

    3. Big annuncio: contiene tutti dati dell'auto selezionata, inclusa la descrizione del venditore. Sul fondo è presente un bottone che, cliccandolo, permette di vedere la mail del venditore per gestire tra privati la compra-vendita. Accanto alla foto dell'auto è presente una sezione interattiva che permette di simulare la quota della rata dell'eventuale prestito necessario per l'acquisto dell'automobile, insieme ad altre info.

    Vendi: 
        contiene un form con opzioni compilabili quali marca, modello, chilometraggio, prezzo, se trattabile, anno, carburante, potenza, descrizione e l'inserimento di una foto.
        Il pulsante "Reset" cancella le sezioni del form compilate dall'utente.
        Il pulsante "Conferma", clicclabile solo se loggato, se tutti i dati sono corretti, pubblica l'annuncio sul sito, inserendolo quindi in "Vedi annunci" e nel carosello della homepage. Inoltre sarà visibile dal venditore nella sezione "I miei annunci" del suo profilo.

        1. Uploads: l'immagine inserita dall'utente sarà archiviata nella cartella "uploads" in una sottocartella chiamata come la mail del venditore (quindi univoca) .

    Chi siamo: Riconduce al footer che contiene tutte le informazioni del sito e come contattare i suoi proprietari, insieme ai link per i vari social del sito.

preferiti.php: contiene un carosello con tutti gli annunci che l'utente loggato ha segnato come preferiti; se non loggato rimanda alla pagina di login. Cliccando sul cuore si può rimuovere dai preferiti mentre cliccando sul nome dell'annuncio si viene reindirizzati a "big annuncio"

Login: form di login con email, password e possibilità di essere ricordati(salvare dati di accesso). Bisogna essere registrati per loggarsi. A sinistra del form ci sono 3 bottoni con login, registrati e password dimenticata che mandano alla rispettive pagine.

Registrati: form di registrazione con email, nome utente, password e relativa conferma, e possibilità di essere ricordati. La mail non deve essere già stata registrata in precedenza altrimenti si viene reindirizzati alla pagina di login. A sinistra del form ci sono 3 lottoni con login, registrati e password dimenticata che mandano alla rispettive pagine.

Password dimenticata: form che permette di recuperare la password dell'email inserita mandando alla mail dell'utente una password temporanea che permette di accedere, e che consiglia di modificare la password appena loggati.

Ciao, utente:
    1. I miei annunci: contiene un carosello con tutti gli annunci che l'utente loggato ha pubblicato. Ogni annuncio ha sul fondo un bottone: contrassegna come venduto che nasconde l'annuncio e rendi visibile che lo fa tornare visibile. Quando è presente uno, l'altro non c'è.
    2. Preferiti
    3. Modifica password: permette di modificare l'attuale password quando i possesso della vecchia.
    4. Esci: logout, tornano nella navbar le opzioni per registrarsi e loggarsi



Directory bootstrap e risorse: file importati per utilizzare rispettivamente le librerie bootstrap e per la realizzazione del carousel nella homepage.

Immagini: directory contenente tutte le immagini dell’applicazione.
