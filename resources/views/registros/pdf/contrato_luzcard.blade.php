<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuración de Página y Márgenes Reales */
        @page {
            margin: 1.2cm 1.5cm;
            size: A4;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #1a1a1a;
        }
        .page-break { page-break-after: always; }
        
        /* Encabezado con Logos */
        .header { width: 100%; margin-bottom: 10px; border-bottom: 2px solid #B11A1A; padding-bottom: 5px; }
        .logo-left { float: left; width: 45%; }
        .logo-right { float: right; width: 45%; text-align: right; }
        .clearfix { clear: both; }

        /* Títulos y Secciones */
        .title-main { color: #B11A1A; text-align: center; font-weight: bold; font-size: 14pt; margin: 5px 0; }
        .subtitle { text-align: center; font-weight: bold; margin-bottom: 10px; text-decoration: underline; }
        .section-title { color: #B11A1A; font-weight: bold; margin-top: 10px; margin-bottom: 5px; }

        /* Tablas de Beneficios Estilo PDF */
        .table-beneficios { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .table-beneficios th { background-color: #B11A1A; color: white; border: 1px solid #000; padding: 5px; font-size: 9pt; }
        .table-beneficios td { border: 1px solid #000; padding: 4px 8px; font-size: 8.5pt; }

        /* Cuadro de Datos del Afiliado (Pág 4) */
        .box-container { border: 2px solid #B11A1A; margin-top: 10px; }
        .box-title { background-color: #B11A1A; color: white; padding: 4px 10px; font-weight: bold; }
        .field-row { border-bottom: 1px solid #B11A1A; padding: 6px 10px; }
        .field-row:last-child { border-bottom: none; }
        .col-2 { width: 50%; float: left; }

        /* Firma y Huella */
        .signature-container { margin-top: 30px; text-align: center; }
        .fingerprint-box { width: 80px; height: 100px; border: 1px solid #000; display: inline-block; margin-left: 20px; vertical-align: middle; }
        .signature-line { width: 250px; border-top: 1px solid #000; display: inline-block; margin-top: 70px; vertical-align: middle; }
        
        /* Estilos adicionales */
        p { text-align: justify; margin-bottom: 6px; }
        .highlight { color: #B11A1A; font-weight: bold; }
        ul { margin-left: 20px; }
        li { margin-bottom: 4px; text-align: justify; }
    </style>
</head>
<body>

    {{-- ========== PÁGINA 1 ========== --}}
    <div class="header">
        <div class="logo-left"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" height="55"></div>
        <div class="logo-right"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/Logo1.png'))) }}" height="45"></div>
        <div class="clearfix"></div>
    </div>

    <div class="title-main">LA LUZ CARD</div>
    <div class="subtitle">MANUAL DE USO Y CONDICIONES GENERALES DE CONTRATACIÓN</div>

    <p><strong>CLINICA LA LUZ S.A.C.</strong> con RUC. N° 20537489295, en el distrito, provincia y departamento de Lima, le da la bienvenida a la <span class="highlight">LUZCARD</span>, la cual le da acceso a múltiples beneficios. Para acceder a los beneficios deberá leer detenidamente el presente documento, firmarlo y pagar el derecho de afiliación.</p>

    <p>Los beneficios se especifican en el presente documento, beneficios adicionales y activaciones de beneficios en periodos de tiempo determinados se publicarán en la forma y medios que oportunamente determine Clínica La Luz.</p>

    <p>La recepción de la <span class="highlight">LUZCARD</span> y la firma del presente documento, constituye la aceptación a los términos y condiciones que se expresan a continuación:</p>

    <div class="section-title">I. DEFINICIONES OPERATIVAS</div>
    <p style="margin-bottom: 4px;"><strong>- BENEFICIARIO O TITULAR:</strong> Persona natural sin límite de edad, que accederá a los descuentos y beneficios de la LUZCARD.</p>
    <p style="margin-bottom: 4px;"><strong>- CONSULTA:</strong> Es la evaluación médica realizada en el servicio de emergencia y/o consultorio de una de las especialidades que oferta clínica.</p>
    <p style="margin-bottom: 4px;"><strong>- CONTRATANTE:</strong> Persona natural que adquiere y se hace socio de la LUZCARD.</p>
    <p style="margin-bottom: 4px;"><strong>- DESCUENTO:</strong> Porcentaje que se reducirá de las tarifa vigente al momento de hacer uso del servicio, al beneficiario, titular y/o contratante de la LUZCARD, aplicable sobre la base de la tarifa regular o precio al público en general. El descuento no resulta acumulable, ni aplicable sobre tarifas de paquetes, campañas y/o promociones.</p>
    <p style="margin-bottom: 4px;"><strong>- IMÁGENES:</strong> Estudios de apoyo al diagnóstico del área de radiología realizados en la Clínica La Luz.</p>
    <p style="margin-bottom: 4px;"><strong>- INTERCONSULTA:</strong> Acto médico en donde interviene un profesional de la salud de distinta especialidad a la del médico tratante, la misma que brindará una opinión especializada sobre un caso específico en un paciente determinado.</p>
    <p style="margin-bottom: 4px;"><strong>- LABORATORIO:</strong> se incluyen todas las pruebas de laboratorio clínico y de anatomía patológica que estén incluidas en la cartera de servicios de la clínica, las cuales requerirán para los fines del beneficio, la respectiva solicitud y/o indicación médica.</p>
    <p style="margin-bottom: 4px;"><strong>- MEDICAMENTOS:</strong> Productos farmacológicos que se encuentren disponibles en la farmacia de la clínica. No se consideran como medicamento a los materiales médicos, materiales de osteosíntesis y prótesis. Tampoco se incluyen en esta definición a los insumos y materiales que son requeridos para la aplicación de medicamentos.</p>
    <p style="margin-bottom: 4px;"><strong>- PROCEDIMIENTO DIAGNÓSTICO AMBULATORIO:</strong> Acto médico que se realiza utilizando o no algún equipo biomédico para poder confirmar y/o descartar una enfermedad. No requiere hospitalización.</p>
    <p style="margin-bottom: 4px;"><strong>- PROCEDIMIENTO TERAPÉUTICO AMBULATORIO:</strong> Acto médico que se realiza utilizando o no algún equipo biomédico para poder tratar una enfermedad. No requiere hospitalización.</p>
    <p style="margin-bottom: 4px;"><strong>- TARIFAS PREFERENCIALES:</strong> Tarifas de productos o servicios, por debajo del valor ofrecido al público en general, a las cuales podrán acceder los titulares Beneficiarios de la LUZCARD.</p>

    <div class="section-title">II. AFILIACIÓN</div>
    <p style="margin-bottom: 4px;">- Podrán acceder como beneficiario y/o titular de la LUZCARD, personas sin límite de edad, debidamente identificadas con su Documento Nacional de Identidad D.N.I. vigente para ciudadanos peruanos o carnet de extranjería en el caso de extranjeros.</p>
    <p style="margin-bottom: 4px;">- La afiliación tendrá un costo de <strong>S/. 100.00 Soles</strong>. Este costo podría variar en el tiempo, lo cual será debidamente comunicado en su momento a las personas nuevas que lo adquieran, mediante los medios que se señalan en el punto VII de este documento. La adquisición de la tarjeta implicará la aceptación de los términos y condiciones de afiliación a la LUZCARD.</p>

    <div style="margin-top: 20px; text-align: right; font-size: 8pt; color: #666;">Página 1 de 4</div>
    <div class="page-break"></div>

    {{-- ========== PÁGINA 2 ========== --}}
    <div class="header">
        <div class="logo-left"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" height="55"></div>
        <div class="logo-right"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/Logo1.png'))) }}" height="45"></div>
        <div class="clearfix"></div>
    </div>

    <p style="margin-bottom: 4px;">- Luego del pago de la afiliación, Clínica La Luz emitirá una (01) tarjeta personal e intransferible digital a nombre del titular, para que pueda acceder a los beneficios de la LUZCARD.</p>
    
    <p style="margin-bottom: 4px;">- La información que Clínica La Luz reúna a través de la ficha de registro de datos y afiliación a la LUZCARD, será tratada con arreglo a las disposiciones contenidas en la Ley N° 29733, Ley de Protección de Datos Personales, y su reglamento aprobado mediante Decreto Supremo N° 003-2013, así como sus modificaciones. Se deja expresa constancia, que con la afiliación a la LUZCARD, el usuario autoriza a Clínica La Luz acercarle propuestas, ofertas y promociones. El cliente en todo momento tendrá posibilidad de solicitar gratuitamente la rectificación y/o suspensión total o parcial de algunos de los datos suministrados, debiendo para tal efecto comunicarse con los módulos de atención de la clínica.</p>
    
    <p style="margin-bottom: 4px;">- La membresía de la LUZCARD tiene una duración de <strong>un (01) año</strong> y podrá ser renovada por el mismo período, previa cancelación de renovación y/o emisión de una nueva tarjeta, costo en su momento se le informará al adquiriente.</p>
    
    <p style="margin-bottom: 4px;">- La LUZCARD no es de débito, ni de crédito, ni de compra, son intransferibles y no aplican como medio de pago; solo sirve para los beneficios que luego se detallan.</p>

    <div class="section-title">III. DE LOS BENEFICIOS DE LA LUZ CARD</div>
    <p>Los beneficios que luego se detallan, son brindados en su establecimiento ubicado en Lima, en su dirección señalada en la introducción de este documento.</p>

    <p style="margin-bottom: 4px;">- Los beneficios de la LUZCARD, solamente se extenderán a favor de su beneficiario y/o titular.</p>
    <p style="margin-bottom: 4px;">- <strong>Los beneficios estarán vigentes desde el día de la suscripción</strong>.</p>
    <p style="margin-bottom: 4px;">- Los beneficios de la LUZCARD no podrán venderse, transferirse, cederse o de cualquier otra forma negociarse, ni aún en el caso de fallecimiento del titular.</p>
    <p style="margin-bottom: 4px;">- Los beneficios no podrán ser sustituidos o canjeados en ningún caso, por dinero.</p>
    <p style="margin-bottom: 4px;">- El beneficiario y/o titular sólo adquiere el derecho personal para recibir alguno de los beneficios y/o descuentos de la LUZCARD.</p>
    <p style="margin-bottom: 4px;">- Bajo ningún motivo se realizarán reembolsos por beneficios no utilizados.</p>
    <p style="margin-bottom: 4px;">- Las tarifas preferenciales y descuentos, aplican únicamente a los servicios que se incluyen en el presente documento. Los descuentos se aplican sobre la tarifa vigente (al momento de la atención). Los beneficios no resultan acumulables con otras promociones.</p>
    <p style="margin-bottom: 4px;">- Los beneficios a los que accederán los titulares de la LUZCARD, contienen limitaciones y restricciones particulares detalladas en el presente documento.</p>
    <p style="margin-bottom: 4px;">- Las citas a las diferentes especialidades se encuentran sujetas a la disponibilidad de cada médico.</p>

    <div class="section-title">IV. MODIFICACIONES A LOS BENEFICIOS</div>
    <p>En cualquier momento, Clínica La Luz podrá efectuar modificaciones a los beneficios de la LUZCARD, agregando o reduciendo o eliminando descuentos, promociones o tarifas preferenciales ya sea en la clínica o en otros establecimientos afiliados. En caso la modificación resulte una reducción o supresión de los beneficios del titular, Clínica La Luz notificará al contratante de este hecho por medio escrito o electrónico a las direcciones precisadas en el presente documento, otorgándole un plazo de <strong>30 días calendario</strong> para solicitar la renuncia a la tarjeta en caso así lo considere. Si el contratante no manifiesta su deseo de desvincularse a la LUZCARD en el plazo antes referido, se entenderá que se encuentra conforme con las modificaciones realizadas al régimen de beneficios de la LUZCARD.</p>

    <div style="margin-top: 20px; text-align: right; font-size: 8pt; color: #666;">Página 2 de 4</div>
    <div class="page-break"></div>

    {{-- ========== PÁGINA 3 ========== --}}
    <div class="header">
        <div class="logo-left"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" height="55"></div>
        <div class="logo-right"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/Logo1.png'))) }}" height="45"></div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">V. PROCEDIMIENTO PARA EL ACCESO A LOS BENEFICIOS</div>
    <p>Para acceder a los beneficios de la LUZCARD, el beneficiario y/o titular indicar su documento de identidad al momento de la admisión y/o al momento de cancelar por el servicio solicitado.</p>

    <div class="section-title">VI. TARIFAS APLICABLES A LA TARJETA</div>
    <p style="margin-bottom: 4px;"><strong>- Membresía anual: S/. 100.00</strong></p>
    <p style="margin-bottom: 4px;"><strong>- Renovación de membresía: S/. 100.00</strong></p>

    <div class="section-title">VII. RELACIÓN DE BENEFICIOS</div>
    <table class="table-beneficios">
        <thead>
            <tr>
                <th width="70%">BENEFICIOS CLÍNICA LA LUZ</th>
                <th width="30%">TARIFA / DESCUENTO</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Consulta externa (todas las especialidades).</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Exámenes de apoyo al diagnóstico: laboratorio clínico, ecografía, rayos x, tomografía, mamografía, densitometría y anatomía patológica.</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Servicios clínicos.</td><td align="center">10% descuento (*) (**)</td></tr>
            <tr><td>Uso de equipos médicos.</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Óptica: resinas y monturas.</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Servicio de ambulancia.</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Trámites administrativos: informes médicos y copia de historia clínica.</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Cirugías (todas las especializadades)</td><td align="center">10% descuento (*)</td></tr>
            <tr><td>Aplicación de inyectables.</td><td align="center">Gratuito</td></tr>
            <tr><td>Procedimientos (todas las especialidades)</td><td align="center">10% descuento</td></tr>
            <tr><td>Control de presión arterial, peso y talla.</td><td align="center">Gratuito</td></tr>
        </tbody>
    </table>
    
    <p style="font-size: 8pt;">(*) Los descuentos se aplican sobre la tarifa vigente (al momento de la atención) de paciente particular.<br>
    (**) El descuento resulta aplicable a los siguientes servicios Clínicos</p>

    <table class="table-beneficios" style="width: 60%;">
        <thead>
            <tr><th>SERVICIOS CLÍNICOS SUJETOS A DESCUENTO</th></tr>
        </thead>
        <tbody>
            <tr><td>Servicio de Hospitalización</td></tr>
            <tr><td>Procedimientos médicos</td></tr>
            <tr><td>Sala de operaciones y sala de recuperación</td></tr>
            <tr><td>Tópico de emergencia</td></tr>
        </tbody>
    </table>

    <div class="section-title">VIII. RESTRICCIONES</div>
    <p><strong>8.1.</strong> Se deja constancia que los beneficios de la tarjeta LUZCARD no resultan aplicables en los siguientes casos:</p>
    <ul style="margin-left: 35px; list-style-type: lower-alpha;">
        <li>Tratamientos odontológicos de ortodoncia, prótesis dental e implantes.</li>
        <li>Medicamentos, insumos médicos, vitaminas y/o suplementos.</li>
        <li>Evaluación especializada para riesgo cardiológico, riesgo neumológico y/o riesgo anestesiológico de emergencia.</li>
        <li>Chequeos preventivos ni los distintos paquetes, promociones y campañas ofertadas por la clínica.</li>
        <li>Servicios de Banco de Sangre.</li>
        <li>Servicios de Nutrición Enteral y Parenteral.</li>
        <li>Servicios de nutrición en hospitalización.</li>
        <li>Servicios de resonancia magnética.</li>
        <li>Procedimientos de tomografía con contraste.</li>
        <li>Interconsultas hospitalarias y/o de emergencia.</li>
        <li>Sala de observaciones de emergencia.</li>
        <li>Honorarios médicos correspondientes a procedimientos en sala de hemodinamia. Materiales médicos, materiales de osteosíntesis y prótesis.</li>
    </ul>

    <div style="margin-top: 10px; text-align: right; font-size: 8pt; color: #666;">Página 3 de 4</div>
    <div class="page-break"></div>

    {{-- ========== PÁGINA 4 ========== --}}
    <div class="header">
        <div class="logo-left"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" height="55"></div>
        <div class="logo-right"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/Logo1.png'))) }}" height="45"></div>
        <div class="clearfix"></div>
    </div>

    <p><strong>8.2.</strong> Para el caso de atenciones y/o consultas en el servicio de Emergencia, los descuentos aplicarán a la consulta de medicina a cargo del médico de turno programado en las instalaciones de Emergencia. De requerir una consulta en pediatría, ginecología, traumatología, cirugía general u otra especialidad, no aplicarán los descuentos y serán aplicadas las tarifas de atención particular.</p>

    <div class="section-title">IX. COMUNICACIONES Y SOLUCIÓN DE CONTROVERSIAS</div>
    <p style="margin-bottom: 4px;">- Cualquier comunicación cursada por Clínica La Luz a un beneficiario, se considerará notificada en dirección electrónica proporcionada en la respectiva solicitud y que se encuentre almacenada en los registros de clientes. Es responsabilidad del titular mantener actualizados sus datos y comunicar cualquier modificación a la clínica.</p>
    <p style="margin-bottom: 4px;">- Todas las preguntas o asuntos concernientes a la LUZCARD serán resueltos por el personal de la clínica.</p>
    <p style="margin-bottom: 4px;">- Cualquier diferencia de interpretación o conflicto será resuelta de común acuerdo y de buena Fe.</p>

    <p style="margin-top: 10px; font-style: italic; background-color: #f5f5f5; padding: 8px; border-left: 3px solid #B11A1A;"><strong>Luego de haber revisado el presente documento, declaro haber leído y encontrarme conforme con todo lo descrito y/o estipulado, siendo mi firma señal de conformidad y aceptación a los términos estipulados en el presente documento.</strong></p>

    <div class="box-container" style="margin-top: 15px;">
        <div class="box-title">DATOS DEL ORIENTADOR</div>
        <div class="field-row">
            <div class="col-2"><strong>ORIENTADOR:</strong> {{ $afiliado->Orientador->DES_VENDEDOR ?? '' }}</div>
            <div class="col-2"><strong>FECHA:</strong> {{ \Carbon\Carbon::parse($afiliado->Fecha_Registro)->format('d/m/Y') }}</div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="box-container" style="margin-top: 10px;">
        <div class="box-title">AFILIADO</div>
        <div class="field-row"><strong>Nombres y Apellidos:</strong> {{ $afiliado->Afiliado_Nombres }}</div>
        <div class="field-row">
            <div class="col-2"><strong>DNI:</strong> {{ $afiliado->Afiliado_DNI }}</div>
            <div class="col-2"><strong>Teléfono:</strong> {{ $afiliado->Afiliado_Telefono ?? 'No proporcionado' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="field-row"><strong>Dirección:</strong> {{ $afiliado->Afiliado_Direccion ?? 'No proporcionado' }}</div>
        <div class="field-row"><strong>Correo electrónico:</strong> {{ $afiliado->Afiliado_Email ?? 'No proporcionado' }}</div>
    </div>

    @if($afiliado->Apoderado_Nombres)
    <div class="box-container" style="margin-top: 10px;">
        <div class="box-title">PADRE Y/O APODERADO <span style="float: right;">Parentesco: {{ $afiliado->Apoderado_Parentesco ?? '' }}</span></div>
        <div class="field-row"><strong>Nombres y Apellidos:</strong> {{ $afiliado->Apoderado_Nombres }}</div>
        <div class="field-row">
            <div class="col-2"><strong>DNI:</strong> {{ $afiliado->Apoderado_DNI ?? '' }}</div>
            <div class="col-2"><strong>Teléfono:</strong> {{ $afiliado->Apoderado_Telefono ?? 'No proporcionado' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="field-row"><strong>Dirección:</strong> {{ $afiliado->Apoderado_Direccion ?? 'No proporcionado' }}</div>
        <div class="field-row"><strong>Correo electrónico:</strong> {{ $afiliado->Apoderado_Email ?? 'No proporcionado' }}</div>
    </div>
    @endif

    <div class="signature-container">
        <div style="display: inline-block; vertical-align: top;">
            <div class="signature-line"></div>
            <p style="margin-top: 5px;"><strong>Firma y huella del contratante</strong></p>
        </div>
        <div class="fingerprint-box"></div>
    </div>

    <div style="margin-top: 30px; text-align: right; font-size: 8pt; color: #666;">Página 4 de 4</div>

</body>
</html>