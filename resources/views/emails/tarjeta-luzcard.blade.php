<div class="content">
    <h1 style="color: #B11A1A;">¡Felicidades, {{ $nombre }}!</h1>
    <p>Es un placer darte la bienvenida a la familia de <strong>Clínica La Luz</strong>.</p>
    
    <div style="position: relative; width: 100%; max-width: 500px; height: 310px; margin: 20px auto; font-family: Arial, sans-serif; color: white; overflow: hidden; border-radius: 15px; shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <img src="{{ asset('images/fondo-tarjeta.png') }}" style="width: 100%; height: 100%; display: block;">

        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
            <div style="position: absolute; top: 52%; right: 7%; text-align: right; width: 80%;">
                <span style="font-size: 20px; font-weight: bold; text-transform: uppercase; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    {{ $nombre }}
                </span>
            </div>
            <div style="position: absolute; top: 65%; right: 7%; text-align: right;">
                <span style="font-size: 16px; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    DNI: {{ $dni }}
                </span>
            </div>
            <div style="position: absolute; bottom: 8%; right: 7%; text-align: right;">
                <div style="font-size: 9px; text-transform: uppercase; opacity: 0.9;">Válida hasta</div>
                <span style="font-size: 14px; font-weight: bold;">
                    {{ $fechaVigencia }}
                </span>
            </div>
        </div>
    </div>
    <p>Ya eres titular de tu tarjeta <strong>LUZCARD</strong>. Con ella, podrás acceder a descuentos exclusivos...</p>
    
    <p>Adjunto a este correo encontrarás tu <strong>Contrato de Afiliación Digital</strong> y tu tarjeta lista para imprimir si lo deseas.</p>
</div>