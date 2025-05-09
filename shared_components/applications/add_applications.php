<?php
require '../../config/db.php'; // Include the database connection

// Check if we're updating or inserting
$editingId = $_POST['editingId'] ?? null;

if (!$editingId) {
  // Validate required fields only when inserting new data
  $requiredFields = ['fullname', 'email', 'contactno', 'birthdate', 'occupation', 'address', 'social', 'rent', 'residenceType', 'petType', 'shelterVisit', 'zoomdate', 'zoomtime'];
  foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
      die("Missing required field: $field");
    }
  }

  // Sanitize inputs for insertion
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $contactno = $_POST['contactno'];
  $birthdate = $_POST['birthdate'];
  $occupation = $_POST['occupation'];
  $address = $_POST['address'];
  $social = $_POST['social'];
  $zoomdate = $_POST['zoomdate'];
  $zoomtime = $_POST['zoomtime'];
  
  $rent = $_POST['rent'];
  $residence_type = $_POST['residenceType'];
  $pet_type = $_POST['petType'];
  $shelter_visit = $_POST['shelterVisit'];

  // File upload helper
  function saveFile($fileField, $folder) {
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
      return null;
    }
    $filename = basename($_FILES[$fileField]['name']);
    $targetPath = "../../staff/assets/uploads/" . uniqid() . "_" . $filename;
    move_uploaded_file($_FILES[$fileField]['tmp_name'], $targetPath);
    return $targetPath;
  }

  // Handle file uploads
  $validid_path = saveFile('validid', 'ids');
  $housepic_front_path = saveFile('housepic-front', 'home');
  $housepic_living_path = saveFile('housepic-living', 'home');
  $housepic_yard_path = saveFile('housepic-yard', 'home');

  // Insert into DB
  $sql = "INSERT INTO applications (
    fullname, email, contactno, birthdate, occupation, address, social,
    rent, residence_type, pet_type,
    validid_path, housepic_front_path, housepic_living_path, housepic_yard_path,
    zoomdate, zoomtime, shelter_visit
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "sssssssssssssssss",
    $fullname, $email, $contactno, $birthdate, $occupation, $address, $social,
    $rent, $residence_type, $pet_type,
    $validid_path, $housepic_front_path, $housepic_living_path, $housepic_yard_path,
    $zoomdate, $zoomtime, $shelter_visit
  );

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error: " . $stmt->error;
  }
} else {
  // Handle update for existing application
  $status = $_POST['status'];

  // Update the status for the specific application
  $sql = "UPDATE applications SET status = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $status, $editingId);

  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application status updated successfully.']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Failed to update application status.']);
  }
}

$stmt->close();
$conn->close();
?>
