
Per implementare le nuove opzioni sperimentali di WSL, è necessario creare un file `.wslconfig` nella directory home di Windows (es: `C:\Users\<yourusername>\.wslconfig`) e inserire una sezione `[experimental]` con ciascuna impostazione sotto di essa. Ad esempio, per la funzione `autoMemoryReclaim`, il file dovrebbe contenere:

```plaintext
[experimental]
autoMemoryReclaim=gradual
```

Le nuove funzioni includono:

- `autoMemoryReclaim`: Riduce automaticamente la memoria della VM WSL reclamando la memoria cache.
- `Sparse VHD`: Riduce automaticamente il disco virtuale (VHD) di WSL durante l'utilizzo.
- `Mirrored mode networking`: Migliora la compatibilità della rete in WSL.
- `dnsTunneling`, `firewall`, e `autoProxy`: Migliorano ulteriormente la compatibilità della rete e l'integrazione del firewall tra Windows e WSL【6†source】.

Per maggiori info:
https://devblogs.microsoft.com/commandline/windows-subsystem-for-linux-september-2023-update/