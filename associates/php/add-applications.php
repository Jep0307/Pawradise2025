<?php
require '../../staff/php/db.php';

// Validate required fields
$requiredFields = ['fullname', 'email', 'contactno', 'birthdate', 'occupation', 'address', 'social', 'rent', 'residenceType', 'petType', 'shelterVisit', 'zoomdate', 'zoomtime'];
foreach ($requiredFields as $field) {
  if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
    die("Missing required field: $field");
  }
}

// Sanitize inputs
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

$stmt->close();
$conn->close();
?>
