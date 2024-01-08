### Esercitazione su Voice Mail Avanzato con Asterisk

**Obiettivo**: Configurare e personalizzare le funzionalità avanzate di voicemail su un sistema Asterisk, con integrazione per l'invio di notifiche e messaggi vocali via e-mail.

#### **Parte 1: Configurazione Base della Voicemail**

1. **Configurazione di Voicemail in `voicemail.conf`**
   - Apri `/etc/asterisk/voicemail.conf`.
   - Configura una casella di posta vocale di esempio:
     ```
     [default]
     100 => 1234, Mario Rossi, mario.rossi@email.com
     ```
     Qui, `100` è l'ID della casella vocale, `1234` è il PIN, `Mario Rossi` il nome dell'utente, e `mario.rossi@email.com` l'indirizzo e-mail per le notifiche.

2. **Integrazione con Dialplan in `extensions.conf`**
   - Aggiungi una regola nel dialplan per inviare le chiamate non risposte alla voicemail:
     ```
     exten => 100,1,Dial(SIP/100,20)
     exten => 100,n,VoiceMail(100@default,u)
     exten => 100,n,Hangup()
     ```
     Qui, `20` è il tempo in secondi prima che la chiamata venga inviata alla voicemail.

#### **Parte 2: Invio di Notifiche E-mail**

1. **Configurazione SMTP**
   - Assicurati che il server su cui gira Asterisk sia configurato per inviare e-mail (ad esempio, con Postfix o Sendmail).

2. **Personalizzazione del Template E-mail**
   - Personalizza il template delle e-mail in `voicemail.conf` aggiungendo o modificando la sezione `[zonemessages]` e `[emailbody]`.

3. **Test di Invio**
   - Effettua una chiamata di test alla casella vocale e verifica la ricezione dell'e-mail.

#### **Parte 3: Integrazione Avanzata**

1. **Creazione di Script per Notifiche Personalizzate**
   - Scrivi uno script (ad esempio, in Python o Bash) che gestisca l'invio di e-mail con allegati dei messaggi vocali.
   - Configura Asterisk per utilizzare questo script modificando `voicemail.conf` con l'opzione `externnotify`.

2. **Automazione della Gestione Messaggi Vocali**
   - Implementa uno script per l'automazione, come la cancellazione automatica dei messaggi vocali dopo l'invio via e-mail.

3. **Registrazione per Log**
   - Configura i log per monitorare le attività delle caselle vocali.

#### **Parte 4: Test e Verifica**

1. **Test Completi**
   - Effettua test completi per ogni scenario, come messaggi vocali senza risposta, catturando e-mail non inviate, ecc.
   - Verifica la ricezione delle e-mail con i messaggi vocali allegati.

2. **Feedback e Ottimizzazione**
   - Raccogli feedback dagli utenti e ottimizza la configurazione in base alle esigenze.

