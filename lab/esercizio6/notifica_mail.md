### Creazione di Script per Notifiche Personalizzate

Per questa parte dell'esercitazione, scriveremo uno script Bash che gestisce l'invio di e-mail con allegati dei messaggi vocali. Quindi, configureremo Asterisk per utilizzare questo script.

#### Step 1: Creazione dello Script Bash

1. **Preparazione dello Script**:
   - Crea un nuovo file script, ad esempio `send_voicemail.sh`, in una directory appropriata, ad esempio `/usr/local/bin/`.
   - Rendi lo script eseguibile con `chmod +x /usr/local/bin/send_voicemail.sh`.

2. **Contenuto dello Script**:
   - Ecco un esempio base di script che invia un'email con un allegato:

     ```bash
     #!/bin/bash

     # Parametri passati da Asterisk
     RECIPIENT=$1   # Email destinatario
     VM_MESSAGE=$2  # Percorso del file del messaggio vocale

     # Configurazione per l'invio dell'email
     SUBJECT="Nuovo Messaggio Vocale"
     EMAIL_BODY="Hai ricevuto un nuovo messaggio vocale."
     FROM="voicemail@example.com"

     # Invio dell'email
     echo "$EMAIL_BODY" | mutt -a "$VM_MESSAGE" -s "$SUBJECT" -- "$RECIPIENT"
     ```

   - Assicurati che il sistema abbia `mutt` installato (un client di posta elettronica basato su testo) per l'invio di e-mail. Installalo se non è presente.

3. **Test dello Script**:
   - Prima di procedere, testa lo script manualmente per assicurarti che funzioni come previsto.

#### Step 2: Configurazione di Asterisk

1. **Modifica di `voicemail.conf`**:
   - Apri il file `/etc/asterisk/voicemail.conf`.
   - Aggiungi o modifica la linea `externnotify` nella sezione appropriata per puntare al tuo script:
     ```
     [default]
     externnotify=/usr/local/bin/send_voicemail.sh
     ```
   - Questo dirà ad Asterisk di eseguire lo script `send_voicemail.sh` ogni volta che arriva un nuovo messaggio vocale.

2. **Riavvia Asterisk**:
   - Riavvia il servizio Asterisk per applicare le modifiche:
     ```
     sudo asterisk -rx "core restart now"
     ```

Con questo setup, ogni volta che un messaggio vocale viene lasciato in una delle caselle vocali configurate in `voicemail.conf`, il tuo script personalizzato verrà eseguito, inviando un'email con il messaggio vocale come allegato al destinatario specificato. Assicurati di testare a fondo il sistema per garantire che tutto funzioni come previsto.