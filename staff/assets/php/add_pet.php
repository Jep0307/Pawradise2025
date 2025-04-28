<?php
require 'db.php';

$name = $_POST['name'];
$sex = $_POST['sex'];
$type = $_POST['type'];
$breed = $_POST['breed'];
$location = $_POST['location'];
$description = $_POST['description'];
$editingId = $_POST['editingId'] ?? null;

$imageName = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
}

// If editingId is set, update the record
if ($editingId) {
    $query = "UPDATE pets SET name=?, sex=?, type=?, breed=?, location=?, description=?";
    $params = [$name, $sex, $type, $breed, $location, $description];

    if ($imageName) {
        $query .= ", image=?";
        $params[] = $imageName;
    }

    $query .= " WHERE id=?";
    $params[] = $editingId;

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    // Otherwise, insert a new pet
    $stmt = $conn->prepare("INSERT INTO pets (name, sex, type, breed, location, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $sex, $type, $breed, $location, $description, $imageName);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

$conn->close();
?>
