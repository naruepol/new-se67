<?php
// Database Connection via PDO
$servername = "lemp_mariadb";
$username = "admin";
$password = "1234";
$dbname = "titanic";

try {
    // กำหนด DSN และเปิดการเชื่อมต่อ PDO
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ให้โยนข้อผิดพลาดเป็น Exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // กำหนดให้ดึงข้อมูลเป็นแบบ associative array เสมอ
        PDO::ATTR_EMULATE_PREPARES => false,                  // ปิดการจำลอง Prepared Statements เพื่อความปลอดภัย
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Pagination Setup
$items_per_page = 20; // จำนวนรายการต่อหน้า
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($current_page < 1)
    $current_page = 1; // ป้องกันการใส่เลขหน้าติดลบ

$offset = ($current_page - 1) * $items_per_page; // คำนวณ OFFSET

// Fetch Total Recordsด้วย PDO
$total_sql = "SELECT COUNT(*) AS total FROM titanic";
$total_stmt = $pdo->query($total_sql);
$total_row = $total_stmt->fetch();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $items_per_page); // จำนวนหน้าทั้งหมด

// Fetch Paginated Data ด้วย Prepared Statement ของ PDOเพื่อความปลอดภัย
$sql = "SELECT * FROM titanic LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

// ผูกค่าตัวแปรแบบระบุประเภทข้อมูล (Integer) ป้องกัน SQL Injection
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// ดึงข้อมูลทั้งหมดเก็บไว้ในตัวแปร array
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanic Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Titanic Passenger Data</h2>
        <?php if (count($rows) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Index</th>
                        <th>Passenger ID</th>
                        <th>Survived</th>
                        <th>Pclass</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>SibSp</th>
                        <th>Parch</th>
                        <th>Ticket</th>
                        <th>Fare</th>
                        <th>Cabin</th>
                        <th>Embarked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['index']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['PassengerId']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Survived']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Pclass']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Sex']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Age']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['SibSp']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Parch']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Ticket']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Fare']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Cabin']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Embarked']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                    </li>
                    <!-- Page Number Links -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <!-- Next Page Link -->
                    <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php else: ?>
            <p class="text-center">No records found in the Titanic table.</p>
        <?php endif; ?>
        <?php
        // ปิดการเชื่อมต่อ PDO (ทำได้โดยการสั่งให้ตัวแปรเป็น null)
        $pdo = null;
        ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>