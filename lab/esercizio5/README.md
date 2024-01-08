# Esercizio: Creazione di un Sistema IVR

## Obiettivo
Lo scopo di questo esercizio è creare un sistema IVR (Interactive Voice Response) utilizzando Asterisk. Il sistema IVR è un sistema telefonico che interagisce con i chiamanti e li indirizza alle giuste opzioni senza l'intervento umano.

## Requisiti
1. Il sistema IVR deve accogliere il chiamante con un messaggio di benvenuto.
2. Dopo il messaggio di benvenuto, il sistema deve presentare al chiamante un menu di opzioni. Ad esempio, "Premi 1 per le vendite, 2 per il supporto, 3 per l'ufficio amministrativo".
3. Il sistema deve essere in grado di indirizzare il chiamante alla giusta destinazione in base alla scelta effettuata.
4. Se il chiamante non fa una scelta, il sistema deve ripetere il menu di opzioni.
5. Il sistema deve gestire gli errori, come una scelta non valida, riproducendo un messaggio di errore e ripresentando il menu di opzioni.



Buon lavoro!
### Introduzione

Questa sessione pratica vi guiderà attraverso il processo di collegamento di vari centralini locali, ospitati sui PC degli allievi, con un centralino pubblico. Imparerete come configurare il protocollo IAX (Inter-Asterisk eXchange), essenziale per la comunicazione efficace in ambienti VoIP. Affronteremo la configurazione dei file iax.conf e extensions.conf, focalizzandoci sulla creazione di trunks IAX e sul routing delle chiamate.

### Configurazione del Centralino Pubblico

#### 1. Configurazione di `iax.conf`
Apri il file `iax.conf` sul centralino pubblico e aggiungi le seguenti configurazioni:

```ini
[general]
; altre impostazioni generali

; Definizione del trunk per ogni centralino locale
[centralino_locale_1]
type=friend
host=dynamic
username=centralino_locale_1
secret=password_segreto_1
context=from-internal
trunk=yes

[centralino_locale_2]
type=friend
host=dynamic
username=centralino_locale_2
secret=password_segreto_2
context=from-internal
trunk=yes

; Aggiungi configurazioni simili per altri centralini locali
```

#### 2. Configurazione di `extensions.conf`
Apri il file `extensions.conf` sul centralino pubblico e aggiungi un nuovo context per gestire le chiamate in entrata dai centralini locali:

```ini
[from-internal]
exten => _5X.,1,Dial(SIP/${EXTEN})
; Questo context inoltra le chiamate ai numeri che iniziano con 5 ai telefoni SIP appropriati
```

### Configurazione del Centralino Locale

#### 1. Configurazione di `iax.conf`
Apri il file `iax.conf` su ogni centralino locale e aggiungi:

```ini
[general]
; altre impostazioni generali

; Definizione del trunk verso il centralino pubblico
[centralino_pubblico]
type=friend
host=93.47.200.23
username=centralino_locale_1 ; cambia per ogni centralino
secret=password_segreto_1 ; cambia per ogni centralino
context=from-public
trunk=yes
```

#### 2. Configurazione di `extensions.conf`
Apri il file `extensions.conf` su ogni centralino locale e aggiungi:

```ini
[from-public]
exten => _5X.,1,Dial(IAX2/centralino_pubblico/${EXTEN})
; Questo context inoltra le chiamate ai numeri che iniziano con 5 al centralino pubblico tramite IAX
```

### Note
- Assicurati di cambiare `centralino_locale_1`, `password_segreto_1`, ecc., con i valori appropriati per ogni centralino locale.
- Le configurazioni sopra sono di base e possono richiedere ulteriori personalizzazioni a seconda delle esigenze specifiche e della struttura della rete.
- Ricorda di riavviare il servizio Asterisk su ogni macchina dopo aver apportato modifiche ai file di configurazione.
- È essenziale garantire la sicurezza delle comunicazioni, specialmente se si lavora con indirizzi IP pubblici e password.assicurati che tutte le connessioni siano protette e monitorate.

