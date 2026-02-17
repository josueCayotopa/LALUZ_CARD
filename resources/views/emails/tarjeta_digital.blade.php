<div style="position: relative; width: 600px; height: 370px; font-family: Arial, sans-serif; color: white;">
    <img src="{{ $message->embed(public_path('images/fondo-tarjeta.png')) }}" style="width: 100%; border-radius: 15px;">

    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
        
        <div style="position: absolute; top: 180px; right: 40px; text-align: right; width: 400px;">
            <span style="font-size: 22px; font-weight: bold; text-transform: uppercase;">
                {{ $afiliado->Afiliado_Nombres }}
            </span>
        </div>

        <div style="position: absolute; top: 220px; right: 40px; text-align: right;">
            <span style="font-size: 18px; font-weight: bold;">
                DNI: {{ $afiliado->Afiliado_DNI }}
            </span>
        </div>

        <div style="position: absolute; bottom: 35px; right: 40px; text-align: right;">
            <div style="font-size: 10px; text-transform: uppercase; margin-bottom: 2px;">Válida hasta</div>
            <span style="font-size: 16px; font-weight: bold;">
                {{ \Carbon\Carbon::parse($afiliado->fecha_fin_vigencia)->format('d / m / y') }}
            </span>
        </div>
    </div>
</div>