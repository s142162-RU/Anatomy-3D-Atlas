<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$data = json_decode(file_get_contents('php://input'), true);
$system = $data['system'] ?? '';
$organ_id = $data['organ_id'] ?? '';
$note = $data['note'] ?? '';


$filename = "notes_{$system}.json";
$allNotes = [];
if (file_exists($filename)) {
    $allNotes = json_decode(file_get_contents($filename), true);
}
$allNotes[$organ_id] = $note;
file_put_contents($filename, json_encode($allNotes));

echo json_encode(['success' => true]);
?>
