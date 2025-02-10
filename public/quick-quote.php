<?php
// Server-side email handling: This block runs when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_request') {
    // Retrieve POST data (be sure to validate and sanitize in a production site)
    $firstName        = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName         = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $customerEmail    = isset($_POST['email']) ? $_POST['email'] : '';  // Customer's email address
    $phone            = isset($_POST['phone']) ? $_POST['phone'] : '';
    $projectType      = isset($_POST['projectType']) ? $_POST['projectType'] : '';
    $area             = isset($_POST['area']) ? $_POST['area'] : '';
    $finish           = isset($_POST['finish']) ? $_POST['finish'] : '';
    $additional       = isset($_POST['additional']) ? $_POST['additional'] : '';
    $interiorFeatures = isset($_POST['interiorFeatures']) ? $_POST['interiorFeatures'] : '';
    $exteriorFeatures = isset($_POST['exteriorFeatures']) ? $_POST['exteriorFeatures'] : '';
    $flooring         = isset($_POST['flooring']) ? $_POST['flooring'] : '';
    $quote            = isset($_POST['quote']) ? $_POST['quote'] : '';
    $baseCost         = isset($_POST['baseCost']) ? $_POST['baseCost'] : '';
    $interiorCost     = isset($_POST['interiorCost']) ? $_POST['interiorCost'] : '';
    $exteriorCost     = isset($_POST['exteriorCost']) ? $_POST['exteriorCost'] : '';
    
    // Compose the message with all the details for the admin
    $adminSubject = "New Quick Quote Request from $firstName $lastName";
    $adminMessage  = "You have received a new quote request with the following details:\n\n";
    $adminMessage .= "First Name: $firstName\n";
    $adminMessage .= "Last Name: $lastName\n";
    $adminMessage .= "Email: $customerEmail\n";
    $adminMessage .= "Phone: $phone\n";
    $adminMessage .= "Project Type: $projectType\n";
    $adminMessage .= "Area: $area\n";
    $adminMessage .= "Finish: $finish\n";
    $adminMessage .= "Additional Options: $additional\n";
    $adminMessage .= "Interior Features: $interiorFeatures\n";
    $adminMessage .= "Exterior Features: $exteriorFeatures\n";
    $adminMessage .= "Flooring: $flooring\n";
    $adminMessage .= "Base Cost: $baseCost\n";
    $adminMessage .= "Interior Cost: $interiorCost\n";
    $adminMessage .= "Exterior Cost: $exteriorCost\n";
    $adminMessage .= "Total Quote: $quote\n";
    
    // The admin email address (update to your actual email)
    $adminEmail = "your@email.com";
    
    // To have the email appear as if it comes directly from the customer, set the From header to the customer's email.
    // (Some hosts might override this for security reasons.)
    $adminHeaders = "From: $customerEmail\r\n";
    $adminHeaders .= "Reply-To: $customerEmail\r\n";
    $adminHeaders .= "X-Mailer: PHP/" . phpversion();
    
    // Send email to admin
    $mailToAdmin = mail($adminEmail, $adminSubject, $adminMessage, $adminHeaders);
    
    // Now, send a confirmation email to the customer.
    $customerSubject = "Thank you for your Quick Quote Request";
    $customerMessage = "Dear $firstName,\n\n";
    $customerMessage .= "Thank you for reaching out. We have received your request and will get back to you shortly.\n\n";
    $customerMessage .= "Here is a copy of your request details:\n";
    $customerMessage .= "Project Type: $projectType\n";
    $customerMessage .= "Area: $area\n";
    $customerMessage .= "Total Quote: $quote\n\n";
    $customerMessage .= "Best regards,\nYour Company Name";
    
    // Set the confirmation email to come from your admin email address.
    $customerHeaders = "From: Your Company Name <no-reply@yourdomain.com>\r\n";
    $customerHeaders .= "Reply-To: no-reply@yourdomain.com\r\n";
    $customerHeaders .= "X-Mailer: PHP/" . phpversion();
    
    // Send email to customer
    $mailToCustomer = mail($customerEmail, $customerSubject, $customerMessage, $customerHeaders);
    
    // Return a JSON response to the front end.
    if ($mailToAdmin && $mailToCustomer) {
        echo json_encode(['success' => true, 'message' => 'Emails sent successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email sending failed.']);
    }
    exit;
}
?>
