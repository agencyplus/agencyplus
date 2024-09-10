<?php
// Aktivera felrapportering
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Skapa en tom array för att hålla frågorna
$data = [];

// Kontrollera om formuläret har skickats
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $youtube_link = $_POST['youtube_link'];

    // Hantera bilduppladdning
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["background_image"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kontrollera om filen är en bild
    $check = getimagesize($_FILES["background_image"]["tmp_name"]);
    if ($check !== false) {
        $upload_ok = 1;
    } else {
        echo "Filen är inte en bild.<br>";
        $upload_ok = 0;
    }

    // Kontrollera om filen redan existerar
    if (file_exists($target_file)) {
        echo "Filen existerar redan.<br>";
        $upload_ok = 0;
    }

    // Begränsa till vissa filformat
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
        echo "Endast JPG, JPEG, PNG filer är tillåtna.<br>";
        $upload_ok = 0;
    }

    // Om alla kontroller passeras, ladda upp filen
    if ($upload_ok == 1) {
        if (move_uploaded_file($_FILES["background_image"]["tmp_name"], $target_file)) {
            echo "Filen ". htmlspecialchars(basename($_FILES["background_image"]["name"])) ." har laddats upp.<br>";

            // Lägg till frågan, YouTube-länken och bakgrundsbilden i arrayen
            $new_entry = [
                "question" => $question,
                "youtube_link" => $youtube_link,
                "background_image" => $target_file
            ];

            // Ladda befintlig JSON-fil om den finns
            if (file_exists('data.json')) {
                $json_content = file_get_contents('data.json');
                $data = json_decode($json_content, true);

                // Kontrollera om avkodningen lyckades
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo "Fel vid avkodning av JSON: " . json_last_error_msg() . "<br>";
                    $data = []; // Återställ till en tom array om JSON är skadad
                }
            }

            // Lägg till den nya posten till dataarrayen
            $data[] = $new_entry;

            // Spara data i JSON-filen utan att escapa snedstreck och unicode-tecken
if (file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))) {
    echo "Frågan har sparats!";
} else {
    echo "Kunde inte skriva till JSON-filen.";
}


        } else {
            echo "Det uppstod ett problem med uppladdningen.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lägg till Fråga</title>
</head>
<body>
    <h1>Lägg till en ny fråga</h1>
    <form action="form.php" method="post" enctype="multipart/form-data">
        <label for="question">Fråga:</label><br>
        <textarea name="question" id="question" required></textarea><br><br>

        <label for="youtube_link">YouTube-länk:</label><br>
        <input type="text" name="youtube_link" id="youtube_link" required><br><br>

        <label for="background_image">Bakgrundsbild:</label><br>
        <input type="file" name="background_image" id="background_image" required><br><br>

        <input type="submit" value="Lägg till fråga">
    </form>
</body>
</html>