Nella configurazione di Asterisk, specialmente nei file `sip.conf` e `iax.conf`, si incontrano termini come `friend`, `peer` e `user`. Questi termini definiscono come Asterisk gestisce le connessioni SIP o IAX con altri dispositivi o sistemi. Ecco le differenze:

#### Parametro type

1. **Peer**:
   - Un `peer` è un endpoint (di solito un altro server o un dispositivo come un telefono IP) definito principalmente per la ricezione di chiamate in entrata o l'invio di chiamate in uscita.
   - Quando definisci un `peer`, Asterisk utilizza le impostazioni di questo endpoint solo per autenticare le chiamate in uscita verso questo endpoint o per autenticare le chiamate in entrata da questo endpoint.
   - In altre parole, la definizione `peer` è utilizzata quando il flusso delle chiamate è unidirezionale, sia in entrata che in uscita, ma non entrambi con lo stesso endpoint.

2. **User**:
   - Un `user` è il contrario di un `peer`. È definito per autenticare un endpoint che invia chiamate in entrata ad Asterisk, ma non per autenticare le chiamate in uscita verso quell'endpoint.
   - Questa configurazione è tipicamente utilizzata per dispositivi o sistemi che sono configurati per inviare chiamate ad Asterisk, ma non per ricevere chiamate da Asterisk.

3. **Friend**:
   - Un `friend` è una combinazione di `peer` e `user`. È utilizzato per gli endpoint che possono sia inviare che ricevere chiamate da Asterisk.
   - Quando definisci un `friend`, Asterisk lo tratta sia come un `user` che come un `peer`. Ciò significa che può essere utilizzato per autenticare sia le chiamate in entrata che quelle in uscita.
   - Questa configurazione è la più flessibile, poiché gestisce entrambe le direzioni delle chiamate con lo stesso set di configurazioni.

La scelta tra `peer`, `user` e `friend` dipende dalla tua specifica configurazione e dalle esigenze del tuo sistema. Se hai bisogno sia di chiamate in entrata che in uscita con lo stesso endpoint, `friend` è di solito la scelta migliore. Se invece le tue esigenze sono unicamente in una direzione (solo in entrata o solo in uscita), allora `peer` o `user` possono essere più appropriati per mantenere la configurazione più snella e sicura.


#### Parametro trunk

Nella configurazione di un trunk IAX in Asterisk, l'opzione `trunk=yes` ha un significato specifico e importante per la gestione del traffico voce tra differenti centralini Asterisk. Ecco cosa significa:

1. **Ottimizzazione del Traffico Voce**: Quando imposti `trunk=yes` in una definizione di trunk IAX nel file `iax.conf`, stai indicando ad Asterisk di trattare quella connessione come un "trunk". In termini di rete, un trunk è una singola connessione fisica o virtuale che può trasportare più canali di comunicazione. Nel contesto di IAX, ciò significa che più chiamate possono essere inviate attraverso una singola connessione IAX.

2. **Riduzione dell'Overhead**: Normalmente, ogni chiamata SIP o IAX tra due centralini richiederebbe una propria connessione di rete separata, con il proprio overhead per l'intestazione del pacchetto. Utilizzando `trunk=yes`, Asterisk impacchetta più chiamate in un unico flusso di dati IAX, riducendo così l'overhead di rete. Ciò è particolarmente vantaggioso quando si hanno molte chiamate contemporanee tra due siti.

3. **Miglioramento dell'Efficienza**: L'uso di trunk IAX migliora l'efficienza della banda e riduce la latenza, specialmente su link WAN (Wide Area Network) o su connessioni Internet dove l'overhead di rete può avere un impatto significativo.

4. **Utilizzo Tipico in Reti VoIP Distribuite**: Questa opzione è comune in reti VoIP distribuite dove diversi centralini Asterisk devono comunicare regolarmente e scambiarsi un alto volume di chiamate.

