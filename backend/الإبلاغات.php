<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM الإبلاغات');
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($reports);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate input
    $requiredFields = array('title', 'description', 'reporterID');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['title'] = htmlspecialchars($input['title']);
    $input['description'] = htmlspecialchars($input['description']);

    // Insert report
    $stmt = $pdo->prepare('INSERT INTO الإبلاغات (title, description, reporterID) VALUES (:title, :description, :reporterID)');
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':reporterID', $input['reporterID']);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report created successfully'));
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate input
    $requiredFields = array('id', 'title', 'description');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['title'] = htmlspecialchars($input['title']);
    $input['description'] = htmlspecialchars($input['description']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update report
    $stmt = $pdo->prepare('UPDATE الإبلاغات SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report updated successfully'));
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate input
    $requiredFields = array('id');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete report
    $stmt = $pdo->prepare('DELETE FROM الإبلاغات WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report deleted successfully'));
}