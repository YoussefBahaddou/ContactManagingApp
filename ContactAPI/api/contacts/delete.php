<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
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

// Make sure ID is not empty
if(!empty($data->id)) {
    // Set contact ID to be deleted
    $contact->id = $data->id;
    
    // Delete the contact
    if($contact->delete()) {
        // Set response code - 200 ok
        http_response_code(200);
        
        // Tell the user
        echo json_encode(array("message" => "Contact was deleted."));
    } else {
        // Set response code - 503 service unavailable
        http_response_code(503);
        
        // Tell the user
        echo json_encode(array("message" => "Unable to delete contact."));
    }
} else {
    // Set response code - 400 bad request
    http_response_code(400);
    
    // Tell the user
    echo json_encode(array("message" => "Unable to delete contact. ID is required."));
}
?>
