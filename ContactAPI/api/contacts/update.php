<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and contact class
// In create.php, update.php, delete.php, read.php, search.php
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/models/Contact.php';


// Initialize database and contact object
$database = new Database();
$db = $database->getConnection();

$contact = new Contact($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Make sure data is not empty and ID exists
if(!empty($data->id) && !empty($data->name) && !empty($data->number)) {
    // Set contact property values
    $contact->id = $data->id;
    $contact->name = $data->name;
    $contact->number = $data->number;
    
    // Update the contact
    if($contact->update()) {
        // Set response code - 200 ok
        http_response_code(200);
        
        // Tell the user
        echo json_encode(array("message" => "Contact was updated."));
    } else {
        // Set response code - 503 service unavailable
        http_response_code(503);
        
        // Tell the user
        echo json_encode(array("message" => "Unable to update contact."));
    }
} else {
    // Set response code - 400 bad request
    http_response_code(400);
    
    // Tell the user
    echo json_encode(array("message" => "Unable to update contact. Data is incomplete."));
}
?>
