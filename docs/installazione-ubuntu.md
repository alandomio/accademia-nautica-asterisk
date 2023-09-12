# Guida all'installazione di Ubuntu su Windows 11 tramite WSL 2

L'installazione di Ubuntu su Windows 11 tramite il Windows Subsystem for Linux (WSL) è un processo relativamente semplice che può essere diviso in diversi passaggi. Ecco come farlo:

## Requisiti

- Un computer con Windows 11 installato
- Accesso come amministratore
- Connessione a Internet per scaricare pacchetti

## Abilitare WSL

1. **Abilitare le funzionalità WSL e Virtual Machine Platform**:
Apri PowerShell come amministratore e esegui i seguenti comandi:

    ```powershell
    dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
    dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
    ```

2. **Riavvia il computer** per applicare le modifiche.

## Installazione del Kernel Linux

Scarica e installa il pacchetto del kernel Linux aggiornato per WSL 2 dal [sito web di Microsoft](https://aka.ms/wsl2kernel).

## Configurare WSL 2 come versione predefinita

1. **Imposta WSL 2 come versione predefinita**: apri PowerShell come amministratore e esegui il comando seguente:

    ```powershell
    wsl --set-default-version 2
    ```

## Installare Ubuntu

1. **Scarica Ubuntu dalla Microsoft Store**: vai al Microsoft Store, cerca "Ubuntu" e scegli la versione che preferisci (ad esempio, Ubuntu 20.04 LTS).

2. **Installa Ubuntu**: segui le istruzioni a schermo per completare l'installazione.

3. **Configura Ubuntu**: alla prima apertura, ti verrà chiesto di creare un utente e una password.

## Verifica

1. **Verifica la versione di WSL**: per assicurarti di utilizzare WSL 2, apri PowerShell e inserisci:

    ```powershell
    wsl --list --verbose
    ```

    Assicurati che accanto al nome di Ubuntu ci sia il numero "2".

## Configurazione di Ubuntu (opzionale)



1. **Aggiorna i pacchetti**:

    ```bash
    sudo apt update && sudo apt upgrade
    ```

2. **Installa Asterisk**:

    ```bash
    sudo apt install asterisk
    ```