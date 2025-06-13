<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-top: 50px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .alert-danger strong {
            font-size: 1.2rem;
        }

        .error-message {
            font-size: 1.3rem;
            color: #721c24;
            text-align: center;
            margin-bottom: 20px;
        }

        .whatsapp-btn {
            background-color: #25D366;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }

        .whatsapp-btn:hover {
            background-color: #128C7E;
        }

        .whatsapp-btn img {
            width: 26px;
            height: 26px;
            margin-right: 12px;
        }

        .footer-info {
            margin-top: 30px;
            font-size: 1rem;
            text-align: center;
            color: #777;
        }

        .footer-info a {
            color: #007bff;
            text-decoration: none;
        }

        .footer-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Mensaje de error -->
        <div class="alert alert-danger">
            <strong>Error:</strong>
            <p class="error-message"><?php echo isset($error) ? $error : 'Ha ocurrido un problema inesperado.'; ?></p>
            <p>Si el problema persiste, por favor póngase en contacto con nuestro equipo de ventas.</p>
        </div>

        <!-- Mensaje de contacto con WhatsApp -->
        <div class="text-center">
            <a href="https://api.whatsapp.com/send?phone=573128956692&text=Hola%2C%20estaba%20comprando%20mis%20entradas%20y%20me%20sali%C3%B3%20un%20error%20" target="_blank" style="text-decoration: none;">
                <button class="whatsapp-btn">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Logo">
                    <span>WhatsApp</span>
                </button>
            </a>
        </div>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="https://nixcol.com" 
               style="display: inline-block; background-color: #007BFF; color: white; padding: 12px 30px; text-decoration: none; font-size: 15px; border-radius: 5px; transition: background-color 0.3s, box-shadow 0.3s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-top: 20px;">
               Regresar
            </a>
          </div>

        <!-- Footer con información adicional -->
        <div class="footer-info">
            <p>Si necesitas asistencia adicional, no dudes en contactarnos por WhatsApp.</p>
        </div>
    </div>

    <!-- Vincula los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>
