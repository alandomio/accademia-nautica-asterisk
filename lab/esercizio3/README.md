

### Prerequisiti:
1. **Docker Desktop**: Assicurati di avere Docker Desktop installato sul tuo sistema Windows. Se non lo hai già fatto, puoi scaricarlo da [qui](https://www.docker.com/products/docker-desktop).
2. **WSL2**: Assicurati di avere WSL2 configurato e abilitato. Se non lo hai già fatto, puoi seguire questa [guida ufficiale di Microsoft](https://docs.microsoft.com/it-it/windows/wsl/install).

### Passi per l'installazione:

1. **Avvia Docker Desktop e WSL2**:
   Apri Docker Desktop e assicurati che sia in esecuzione. Avvia anche la tua distribuzione WSL2 preferita.

2. **Scarica l'immagine FreePBX da Docker Hub**:
   Dal terminale WSL2, esegui il seguente comando:
   ```bash
   docker pull tiredofit/freepbx
   ```

3. **Crea una rete Docker personalizzata**:
   Questo è utile per la comunicazione tra i container.
   ```bash
   docker network create freepbx-net
   ```

4. **Esegui il container FreePBX**:
   ```bash
   docker run -d \
     --name freepbx \
     --network freepbx-net \
     -e DB_EMBEDDED=TRUE \
     -p 80:80 \
     -p 5060:5060/udp \
     -p 5160:5160/udp \
     -p 18000-18100:18000-18100/udp \
     tiredofit/freepbx
   ```

   Questo comando eseguirà il container FreePBX e lo esporrà sulla porta 80. Le porte `5060` e `5160` sono utilizzate per SIP, mentre il range `18000-18100` è utilizzato per RTP.

5. **Accesso a FreePBX**:
   Ora puoi accedere all'interfaccia web di FreePBX dal tuo browser all'indirizzo `http://localhost`.

6. **Configurazione iniziale di FreePBX**:
   La prima volta che accedi a FreePBX, ti verrà chiesto di completare la configurazione iniziale. Segui i passaggi per impostare l'utente amministratore e altre configurazioni di base.

### Note:
- L'immagine Docker `tiredofit/freepbx` è una delle più popolari per FreePBX, ma ci sono molte altre immagini disponibili. Assicurati di leggere la documentazione associata all'immagine che scegli.
- Se hai già servizi in esecuzione su queste porte, dovrai mappare le porte del container su porte diverse.

