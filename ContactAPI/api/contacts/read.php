<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and contact class
// In create.php, update.php, delete.php, read.php, search.php
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/ContactAPI/models/Contact.php';


// Initialize database and contact object
$database = new Database();
$db = $database->getConnection();

$contact = new Contact($db);

// Get contacts
$stmt = $contact->getAll();
$num = $stmt->rowCount();

// Check if any contacts found
if($num > 0) {
    // Contacts array
    $contacts_arr = array();
    $contacts_arr["records"] = array();
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        $contact_item = array(
            "id" => $id,
            "name" => $name,
            "number" => $number
        );
        
        array_push($contacts_arr["records"], $contact_item);
    }
    
    // Set response code - 200 OK
    http_response_code(200);
    
    // Show contacts data in JSON format
    echo json_encode($contacts_arr);
} else {
    // Set response code - 404 Not found
    http_response_code(404);
    
    // Tell the user no contacts found
    echo json_encode(array("message" => "No contacts found."));
}
?>
