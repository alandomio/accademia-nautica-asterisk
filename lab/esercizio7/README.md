### Esercitazione sulla Configurazione della Sicurezza Avanzata per Asterisk

**Obiettivo**: Implementare tecniche avanzate di sicurezza nel sistema Asterisk, incluse la crittografia delle chiamate tramite TLS e SRTP, e l'uso di firewall e NAT traversal per proteggere il traffico IAX.

#### Parte 1: Configurazione della Crittografia delle Chiamate

1. **Crittografia con TLS**
   - Genera i certificati TLS per il server e i client:
     ```
     sudo asterisk -rx "module load res_crypto.so"
     sudo asterisk -rx "tls init"
     ```
   - Configura `iax.conf` per utilizzare TLS:
     ```
     [general]
     tlsenable=yes
     tlsbindaddr=0.0.0.0
     tlscertfile=/etc/asterisk/keys/asterisk.pem
     tlscafile=/etc/asterisk/keys/ca.crt
     tlscipher=ALL
     tlsclientmethod=tlsv1
     ```

2. **Crittografia con SRTP**
   - Installa la libreria SRTP:
     ```
     sudo apt-get install libsrtp0
     ```
   - Abilita SRTP in `iax.conf`:
     ```
     [general]
     encryption=yes
     forceencryption=yes
     ```

#### Parte 2: Configurazione Firewall e NAT

1. **Configurazione del Firewall**
   - Configura il firewall per consentire il traffico IAX. Se usi `iptables`, aggiungi una regola per la porta 4569 (porta IAX):
     ```
     sudo iptables -A INPUT -p udp --dport 4569 -j ACCEPT
     ```
   - Assicurati di salvare le impostazioni del firewall.

2. **NAT Traversal**
   - In `iax.conf`, configura le impostazioni NAT:
     ```
     [general]
     nat=yes
     externip=tuo_ip_pubblico
     localnet=tua_rete_locale/subnet
     ```
   - Configura il tuo router o il dispositivo NAT per inoltrare la porta 4569 al server Asterisk.

#### Parte 3: Test e Verifica

1. **Verifica delle Configurazioni di Sicurezza**
   - Testa le chiamate IAX tra i client per assicurarti che la crittografia TLS e SRTP sia attiva.
   - Utilizza strumenti come Wireshark per verificare che il traffico sia effettivamente crittografato.

2. **Test del Firewall e del NAT**
   - Verifica che il firewall stia permettendo solo il traffico necessario.
   - Esegui test da dietro il NAT per assicurarsi che le chiamate passino correttamente.

#### Parte 4: Documentazione e Best Practice

1. **Documenta la Configurazione**
   - Crea una documentazione dettagliata delle configurazioni effettuate, inclusi i file di configurazione e le regole del firewall.

2. **Best Practice di Sicurezza**
   - Discuti le best practice di sicurezza, come la regolare aggiornazione del software e la gestione sicura delle credenziali e dei certificati.

Questa esercitazione fornisce una comprensione pratica di come implementare la sicurezza avanzata in un sistema di telefonia basato su Asterisk, assicurando che le comunicazioni siano protette e che l'infrastruttura sia resiliente contro potenziali minacce.

### Note - generazione dei certificati

1. **Installare gli Strumenti Necessari**:
   - Assicurati che gli strumenti per la generazione di certificati, come OpenSSL, siano installati sul tuo sistema.

2. **Creazione della Chiave Privata e della Richiesta di Certificato (CSR)**:
   - Genera una chiave privata:
     ```bash
     openssl genpkey -algorithm RSA -out mykey.key
     ```
   - Crea una CSR (Certificate Signing Request) utilizzando la chiave privata:
     ```bash
     openssl req -new -key mykey.key -out mycsr.csr
     ```
   - Durante la creazione della CSR, ti verranno chieste informazioni come il nome della tua organizzazione, il tuo paese, ecc. Assicurati di inserire il nome corretto del dominio o dell'IP del server.

3. **Creazione del Certificato della CA**:
   - Se non hai ancora una CA, puoi crearne una generando una nuova chiave privata e un certificato auto-firmato:
     ```bash
     openssl req -new -x509 -days 1095 -key ca.key -out ca.crt
     ```
   - Questo certificato della CA sarà usato per firmare i certificati dei server o dei client.

4. **Firma della CSR con la CA**:
   - Firma la CSR con il certificato della CA per generare il certificato del server:
     ```bash
     openssl x509 -req -days 1095 -in mycsr.csr -CA ca.crt -CAkey ca.key -set_serial 01 -out mycert.crt
     ```
   - `set_serial` è un numero di serie univoco per il certificato, che puoi impostare a piacere.

5. **Installazione del Certificato sul Server Asterisk**:
   - Copia il certificato del server (`mycert.crt`) e la chiave privata (`mykey.key`) nella directory appropriata, ad esempio `/etc/asterisk/keys/`.
   - Assicurati che i file siano leggibili dall'utente che esegue Asterisk.

6. **Configurazione di Asterisk per Usare il Certificato**:
   - Configura `iax.conf` o `sip.conf` per utilizzare il certificato e la chiave:
     ```ini
     [general]
     tlsenable=yes
     tlsbindaddr=0.0.0.0
     tlscertfile=/etc/asterisk/keys/mycert.crt
     tlsprivatekey=/etc/asterisk/keys/mykey.key
     ```

7. **Distribuzione del Certificato della CA**:
   - Distribuisci il certificato della CA (`ca.crt`) ai client che si connetteranno al server, in modo che possano verificare la validità del certificato del server.

8. **Riavvio di Asterisk**:
   - Dopo aver apportato le modifiche alla configurazione, riavvia Asterisk per applicarle.

