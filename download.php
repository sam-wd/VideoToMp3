<?php

// Disable error reporting
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Retrieve the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['url'])) {
    $videoURL = escapeshellarg($data['url']);
    $output = [];
    $return_var = 0;

    // Generate a unique filename for the audio file
    $audioFile = 'C:/Downloads/' . uniqid() . '.mp3';

    // Run yt-dlp to download the audio
    $command = "yt-dlp.exe --extract-audio --audio-format mp3 -o 'downloads/%(title)s.%(ext)s' $videoURL 2>&1";
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        // Success - find the downloaded file
        $files = glob('downloads/*.mp3');
        $latestFile = end($files);

        echo json_encode(['success' => true, 'file' => $latestFile]);
    } else {
        // Error
        echo json_encode(['success' => false, 'message' => implode("\n", $output)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
