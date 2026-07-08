<?php
require_once '../config/db.php';

echo "<h2>Login Debug</h2>";

echo "<h3>1. POST Data:</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    echo "<h3>2. Processing:</h3>";
    echo "Email: '" . htmlspecialchars($email) . "'<br>";
    echo "Password length: " . strlen($password) . "<br>";
    echo "Role: '" . htmlspecialchars($role) . "'<br>";
    echo "Table: " . (($role === 'admin') ? 'admin' : 'users') . "<br>";

    $table = ($role === 'admin') ? 'admin' : 'users';
    
    echo "<h3>3. All records in '$table' table:</h3>";
    $all = $conn->query("SELECT * FROM $table");
    if ($all) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Password (first 30)</th><th>Password Len</th></tr>";
        while ($row = $all->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name'] ?? '') . "</td>";
            echo "<td>'" . htmlspecialchars($row['email']) . "'</td>";
            echo "<td>" . htmlspecialchars(substr($row['password'], 0, 30)) . "</td>";
            echo "<td>" . strlen($row['password']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }

    echo "<h3>4. Login Query:</h3>";
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "Rows found: " . $result->num_rows . "<br>";

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        echo "User found: " . htmlspecialchars($user['name']) . "<br>";
        echo "Stored password length: " . strlen($user['password']) . "<br>";
        echo "Stored password starts with: " . htmlspecialchars(substr($user['password'], 0, 20)) . "<br>";
        
        $verify = password_verify($password, $user['password']);
        echo "password_verify result: " . ($verify ? 'TRUE ✅' : 'FALSE ❌') . "<br>";
        
        if ($verify) {
            echo "<h3 style='color:green'>LOGIN WOULD SUCCEED ✅</h3>";
        } else {
            echo "<h3 style='color:red'>password_verify FAILED ❌</h3>";
            
            // Check if the stored value looks like a valid hash
            if (substr($user['password'], 0, 4) === '$2y$') {
                echo "Hash looks like a valid bcrypt hash.<br>";
            } else {
                echo "Hash does NOT look like bcrypt (should start with \$2y\$). It starts with: '" . htmlspecialchars(substr($user['password'], 0, 10)) . "'<br>";
            }
        }
    } else {
        echo "<h3 style='color:red'>No user found with this email ❌</h3>";
        echo "Try these available emails:<br>";
        $all = $conn->query("SELECT email FROM $table");
        while ($row = $all->fetch_assoc()) {
            echo " - '" . htmlspecialchars($row['email']) . "'<br>";
        }
    }
    $stmt->close();
} else {
    echo "Submit the form below to test:<br><br>";
    echo '<form method="POST">
        Email: <input type="text" name="email" value="adminaccessedu@gmail.com"><br>
        Password: <input type="text" name="password" value="access123"><br>
        Role: <select name="role">
            <option value="user">Student</option>
            <option value="admin" selected>Admin</option>
        </select><br>
        <button type="submit">Test Login</button>
    </form>';
}
?>
