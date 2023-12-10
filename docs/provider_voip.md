

### 2. Configurazione del SIP Trunk
Dovrai configurare un SIP Trunk con il tuo provider VoIP. Questo richiede di modificare il file `sip.conf` in `/etc/asterisk/`. Di seguito un esempio di configurazione base:

```ini
[general]
context=default
allowoverlap=no
udpbindaddr=0.0.0.0
tcpenable=no

[provider_name]
type=friend
username=tuo_username
secret=tua_password
host=host_del_provider
fromuser=tuo_username
fromdomain=host_del_provider
context=from-trunk
```

- `provider_name` è un nome a tua scelta per il trunk.
- `username` e `secret` sono forniti dal tuo provider VoIP.
- `host` è l'indirizzo del server SIP del provider.

### 3. Configurazione delle Dialplan
Dopo aver configurato il SIP Trunk, devi configurare le dialplan in `extensions.conf` in `/etc/asterisk/`. Questo file definisce come Asterisk gestirà le chiamate in entrata e in uscita.

Esempio di dialplan semplice:

```ini
[default]
exten => _X.,1,Dial(SIP/provider_name/${EXTEN})

[from-trunk]
exten => 123456789,1,Dial(SIP/interno1)
exten => 987654321,1,Dial(SIP/interno2)

```



Questo esempio di dialplan inoltrerà tutte le chiamate (rappresentate da `_X.`) al SIP trunk del tuo provider.

### 4. Riavvio di Asterisk e Test
Dopo aver effettuato le modifiche, riavvia Asterisk per applicare le nuove configurazioni:

```bash
sudo asterisk -rx "core reload"
```

### 5. Testare la Configurazione
Effettua una chiamata di test per assicurarti che la configurazione sia corretta. Se hai problemi, controlla i log di Asterisk per individuare eventuali errori.

### Considerazioni Addizionali
- **Sicurezza**: Assicurati che la configurazione sia sicura. Considera l'utilizzo di firewall, fail2ban e la disattivazione di `allowguest` in `sip.conf`.
- **Codec e Qualità del Suono**: Assicurati di configurare i codec corretti per una buona qualità del suono.
- **Backup e Monitoraggio**: Considera la creazione di backup regolari delle configurazioni e l'implementazione di un sistema di monitoraggio.
