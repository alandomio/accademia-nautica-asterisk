### Installazione di Grafana e Prometheus

```yaml
version: '3.7'

services:
  prometheus:
    image: prom/prometheus
    volumes:
      - ./prometheus:/etc/prometheus
      - prometheus_data:/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - '--web.enable-lifecycle'
    ports:
      - "9090:9090"
    restart: unless-stopped

  grafana:
    image: grafana/grafana
    volumes:
      - grafana_data:/var/lib/grafana
      - ./grafana:/etc/grafana
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=youradminpassword  # Cambia questa password
      - GF_USERS_ALLOW_SIGN_UP=false
    ports:
      - "3000:3000"
    restart: unless-stopped

volumes:
  prometheus_data:
  grafana_data:
```

### Spiegazione

- **Versione**: La versione di `docker-compose` utilizzata è `3.7`.
- **Servizi**: Definisce due servizi, `prometheus` e `grafana`.
- **Images**: Usa le immagini ufficiali di Prometheus (`prom/prometheus`) e Grafana (`grafana/grafana`) da Docker Hub.
- **Volumes**: 
  - Per Prometheus, monta una cartella locale `./prometheus` sulla cartella `/etc/prometheus` all'interno del container, dove puoi mettere il tuo file `prometheus.yml`. Monta anche un volume persistente `prometheus_data` per conservare i dati di Prometheus.
  - Per Grafana, monta una cartella locale `./grafana` sulla cartella `/etc/grafana` all'interno del container per la configurazione personalizzata e un volume persistente `grafana_data` per i dati di Grafana.
- **Ports**: Espone la porta 9090 per Prometheus e la porta 3000 per Grafana.
- **Restart Policy**: Entrambi i servizi sono impostati per riavviarsi automaticamente a meno che non siano fermati manualmente.

### Istruzioni per l'uso

1. **Crea le Cartelle di Configurazione**: Prima di avviare i servizi, assicurati di creare le cartelle `./prometheus` e `./grafana` nel tuo sistema host e di inserire al loro interno i file di configurazione necessari.

2. **Configura il file `prometheus.yml`**: Posiziona il tuo file `prometheus.yml` nella cartella `./prometheus`.

3. **Configura Grafana (opzionale)**: Puoi mettere i file di configurazione personalizzati per Grafana nella cartella `./grafana`.

4. **Avvia i servizi**: Esegui `docker-compose up -d` per avviare i container in modalità detached.

5. **Accesso**: Accedi a Grafana all'indirizzo `http://localhost:3000` e Prometheus all'indirizzo `http://localhost:9090`.

Ricorda di sostituire `youradminpassword` con una password sicura per l'accesso a Grafana.