<?php
session_start();
require 'classes/FavorisManager.php';

$favorisManager = new FavorisManager();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit;
}

$user_id = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);
$video_id = $data['video_id'] ?? null;

$response = $favorisManager->toggleFavoris($user_id, $video_id);

echo json_encode($response);

?>