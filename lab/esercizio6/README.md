# Esercizi Dialplan Avanzato

## Esercizio 1: Creazione di un Dialplan per un Call Center
Crea un dialplan per un call center che ha tre dipartimenti: vendite, supporto tecnico e amministrazione. Ogni dipartimento ha tre agenti. Il dialplan dovrebbe distribuire le chiamate in modo equo tra gli agenti di ogni dipartimento.



## Esercizio 2: Implementazione di una Coda di Chiamate
Implementa una coda di chiamate. Quando un chiamante chiama, dovrebbe essere messo in attesa fino a quando un agente non è disponibile. Il chiamante dovrebbe essere informato della sua posizione nella coda e del tempo di attesa stimato.


## Esercizio 3: Creazione di un IVR Multilivello
Crea un IVR multilivello. Ad esempio, dopo aver selezionato l'opzione per il supporto tecnico, il chiamante dovrebbe avere ulteriori opzioni per scegliere tra supporto per prodotti diversi.



## Esercizio 4: Implementazione di un Sistema di Conferenza
Implementa un sistema di conferenza. Gli utenti dovrebbero essere in grado di chiamare un numero, inserire un codice di accesso e unirsi a una conferenza. Il sistema dovrebbe supportare più conferenze simultaneamente.



## Esercizio 5: Creazione di un Dialplan con Funzioni Avanzate
Crea un dialplan che utilizza funzioni avanzate come la registrazione delle chiamate, la gestione del tempo (ad esempio, diversi comportamenti durante le ore di lavoro e fuori orario) e le funzioni di database per memorizzare e recuperare informazioni.

Per creare un dialplan avanzato in Asterisk che includa la registrazione delle chiamate, la gestione del tempo e l'uso delle funzioni di database, seguiremo questi passi:

1. **Installazione e Configurazione di Base di Asterisk**: Assicuriamoci che Asterisk sia installato e configurato correttamente sul tuo sistema Ubuntu o Windows.

2. **Definizione del Dialplan**: Modificheremo il file `extensions.conf` per definire il comportamento del sistema telefonico.

3. **Registrazione delle Chiamate**: Utilizzeremo la funzione `MixMonitor` per registrare le chiamate.

4. **Gestione del Tempo**: Useremo il modulo `timeconditions` per definire comportamenti diversi a seconda dell'orario.

5. **Funzioni di Database**: Utilizzeremo le funzioni `DB` e `DBdel` per memorizzare e recuperare informazioni dal database interno di Asterisk.

### Esempio di Dialplan



```ini
[general]
static=yes
writeprotect=no
clearglobalvars=no

[globals]
; Definizione di variabili globali per la gestione del tempo
WORK_HOURS=09:00-17:00

[default]
exten => _X.,1,NoOp(Chiamata in arrivo da ${CALLERID(num)})

; Gestione del tempo
same => n,GotoIfTime(${WORK_HOURS},mon-fri,,?open,s,1:closed,s,1)
same => n,Hangup()

[open]
; Durante l'orario di lavoro
exten => s,1,Answer()
same => n,MixMonitor(${CALLERID(num)}-${STRFTIME(${EPOCH},,%Y%m%d-%H%M%S)}.wav)
; Qui inserire ulteriori istruzioni per gestire la chiamata
same => n,Hangup()

[closed]
; Fuori dall'orario di lavoro
exten => s,1,Playback(after-hours-message)
same => n,Hangup()

; Esempio di utilizzo delle funzioni di database
[database-functions]
exten => 1234,1,Set(DB(mychannel/${CALLERID(num)})=Value)
same => n,Playback(value-stored)
same => n,Hangup()

exten => 4321,1,Set(VALUE=${DB(mychannel/${CALLERID(num)})})
same => n,SayAlpha(${VALUE})
same => n,Hangup()
```

### Spiegazione

- **Sezione `[general]`**: Impostazioni generali del dialplan.
- **Sezione `[globals]`**: Definizione di variabili globali, come l'orario di lavoro.
- **Sezione `[default]`**: Gestisce le chiamate in entrata e decide se inviarle alla sezione `open` o `closed` in base all'orario.
- **Sezione `[open]`**: Gestisce le chiamate durante l'orario di lavoro, includendo la registrazione.
- **Sezione `[closed]`**: Gestisce le chiamate fuori dall'orario di lavoro.
- **Sezione `[database-functions]`**: Dimostra come utilizzare le funzioni di database per memorizzare e recuperare informazioni.



