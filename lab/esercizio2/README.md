

# Esercitazione su Messa in Sicurezza di Asterisk

## Introduzione

Questo documento serve come guida pratica per mettere in sicurezza un sistema Asterisk. Coprirà vari aspetti della sicurezza, da fondamenti come il principio del minimo privilegio, fino a configurazioni avanzate come l'uso di iptables e ACL.

---

## Indice

1. Perché la Sicurezza è Importante?
2. Fondamenti di Sicurezza
3. Sicurezza a Livello di Rete
4. Sicurezza a Livello di Protocollo
5. Sicurezza a Livello di Applicazione
6. Monitoraggio e Logging
7. Esercizi Pratici

---

### 1. Perché la Sicurezza è Importante?

#### Esercizio 1.1: Discussione
- Quali sono le implicazioni di un sistema non sicuro?

---

### 2. Fondamenti di Sicurezza

#### Esercizio 2.1: Principio del Minimo Privilegio
- Configurare un utente Asterisk con i minimi privilegi necessari.

#### Esercizio 2.2: Autenticazione e Autorizzazione
- Implementare un meccanismo di autenticazione e autorizzazione.

---




---



### 3. Sicurezza a Livello di Rete

La sicurezza a livello di rete è fondamentale per proteggere il sistema Asterisk da accessi non autorizzati e attacchi esterni. In questa sezione, esploreremo come configurare un firewall, impostare una VPN e implementare il rate limiting.

---

#### Esercizio 3.1: Firewall e IDS/IPS con iptables

**Obiettivo**: Configurare iptables per limitare l'accesso al server Asterisk.

**Codice di Esempio**:

```bash
# Pulire tutte le regole esistenti
iptables -F

# Bloccare tutto il traffico in ingresso
iptables -P INPUT DROP

# Permette il traffico SSH in ingress
iptables -A INPUT -p tcp --dport 22 -j ACCEPT

# Permette il traffico in uscita
iptables -P OUTPUT ACCEPT

# Permettere il traffico locale
iptables -A INPUT -i lo -j ACCEPT

# Permettere il traffico SIP su porta 5060 dall'IP x.x.x.x
iptables -A INPUT -p udp -s x.x.x.x --dport 5060 -j ACCEPT


# Permettere il traffico RTP sulle porte da 10000 a 20000
iptables -A INPUT -p udp --dport 10000:20000 -j ACCEPT

# Salvare le regole
service iptables save
```

**Note**:  
La configurazione di un firewall è uno dei primi passi nella messa in sicurezza di un sistema. Utilizzando `iptables`, è possibile definire regole che permettono o bloccano il traffico in ingresso o in uscita basato su vari parametri come l'indirizzo IP, la porta e il protocollo. Nel nostro esempio, abbiamo bloccato tutto il traffico in ingresso tranne quello necessario per il funzionamento di Asterisk.

---

#### Esercizio 3.2: VPN e Tunneling

**Obiettivo**: Creare una VPN per proteggere il traffico Asterisk.

**Codice di Esempio**:

```bash
# Installare OpenVPN
apt-get install openvpn

# Configurare OpenVPN (file server.conf)
dev tun
ifconfig 10.8.0.1 10.8.0.2
secret static.key
```

**Note**:  
L'uso di una VPN (Virtual Private Network) è un altro strato di sicurezza che può essere aggiunto per proteggere il traffico Asterisk. Una VPN cifra tutto il traffico che passa attraverso di essa, rendendo molto più difficile per un attaccante intercettare o manipolare i dati.

---

#### Esercizio 3.3: Rate Limiting

**Obiettivo**: Implementare il rate limiting per prevenire attacchi DoS.

**Codice di Esempio**:

```bash
# Limitare il numero di connessioni SIP per indirizzo IP
iptables -A INPUT -p udp --dport 5060 -m state --state NEW -m recent --set
iptables -A INPUT -p udp --dport 5060 -m state --state NEW -m recent --update --seconds 60 --hitcount 4 -j DROP
```

**Note**:  
Il rate limiting è una tecnica efficace per mitigare gli attacchi DoS (Denial of Service). Utilizzando `iptables`, è possibile limitare il numero di nuove connessioni per unità di tempo da un singolo indirizzo IP. Questo può prevenire che un attaccante sovraccarichi il sistema con un gran numero di richieste.

---

### 4. Sicurezza a Livello di Protocollo

#### Esercizio 4.1: Sicurezza SIP
- Configurare SIP over TLS.

### 4.2 Sicurezza a Livello di Protocollo: ACL (Access Control Lists)

La sicurezza a livello di protocollo è un elemento fondamentale per garantire che il sistema Asterisk sia protetto da attacchi che sfruttano le vulnerabilità nei protocolli di comunicazione. Le liste di controllo degli accessi (ACL) sono uno strumento potente per limitare l'accesso ai servizi e alle risorse del sistema. In questa sezione, esploreremo come configurare e utilizzare le ACL in Asterisk.

---

#### Esercizio 4.2.1: Introduzione alle ACL in Asterisk

