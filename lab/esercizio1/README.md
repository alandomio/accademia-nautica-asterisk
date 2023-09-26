### Esercitazione di Laboratorio

#### Obiettivo
Collegare due telefoni SIP (SNOM) a un centralino Asterisk e effettuare una chiamata tra i due.

#### Materiali
- Due telefoni SIP (SNOM)
- Un computer con Asterisk installato
- Rete LAN

#### Procedura

1. **Installazione Asterisk**
   - Esempio per Ubuntu LTS:
     ```bash
     sudo apt update
     sudo apt install asterisk
     ```

2. **Configurazione SIP**
   - Modificare il file `/etc/asterisk/sip.conf`
   - Aggiungere le seguenti righe per ogni telefono:
     ```ini
     [phone1]
     type=friend
     host=dynamic
     secret=yourpassword
     context=internal

     [phone2]
     type=friend
     host=dynamic
     secret=yourpassword
     context=internal
     ```


### Configurazione delle Porte

1. **Configurazione Firewall su Windows**: Vai al pannello di controllo del Firewall di Windows e aggiungi una regola in entrata e una in uscita per consentire il traffico sulla porta 5060 (SIP) e sulle porte RTP (es. 10000-20000).

2. **Modificare il file `sip.conf` e `rtp.conf`**: 
    - Apri `/etc/asterisk/sip.conf` e configura la sezione `[general]` per ascoltare su tutte le interfacce:
        ```ini
        [general]
        bindaddr=0.0.0.0
        ```
    - Apri `/etc/asterisk/rtp.conf` e configura le porte RTP:
        ```ini
        [general]
        rtpstart=10000
        rtpend=20000
        ```

3. **Ricarica la configurazione di Asterisk**:
    ```bash
    sudo asterisk -rx "sip reload"
    sudo asterisk -rx "rtp reload"
    ```

3. **Configurazione Dial Plan**
   - Modificare il file `/etc/asterisk/extensions.conf`
   - Aggiungere le seguenti righe:
     ```ini
     [internal]
     exten => 1001,1,Dial(SIP/phone1)
     exten => 1002,1,Dial(SIP/phone2)
     ```

4. **Riavvio Asterisk**
   ```bash
   sudo systemctl restart asterisk
   ```

5. **Configurazione Telefoni SIP**
   - Inserisci l'indirizzo IP del server Asterisk, username (e.g., phone1), e password (e.g., yourpassword).

6. **Test Chiamata**
   - Effettuare una chiamata da `phone1` a `phone2` utilizzando il numero interno (e.g., 1002).

Ecco un riassunto su come potresti impostare le tue slide e la tua esercitazione di laboratorio. Sentiti libero di adattarlo alle tue specifiche esigenze.