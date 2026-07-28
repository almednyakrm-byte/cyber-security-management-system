<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is logged in
    if (!isset($userID)) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Get all analyses
    $stmt = $pdo->prepare('SELECT * FROM analyses');
    $stmt->execute();
    $analyses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return analyses
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($analyses);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($userID)) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Insert new analysis
    $stmt = $pdo->prepare('INSERT INTO analyses (title, description, user_id) VALUES (:title, :description, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $userID);
    $stmt->execute();

    // Return new analysis
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Analysis created successfully'));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is logged in and has admin role
    if (!isset($userID) || $userRole != 'admin') {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Update analysis
    $stmt = $pdo->prepare('UPDATE analyses SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated analysis
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Analysis updated successfully'));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is logged in and has admin role
    if (!isset($userID) || $userRole != 'admin') {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete analysis
    $stmt = $pdo->prepare('DELETE FROM analyses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted analysis
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Analysis deleted successfully'));
}

// Return error response for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}
?>