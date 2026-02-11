<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-top: 5px solid #B11A1A; }
        .header { background: #f8f8f8; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .footer { background: #B11A1A; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .button { background: #B11A1A; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" height="60">
        </div>
        <div class="content">
            <h1 style="color: #B11A1A;">¡Felicidades, {{ $nombre }}!</h1>
            <p>Es un placer darte la bienvenida a la familia de <strong>Clínica La Luz</strong>.</p>
            <p>Ya eres titular de tu tarjeta <strong>LUZCARD</strong>. Con ella, podrás acceder a descuentos exclusivos del 10% en consultas, exámenes y cirugías, diseñados para cuidar lo más importante: tu salud.</p>
            
            <div style="background: #fff5f5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p><strong>Tus datos de afiliación:</strong><br>
                DNI: {{ $dni }}<br>
                Vigencia hasta: {{ $fechaVigencia }}</p>
            </div>

            <p>Adjunto a este correo encontrarás tu <strong>Contrato de Afiliación Digital</strong> y tu tarjeta lista para usar.</p>
            
            <p align="center">
                <a href="https://clinicalaluz.pe" class="button">Visitar Web de la Clínica</a>
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Clínica La Luz - Todos los derechos reservados.<br>
            Av. Arequipa 1148, Lima.
        </div>
    </div>
</body>
</html>