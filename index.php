<?php
include "config.php";
session_start();


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch items
$result = $conn->query("SELECT * FROM posts");

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom styles for table */
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .table-custom {
            border-collapse: separate;
            border-spacing: 0 15px;
            width: 100%;
        }
        .table-custom thead th {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 15px;
        }
        .table-custom tbody tr {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .table-custom tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .table-custom td, .table-custom th {
            padding: 12px 15px;
        }
        .table-custom td {
            border-top: 1px solid #dee2e6;
        }
        .btn-custom {
            background-color: #1e90ff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-custom-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }
        .btn-custom-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">CRUD Application</h2>
            <button class="btn btn-custom" onclick="window.location.href='logout.php'" title="Logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </div>

        <!-- Table Layout for Posts -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Posts</span>
                    <a href="add_post.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Create Post
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-custom btn-sm" 
                                            onclick="editDetails('<?php echo htmlspecialchars($row['id']); ?>', '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['content']); ?>', '<?php echo htmlspecialchars($row['created_at']); ?>')" 
                                            data-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <form action="" method="post" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <button type="submit" name="delete" class="btn btn-custom-danger btn-sm" data-toggle="tooltip" title="Delete">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>

