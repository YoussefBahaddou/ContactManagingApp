<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Welcome message
echo json_encode(array(
    "message" => "Welcome to Contact Management API",
    "endpoints" => array(
        "GET /api/contacts/read.php" => "Get all contacts",
        "GET /api/contacts/search.php?keyword=value" => "Search contacts",
        "POST /api/contacts/create.php" => "Create a new contact",
        "PUT /api/contacts/update.php" => "Update an existing contact",
        "DELETE /api/contacts/delete.php" => "Delete a contact"
    )
));
?>
