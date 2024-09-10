<?php
// Filnamn för loggfilen
$file = 'tracker.txt';

// Initialisera värden
$views = 0;
$shares = 0;

// Läs in filen om den redan existerar
if (file_exists($file)) {
    $data = file_get_contents($file);
    // Dela upp filens innehåll i rader och extrahera värden
    $lines = explode("\n", $data);
    foreach ($lines as $line) {
        $parts = explode(": ", $line);
        if (count($parts) == 2) {
            if ($parts[0] === 'views') {
                $views = (int)$parts[1];
            } elseif ($parts[0] === 'shares') {
                $shares = (int)$parts[1];
            }
        }
    }
}

// Kontrollera vilken åtgärd som skickas via GET-parametern
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'view') {
        // Öka antalet sidvisningar
        $views++;
    } elseif ($action === 'share') {
        // Öka antalet delningar (tipsa en kollega)
        $shares++;
    }

    // Spara tillbaka till filen
    $data = "views: $views\nshares: $shares\n";
    file_put_contents($file, $data);
}
