<?php
header('Content-Type: application/json');
$request_method = $_SERVER['REQUEST_METHOD'];

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            get_data($id);
        } else {
            get_data();
        }
        break;
    case 'POST':
        insert_data();
        break;
    case 'PUT':
        $id = $_GET['id'];
        update_data($id);
        break;
    case 'DELETE':
        $id = $_GET['id'];
        delete_data($id);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(array('message' => 'Method Not Allowed'));
        break;
}

function get_data($id = 0)
{
    global $conn;
    $query = "SELECT * FROM your_table_name";
    if ($id != 0) {
        $query .= " WHERE id=" . $id . " LIMIT 1";
    }
    $response = array();
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function insert_data()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $query = "INSERT INTO your_table_name (name, email) VALUES ('$name', '$email')";
    if ($conn->query($query) === TRUE) {
        echo json_encode(array('message' => 'Data inserted successfully.'));
    } else {
        echo json_encode(array('message' => 'Data insertion failed.'));
    }
}

function update_data($id)
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $query = "UPDATE your_table_name SET name='$name', email='$email' WHERE id=" . $id;
    if ($conn->query($query) === TRUE) {
        echo json_encode(array('message' => 'Data updated successfully.'));
    } else {
        echo json_encode(array('message' => 'Data update failed.'));
    }
}

function delete_data($id)
{
    global $conn;
    $query = "DELETE FROM your_table_name WHERE id=" . $id;
    if ($conn->query($query) === TRUE) {
        echo json_encode(array('message' => 'Data deleted successfully.'));
    } else {
        echo json_encode(array('message' => 'Data deletion failed.'));
    }
}

$conn->close();
?>
