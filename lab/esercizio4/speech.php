#!/bin/php
<?php


require 'phpagi.php';

$agi = new AGI();

// Ottieni la stringa di testo dal parametro AGI
$text = $argv[1] ?? "Prova"; // Se il parametro non è fornito, usa una stringa predefinita

$hash = md5($text);

// Percorso del file audio convertito in formato alaw
$alawFilePath = "/usr/local/share/asterisk/sounds/{$hash}.gsm";

if(!file_exists($alawFilePath)) {

    // Testo da convertire in audio
    // $text = "Questo è un sistema di risposta automatica del corso di Cybersecurity";

    // URL del servizio online
    $serviceUrl = "https://voice.smartforge.eu/?text=" . urlencode($text);

    // Percorso del file MP3 scaricato
    $mp3FilePath = "/tmp/file.mp3";

    // Scarica il file MP3 dal servizio online
    file_put_contents($mp3FilePath, file_get_contents($serviceUrl));



    // Esegui la conversione da MP3 a alaw usando SOX
    $command = "sox $mp3FilePath -r 8000 -c 1 -e signed-integer -b 16 -t raw - | sox -t raw -r 8000 -c 1 -e signed-integer -b 16 -  $alawFilePath";
    $agi->exec('NoOp', $command);
    exec($command);

}

// Riproduci il file alaw
$agi->exec('Playback', "custom/{$hash}");

// Rimuovi i file temporanei
// unlink($mp3FilePath);
// unlink($alawFilePath);

exit;


?>
