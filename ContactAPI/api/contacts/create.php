<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Debug received data
$raw_data = file_get_contents("php://input");
error_log("Raw data received: " . $raw_data);

// Include database and contact class
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/models/Contact.php';

// Initialize database and contact object
$database = new Database();
$db = $database->getConnection();

// Check if database connection is successful
if (!$db) {
    http_response_code(500);
    echo json_encode(array("message" => "Database connection failed"));
    exit;
}

// Get posted data
$data = json_decode($raw_data);

// Check for JSON decode errors
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Invalid JSON: " . json_last_error_msg(),
        "received" => $raw_data
    ));
    exit;
}

// Alternative approach if JSON is still empty
if (empty($data)) {
    $data = new stdClass();
    if (isset($_POST['name'])) $data->name = $_POST['name'];
    if (isset($_POST['number'])) $data->number = $_POST['number'];
}

// Make sure data is not empty
if(!empty($data->name) && !empty($data->number)) {
    $contact = new Contact($db);
    
    // Set contact property values
    $contact->name = $data->name;
    $contact->number = $data->number;
    
    // Create the contact
    if($contact->create()) {
        // Set response code - 201 created
        http_response_code(201);
        
        // Tell the user
        echo json_encode(array(
            "message" => "Contact was created.",
            "id" => $contact->id,
            "name" => $contact->name,
            "number" => $contact->number
        ));
    } else {
        // If contact already exists
        // Set response code - 409 conflict
        http_response_code(409);
        
        // Tell the user
        echo json_encode(array("message" => "Contact already exists."));
    }
} else {
    // Set response code - 400 bad request
    http_response_code(400);
    
    // Tell the user
    echo json_encode(array(
        "message" => "Unable to create contact. Data is incomplete.",
        "received_data" => $data,
        "raw_input" => $raw_data
    ));
}
?>
