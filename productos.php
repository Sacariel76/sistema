<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'db.php';

// Vulnerable search functionality
$search = $_GET['search'] ?? '';
$where = '';
if ($search) {
    $where = " WHERE nombre LIKE '%$search%' OR descripcion LIKE '%$search%' ";
}

// Vulnerable SQL query - directly concatenating user input
$sql = "SELECT * FROM producto $where ORDER BY id DESC";
$productos = [];

try {
    $stmt = $conn->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error al cargar los productos';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Sistema de Gestión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-container {
            margin: 1rem 0;
            display: flex;
            gap: 10px;
        }
        .search-container input[type="text"] {
            padding: 0.5rem;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-container button {
            padding: 0.5rem 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .welcome-msg {
            color: white;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <h1>Gestión de Productos</h1>
            <div>
                <span class="welcome-msg">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <div class="container">
        <form method="GET" action="" class="search-container">
            <input type="text" name="search" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (isset($error)): ?>
            <div style="color: #dc3545; margin: 1rem 0;"><?php echo $error; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                            <td><a href="ver_producto.php?id=<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></a></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td>$<?php echo number_format($producto['precio_compra'], 2); ?></td>
                            <td>$<?php echo number_format($producto['precio_venta'], 2); ?></td>
                            <td><?php echo $producto['cantidadDisponible']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No se encontraron productos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