**Obiettivo**: Comprendere il concetto di ACL e come possono essere utilizzate in Asterisk per migliorare la sicurezza.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[general]
; ACL per limitare l'accesso al solo indirizzo IP 192.168.1.1
permit=192.168.1.1/255.255.255.255
```

**Note del Relatore**:  
Le liste di controllo degli accessi (ACL) sono un meccanismo di sicurezza che permette di specificare quali host o reti hanno il permesso di accedere a determinate risorse o servizi. In Asterisk, le ACL possono essere configurate a vari livelli, inclusi i protocolli SIP e IAX, così come per l'accesso all'interfaccia di gestione (AMI).

L'uso delle ACL è particolarmente utile in scenari in cui si desidera limitare l'accesso a determinate funzionalità o servizi a un sottoinsieme di utenti o sistemi. Ad esempio, potreste voler permettere l'accesso al vostro server Asterisk solo da una specifica rete aziendale o da un insieme di indirizzi IP fidati.

---

#### Esercizio 4.2.2: Configurazione delle ACL

**Obiettivo**: Imparare a configurare le ACL in Asterisk.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[my_user]
type=friend
host=dynamic
; Permetti l'accesso solo dalla rete 192.168.1.0/24
permit=192.168.1.0/255.255.255.0
deny=0.0.0.0/0.0.0.0
```

**Note**:  
La configurazione delle ACL in Asterisk è relativamente semplice ma potente. Utilizzando le direttive `permit` e `deny` nel file `sip.conf`, è possibile specificare una serie di indirizzi IP o intervalli di rete che sono autorizzati o meno ad accedere al sistema. Queste impostazioni possono essere applicate sia a livello globale che per singoli utenti o peer.

Nell'esempio di codice, abbiamo configurato una ACL per l'utente `my_user`. Questa ACL permette l'accesso solo dalla rete `192.168.1.0/24` e nega l'accesso da qualsiasi altro indirizzo IP. Questo è un esempio di come le ACL possono essere utilizzate per limitare l'accesso a specifici servizi o risorse in base all'indirizzo IP dell'utente.

---

#### Esercizio 4.2.3: Test e Verifica delle ACL

**Obiettivo**: Verificare che le ACL siano configurate correttamente e funzionino come previsto.

**Codice di Esempio**:

```bash
# Verifica delle ACL con il comando 'sip show peers'
asterisk -rx "sip show peers"
```

**Note**:  
Dopo aver configurato le ACL, è fondamentale testarle per assicurarsi che funzionino come previsto. Il comando `sip show peers` può essere utilizzato per visualizzare le informazioni sui peer SIP e verificare che le ACL siano applicate correttamente.

Oltre ai test manuali, è anche una buona pratica implementare il monitoraggio e l'auditing per assicurarsi che le ACL siano sempre in vigore e funzionino come previsto. Strumenti come fail2ban possono essere utilizzati per rilevare e bloccare automaticamente tentativi di accesso non autorizzati, mentre i log di sistema possono fornire informazioni preziose per l'analisi forense in caso di violazione della sicurezza
---

### 5. Sicurezza a Livello di Applicazione

### 5.1 Sicurezza a Livello di Applicazione: Configurazione Sicura

La configurazione sicura di Asterisk è un aspetto cruciale per garantire che il sistema sia robusto contro vari tipi di attacchi. Questa sezione si concentrerà su come configurare Asterisk in modo sicuro, utilizzando diverse direttive e impostazioni nel file di configurazione.

---

#### Esercizio 5.1.1: Disabilitare Accessi come Ospite

**Obiettivo**: Imparare a disabilitare gli accessi come ospite per ridurre la superficie di attacco.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[general]
allowguest=no
```

**Note del Relatore**:  
Disabilitare gli accessi come ospite è uno dei primi passi per rendere il vostro sistema Asterisk più sicuro. Questa impostazione impedisce a utenti non autenticati di effettuare chiamate attraverso il vostro sistema, riducendo così il rischio di toll fraud (frode telefonica).

---

#### Esercizio 5.1.2: Autenticazione e Autorizzazione

**Obiettivo**: Configurare meccanismi di autenticazione e autorizzazione robusti.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[my_user]
type=friend
secret=StrongPassword123!
context=my_context
```

**Note del Relatore**:  
L'autenticazione e l'autorizzazione sono fondamentali per garantire che solo utenti autorizzati possano accedere al sistema. Utilizzare password forti e un contesto specifico per gli utenti può migliorare significativamente la sicurezza del sistema.

---

#### Esercizio 5.1.3: Utilizzo di TLS per la Segnalazione

**Obiettivo**: Configurare Asterisk per utilizzare TLS nella segnalazione SIP.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[general]
tlsenable=yes
tlsbindaddr=0.0.0.0:5061
tlscertfile=/etc/asterisk/keys/asterisk.pem
```

**Note del Relatore**:  
L'utilizzo di TLS (Transport Layer Security) per la segnalazione SIP è un altro passo importante per migliorare la sicurezza di Asterisk. TLS cifra la segnalazione SIP, rendendo più difficile per un attaccante intercettare o manipolare le chiamate.

---

#### Esercizio 5.1.4: Limitazione delle Richieste (Rate Limiting)

**Obiettivo**: Imparare a configurare il rate limiting per prevenire attacchi DoS.

**Codice di Esempio**:

```ini
; /etc/asterisk/sip.conf

[general]
call-limit=5
```

**Note**:  
Il rate limiting è un meccanismo efficace per prevenire attacchi DoS (Denial of Service). Limitando il numero di chiamate che un utente può effettuare in un determinato periodo di tempo, è possibile mitigare l'effetto di attacchi mirati a sovraccaricare il sistema.

---



#### Esercizio 5.2: Monitoraggio e Logging
- Configurare il logging e il monitoraggio delle attività.

#### Esercizio 5.3: Aggiornamenti e Patch
- Creare una procedura per mantenere il sistema aggiornato.

---

### 6. Monitoraggio e Logging

#### Esercizio 6.1: Call Monitoring
- Configurare il monitoraggio delle chiamate.

#### Esercizio 6.2: Analisi in Tempo Reale
- Utilizzare strumenti come Grafana e Prometheus per l'analisi in tempo reale.

---