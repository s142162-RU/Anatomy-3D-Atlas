<?php
header('Content-Type: application/json');
$system = $_GET['system'] ?? '';
$filename = "notes_{$system}.json";
$notes = [];
if (file_exists($filename)) {
    $notes = json_decode(file_get_contents($filename), true);
}
$result = [];
foreach ($notes as $organ_id => $note_text) {
    $result[] = ['organ_id' => $organ_id, 'note_text' => $note_text];
}
echo json_encode(['success' => true, 'notes' => $result]);
?>
