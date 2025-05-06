<?php
require './db.php';

$name = $_POST['name'];
$sex = $_POST['sex'];
$type = $_POST['type'];
$age = $_POST['age'];
$breed = $_POST['breed'];
$location = $_POST['location'];
$description = $_POST['description'];
$editingId = $_POST['editingId'] ?? null;

$imageName = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $imageName);
}

if ($editingId) {
    // Update
    $query = "UPDATE pets SET name=?, sex=?, type=?, age=?, breed=?, location=?, description=?";
    $params = [$name, $sex, $type, $age, $breed, $location, $description];

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
    // Insert (CORRECTED)
    $stmt = $conn->prepare("INSERT INTO pets (name, sex, type, age, breed, location, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $sex, $type, $age, $breed, $location, $description, $imageName);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

$conn->close();
?>
