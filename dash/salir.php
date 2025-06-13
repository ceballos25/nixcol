
<!DOCTYPE html>
<html>
<head>
    <title>Cerrar Sesión</title>
</head>
<body>
    <script>
        // Eliminar las claves del vendedor de localStorage
        localStorage.removeItem('nombre');
        localStorage.removeItem('usuario');

        // Redirigir al usuario a la página de inicio de sesión
        window.location.href = 'index.php';
    </script>
</body>
</html>