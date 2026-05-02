<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$system = $data['system'] ?? '';
$organ_id = $data['organ_id'] ?? '';

$filename = "notes_{$system}.json";
if (file_exists($filename)) {
    $allNotes = json_decode(file_get_contents($filename), true);
    unset($allNotes[$organ_id]);
    file_put_contents($filename, json_encode($allNotes));
}
echo json_encode(['success' => true]);
?>
