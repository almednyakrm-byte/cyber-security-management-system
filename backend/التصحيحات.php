<?php
require_once 'db.php';

// Get input data from JSON or POST request
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM تصحيفات WHERE deleted_at IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $requiredFields = array('title', 'description');
    foreach ($requiredFields as $field) {
        if (!isset($inputData[$field]) || empty($inputData[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    }

    // Sanitize input data
    $inputData['title'] = htmlspecialchars($inputData['title']);
    $inputData['description'] = htmlspecialchars($inputData['description']);

    // Insert data into database
    $sql = "INSERT INTO تصحيفات (title, description, created_at, updated_at) VALUES (:title, :description, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $inputData['title']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data inserted successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input data
    $requiredFields = array('id', 'title', 'description');
    foreach ($requiredFields as $field) {
        if (!isset($inputData[$field]) || empty($inputData[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    }

    // Sanitize input data
    $inputData['title'] = htmlspecialchars($inputData['title']);
    $inputData['description'] = htmlspecialchars($inputData['description']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update data in database
    $sql = "UPDATE تصحيفات SET title = :title, description = :description, updated_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':title', $inputData['title']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input data
    if (!isset($inputData['id']) || empty($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete data from database
    $sql = "UPDATE تصحيفات SET deleted_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data deleted successfully'));
    exit;
}