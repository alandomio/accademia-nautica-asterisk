
### Configurazione Generale

1. **Formati di registrazione**: 
   - `format=wav49|gsm|wav` specifica i formati in cui vengono registrati i messaggi vocali. Puoi cambiare l'ordine o rimuovere formati che non desideri utilizzare.
   - **Attenzione**: Modificare i formati su un sistema in produzione può richiedere la conversione manuale dei messaggi esistenti.

2. **Email di notifica**:
   - `serveremail=asterisk` definisce l'indirizzo email mittente per le notifiche.
   - `attach=yes` indica se allegare i messaggi vocali alle email.

3. **Impostazioni Messaggi**:
   - `maxmsg`, `maxsecs`, `minsecs`, ecc. permettono di configurare limiti sui messaggi vocali, come la lunghezza massima, minima e il numero massimo di messaggi per cartella.

4. **Altre impostazioni**:
   - `skipms`, `maxsilence`, `silencethreshold` servono per personalizzare la riproduzione dei messaggi.
   - `emaildateformat` e `pagerdateformat` per personalizzare il formato della data nelle notifiche.

5. **Opzioni avanzate**: 
   - `externnotify`, `smdienable`, `externpass`, ecc. consentono di collegare programmi esterni o servizi di notifica.

### Configurazione Mailbox

Ogni mailbox deve essere definita nel formato `[numero_mailbox] => [password],[nome],[email],[email_pager],[opzioni]`.

#### Esempio:
```ini
[default]
1234 => 4242,Example Mailbox,root@localhost
```
- `[default]`: Contesto di default per le mailbox.
- `1234`: Numero della mailbox.
- `4242`: Password per accedere alla mailbox.
- `Example Mailbox`: Nome dell'utente.
- `root@localhost`: Indirizzo email per le notifiche.

### Opzioni Personalizzate per Ogni Mailbox

- `tz`, `attach`, `saycid`, ecc. permettono di personalizzare il comportamento della mailbox (es. fuso orario, allegato email, annuncio del chiamante).

### Aliases e Contesti Multipli

- Puoi definire alias o organizzare le mailbox in contesti multipli per virtual hosting.

### Utilizzo della Segreteria Telefonica

1. **Accesso**: Gli utenti accedono alla loro mailbox componendo un numero specifico configurato nel dialplan di Asterisk.

2. **Ascolto Messaggi**: Dopo l'accesso, possono ascoltare, salvare o cancellare i messaggi vocali.

3. **Cambiare Password**: È possibile cambiare la password della mailbox tramite il menu della segreteria telefonica.

4. **Opzioni Avanzate**: Gli utenti possono anche configurare saluti personalizzati, rispondere ai messaggi, ecc.

### Note Importanti

- Assicurati che le impostazioni siano compatibili con il tuo ambiente di rete e che siano conformi alle normative locali.
- Testa la configurazione in un ambiente non produttivo prima di applicarla a un sistema in produzione.
- Considera la sicurezza dei dati, soprattutto se i messaggi vocali contengono informazioni sensibili.

Questa guida fornisce una panoramica delle configurazioni principali basata sul tuo file. Per dettagli specifici o esigenze

particolari, potrebbe essere necessario consultare la documentazione ufficiale di Asterisk o richiedere assistenza a un esperto in sistemi VoIP. 

### Integrazione con il Sistema Telefonico

Dopo aver configurato il file `voicemail.conf`, dovrai assicurarti che la segreteria telefonica sia integrata correttamente nel tuo sistema telefonico:

1. **Dialplan**: Aggiungi voci nel dialplan di Asterisk (`extensions.conf`) per indirizzare le chiamate alla segreteria telefonica. Ad esempio, puoi impostare una regola che invia le chiamate non risposte alla segreteria telefonica dell'utente.

2. **IVR e Menu di Navigazione**: Se utilizzi un sistema IVR (Interactive Voice Response), assicurati che gli utenti possano accedere facilmente alla loro segreteria telefonica attraverso le opzioni del menu.

3. **Testing**: Esegui test per assicurarti che le chiamate vengano correttamente instradate alla segreteria telefonica e che le notifiche via email vengano inviate correttamente.

