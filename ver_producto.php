<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'db.php';

// Obtener el ID del producto de la URL - ¡Vulnerable a inyección SQL!
$id = $_GET['id'] ?? 0;

// Consulta vulnerable a inyección SQL
$sql = "SELECT * FROM producto WHERE id = $id";

try {
    $stmt = $conn->query($sql);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto) {
        die("Producto no encontrado");
    }
} catch (Exception $e) {
    die("Error al cargar el producto");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto - Sistema de Gestión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .product-detail {
            margin-top: 20px;
        }
        .product-detail p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .price {
            font-size: 1.5em;
            color: #28a745;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle del Producto</h1>
        
        <?php if ($producto): ?>
            <div class="product-detail">
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <p><strong>Código:</strong> <?php echo htmlspecialchars($producto['codigo']); ?></p>
                <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                <p><strong>Precio de Compra:</strong> <span class="price">$<?php echo number_format($producto['precio_compra'], 2); ?></span></p>
                <p><strong>Precio de Venta:</strong> <span class="price">$<?php echo number_format($producto['precio_venta'], 2); ?></span></p>
                <p><strong>Stock Disponible:</strong> <?php echo $producto['cantidadDisponible']; ?> unidades</p>
            </div>
        <?php else: ?>
            <p>No se encontró el producto solicitado.</p>
        <?php endif; ?>
        
        <a href="productos.php" class="back-link">← Volver al listado de productos</a>
    </div>
</body>
</html>
