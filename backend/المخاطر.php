<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input is valid
if (!$input) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input'));
    exit;
}

// Define routes
$routes = array(
    '/get' => 'get',
    '/create' => 'create',
    '/update/:id' => 'update',
    '/delete/:id' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . $pattern . '$/', $route)) {
        break;
    }
}

// Call method
if ($method == 'get') {
    get();
} elseif ($method == 'create') {
    create();
} elseif ($method == 'update') {
    update();
} elseif ($method == 'delete') {
    delete();
}

// Helper function to get data from database
function getData($query, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper function to insert data into database
function insertData($query, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

// Helper function to update data in database
function updateData($query, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}

// Helper function to delete data from database
function deleteData($query, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}

// GET method
function get() {
    global $pdo;
    $query = "SELECT * FROM المخاطر";
    $data = getData($query);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// CREATE method
function create() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = $pdo->quote($input['name']);
    $description = $pdo->quote($input['description']);
    
    // SQL query
    $query = "INSERT INTO المخاطر (name, description) VALUES ($name, $description)";
    
    // Insert data
    insertData($query);
    
    // Return response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data created successfully'));
}

// UPDATE method
function update() {
    global $pdo;
    // Get id from route
    $id = (int) $_SERVER['REQUEST_URI'];
    $id = str_replace('/update/', '', $id);
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = $pdo->quote($input['name']);
    $description = $pdo->quote($input['description']);
    
    // Check if user is admin
    if ($user['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $query = "UPDATE المخاطر SET name = $name, description = $description WHERE id = :id";
    $params = array(':id' => $id);
    
    // Update data
    updateData($query, $params);
    
    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data updated successfully'));
}

// DELETE method
function delete() {
    global $pdo;
    // Get id from route
    $id = (int) $_SERVER['REQUEST_URI'];
    $id = str_replace('/delete/', '', $id);
    
    // Check if user is admin
    if ($user['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $query = "DELETE FROM المخاطر WHERE id = :id";
    $params = array(':id' => $id);
    
    // Delete data
    deleteData($query, $params);
    
    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data deleted successfully'));
}
?>