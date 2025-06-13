<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - Pendiente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
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

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-success strong {
            font-size: 1.2rem;
        }

        .error-message {
            font-size: 1.2rem;
            color: #5cb85c;
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

        .whatsapp-btn span {
            font-weight: bold;
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
        <!-- Mensaje de pago pendiente -->
        <div class="alert alert-success">
            <strong>Estimado Cliente:</strong>
            <p><?php echo 'Tu pago se encuentra Pendiente.' . '<br>' ?></p>
            <p>Una vez aprobado el pago, te enviaremos la confirmación y tus números al correo electrónico.</p>
            <p>Si tu medio de pago es en efectivo (Efecty), te invitamos a realizar el pago para finalizar tu compra.</p>
        </div>

        <!-- Mensaje de contacto con WhatsApp -->
        <div class="text-center">
            <a href="https://api.whatsapp.com/send/?phone=573128956692&text=Hola%2C+quiero+m%C3%A1s+informaci%C3%B3n&type=phone_number&app_absent=0" target="_blank" style="text-decoration: none;">
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
            <p>¿Aún te quedan dudas?, escríbeme y las resolveos</p>
        </div>
    </div>

    <!-- Vincula los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>
