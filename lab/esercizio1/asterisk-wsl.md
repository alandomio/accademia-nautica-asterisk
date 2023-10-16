
### Passo 1: Installa Asterisk nel tuo ambiente WSL

Prima di tutto, installa Asterisk nel tuo ambiente WSL se non lo hai già fatto.

```bash
sudo apt update
sudo apt install asterisk
```

### Passo 2: Configura Asterisk per Ascoltare su 0.0.0.0

Dovrai configurare Asterisk per ascoltare su tutte le interfacce di rete, non solo su localhost. Modifica i file di configurazione pertinenti (`sip.conf`, `pjsip.conf`, `rtp.conf`, ecc.) per assicurarti che Asterisk ascolti su `0.0.0.0` o sull'indirizzo IP specifico del tuo ambiente WSL.

### Passo 3: Trova l'indirizzo IP del tuo ambiente WSL

Esegui il comando `ip addr` e prendi nota dell'indirizzo IP associato all'interfaccia `eth0`.

### Passo 4: Configura iptables per Inoltrare il Traffico

Ora è il momento di impostare iptables per inoltrare il traffico dalle porte SIP e RTP utilizzate da Asterisk.

```bash
# Inoltra il traffico SIP
sudo iptables -t nat -A PREROUTING -p udp --dport 5060 -j DNAT --to-destination WSL_IP:5060
# Inoltra il traffico RTP
sudo iptables -t nat -A PREROUTING -p udp --dport 10000:20000 -j DNAT --to-destination WSL_IP
```

- `5060` è la porta standard SIP. Potrebbe essere diversa a seconda della tua configurazione.
- `10000:20000` è un intervallo di porte spesso utilizzato per RTP. Anche questo potrebbe variare.

### Passo 5: Verifica e Testa la Configurazione

Una volta configurato tutto, avvia o riavvia il servizio Asterisk e verifica che sia raggiungibile dalla rete esterna. Potresti dover anche configurare il firewall di Windows per permettere il traffico in ingresso sulle porte che hai inoltrato.

```bash
# Riavvia Asterisk
sudo systemctl restart asterisk
```

### Passo 6: Rendi Persistenti le Regole iptables

Le regole iptables non sopravviveranno a un riavvio del sistema. Potrai rendere le regole persistenti utilizzando un pacchetto come `iptables-persistent` o scrivendo uno script di avvio.

```bash
sudo apt install iptables-persistent
```

Durante l'installazione, ti verrà chiesto se vuoi salvare le regole attuali; accetta per rendere persistenti le tue modifiche.

### Nota

WSL potrebbe non essere l'ambiente ideale per eseguire un sistema di telefonia come Asterisk a causa di possibili problemi con il sistema di file, la rete e altre peculiarità dell'interazione tra WSL e Windows. Tuttavia, per scopi di test e sviluppo, potrebbe essere sufficiente.

### Slide del corso
https://docs.google.com/presentation/d/1V2PB-VTvRGS-jZgBByzcfbjJ-ifzyQZrjCaNth-m5SE/edit?usp=sharing