<div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;">
    <div style="background-color: #B11A1A; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0; font-size: 24px;">¡Bienvenido a LUZCARD!</h1>
    </div>

    <div style="padding: 30px; text-align: center;">
        <p style="font-size: 16px;">Hola <strong>{{ $nombre }}</strong>,</p>
        <p>Es un placer darte la bienvenida a la familia de <strong>Clínica La Luz</strong>. Aquí tienes tu nueva tarjeta digital:</p>
        
        <div style="margin: 25px 0;">
            <img src="cid:tarjeta_digital" alt="Tu Tarjeta LUZCARD" style="width: 100%; max-width: 500px; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
        </div>

        <p style="font-size: 14px; color: #666;">Puedes guardarla en tu celular o imprimirla para presentarla en nuestras sedes y acceder a tus beneficios.</p>
    </div>

    <div style="background-color: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #999;">
        <p>Este es un correo automático, por favor no lo respondas.<br>
        &copy; {{ date('Y') }} Clínica La Luz - Todos los derechos reservados.</p>
    </div>
</div>