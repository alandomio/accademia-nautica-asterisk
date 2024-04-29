# Esercizio: Creazione di un Sistema IVR

## Obiettivo
Lo scopo di questo esercizio è creare un sistema IVR (Interactive Voice Response) utilizzando Asterisk. Il sistema IVR è un sistema telefonico che interagisce con i chiamanti e li indirizza alle giuste opzioni senza l'intervento umano.

## Requisiti
1. Il sistema IVR deve accogliere il chiamante con un messaggio di benvenuto.
2. Dopo il messaggio di benvenuto, il sistema deve presentare al chiamante un menu di opzioni. Ad esempio, "Premi 1 per le vendite, 2 per il supporto, 3 per l'ufficio amministrativo".
3. Il sistema deve essere in grado di indirizzare il chiamante alla giusta destinazione in base alla scelta effettuata.
4. Se il chiamante non fa una scelta, il sistema deve ripetere il menu di opzioni.
5. Il sistema deve gestire gli errori, come una scelta non valida, riproducendo un messaggio di errore e ripresentando il menu di opzioni.

Se desideri utilizzare uno script AGI per gestire il playback e altre logiche nel tuo IVR, possiamo rivedere la configurazione del dialplan in `extensions.conf` per incorporare chiamate agli script AGI. Ecco come potrebbe apparire la configurazione con l'integrazione degli script AGI:

### 1. Configurazione di `extensions.conf`
Modificheremo il dialplan per utilizzare gli script AGI per la riproduzione dei messaggi e la gestione del flusso delle chiamate:

```plaintext
[ivr-menu]
exten => s,1,Answer()
 same => n,AGI(speech.php, "La ACME srl ti dà il benvenuto.")
 same => n,Wait(1)
 same => n(begin),AGI(speech.php, "Per favore selezionare un'opzione dal menu. Premi 1 per le vendite, 2 per il supporto, 3 per l'ufficio amministrativo.")
 same => n,WaitExten(10) ; Aspetta l'input dell'utente per 10 secondi

 ; Opzioni del menu
 exten => 1,1,AGI(speech.php, "Hai scelto le vendite. Inoltreremo la tua chiamata al reparto vendite.")
 same => n,Goto(sales,s,1) ; Redirige a 'sales'
 exten => 2,1,AGI(speech.php, "Hai scelto il supporto tecnico. Inoltreremo la tua chiamata al reparto supporto tecnico.")
 same => n,Goto(support,s,1) ; Redirige a 'support'
 exten => 3,1,AGI(speech.php, "Hai scelto l'ufficio amministrativo. Inoltreremo la tua chiamata all'ufficio amministrativo.")
 same => n,Goto(admin,s,1) ; Redirige a 'administration'

 ; Gestione dell'input non valido
 exten => i,1,AGI(error.php, "Scelta non valida.") ; Notifica scelta non valida tramite AGI
 same => n,Goto(ivr-menu,s,begin) ; Ritorna al menu iniziale

 ; Se non viene effettuata nessuna scelta
 exten => t,1,AGI(no-input.php, "Nessun input rilevato.") ; Riproduce il messaggio di nessun input tramite AGI
 same => n,Goto(ivr-menu,s,begin) ; Ritorna al menu iniziale
```

### 2. Creazione degli script AGI in PHP
Dovrai creare diversi script PHP che utilizzeranno la libreria AGI di Asterisk per gestire la riproduzione dei messaggi e altre funzioni. Ecco un esempio base di come potrebbe apparire uno script AGI in PHP:

```php
#!/usr/bin/php -q
<?php
include('phpagi.php');
$agi = new AGI();

$text = $argv[1];
$agi->answer();
$agi->text2wav($text);
$agi->hangup();
?>
```
Questo script accetta un parametro di testo, che viene convertito in audio e riprodotto al chiamante. Assicurati che ogni script PHP sia reso eseguibile e sia posizionato nella directory appropriata per gli script AGI.

### 3. Test del sistema IVR
Come sempre, dopo aver configurato le modifiche, è essenziale eseguire test approfonditi per assicurarti che l'IVR funzioni correttamente, gestendo le chiamate come desiderato e riproducendo correttamente i messaggi tramite gli script AGI.

Questi passaggi ti permetteranno di configurare un sistema IVR dinamico che utilizza script AGI per una maggiore flessibilità e personalizzazione nella gestione delle chiamate.