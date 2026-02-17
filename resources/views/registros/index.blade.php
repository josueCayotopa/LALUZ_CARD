@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl font-bold leading-7 text-clinica-base sm:truncate">
                Lista de Afiliados
            </h2>
            <p class="mt-1 text-clinica-gray text-sm">Gestión de registros LuzCard</p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button onclick="openModal()" type="button" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-clinica-base hover:bg-clinica-hover transition">
                <i class="fa-solid fa-user-plus mr-2"></i> Nuevo Registro
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded-r shadow-sm flex justify-between items-center">
        <div>
            <span class="font-bold">¡Éxito!</span> {{ session('success') }}
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border border-gray-200 mb-8">
        <form action="{{ route('dashboard') }}" method="GET" class="flex gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-clinica-base focus:border-clinica-base sm:text-sm" placeholder="Buscar por DNI o Nombres...">
            </div>
            <button type="submit" class="px-6 py-3 bg-clinica-gray text-white rounded-lg hover:bg-gray-600 transition font-medium">
                Buscar
            </button>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">Afiliado</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">DNI</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">Boleta / Monto</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">Vigencia</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-clinica-base uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registros as $reg)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">#{{ $reg->ID_Registro }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $reg->Afiliado_Nombres }}</div>
                        <div class="text-xs text-gray-500">Reg: {{ $reg->Fecha_Registro ? $reg->Fecha_Registro->format('d/m/Y') : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $reg->Afiliado_DNI }}</td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <div class="font-bold">{{ $reg->boleta ?? 'S/N' }}</div>
                        <div class="text-xs text-green-600 font-bold">S/ {{ number_format($reg->total ?? 100, 2) }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="text-xs">Vence: {{ $reg->fecha_fin_vigencia ? $reg->fecha_fin_vigencia->format('d/m/Y') : '-' }}</span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                        @if($reg->Ruta_Contrato)
                        <a href="{{ Storage::url($reg->Ruta_Contrato) }}" target="_blank" class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition" title="Imprimir Contrato">
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        </a>
                        @else
                        <a href="{{ route('afiliados.reimprimir', $reg->ID_Registro) }}" class="text-gray-400 hover:text-clinica-base p-2 rounded-full hover:bg-gray-50 transition" title="Generar PDF">
                            <i class="fa-solid fa-print text-xl"></i>
                        </a>
                        @endif

                        @if($reg->Afiliado_Email)
                        <button type="button" onclick="confirmarEnvio('{{ $reg->ID_Registro }}', '{{ $reg->Afiliado_Email }}')" class="text-blue-600 hover:text-blue-800 p-2" title="Enviar Tarjeta Digital">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </button>
                        {{-- Formulario oculto --}}
                        <form id="form-enviar-{{ $reg->ID_Registro }}" action="{{ route('luzcard.correo.enviar', $reg->ID_Registro) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endif
                        {{-- Cambia $registro por $reg --}}
                        <button onclick='editAfiliado(@json($reg))' class="text-blue-600 hover:text-blue-900 p-2" title="Editar Afiliado">
                            <i class="fa-solid fa-pen text-xl"></i> Editar
                        </button>

                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $registros->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@include('registros.modal_form')

@endsection

@push('scripts')
<script>
    // Funciones para abrir/cerrar modal
    function openModal() {
        const modal = document.getElementById('modalRegistro');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Evitar scroll de fondo
        }
    }

    function closeModal() {
        const modal = document.getElementById('modalRegistro');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restaurar scroll
        }
    }

    // LÓGICA CLAVE: Si Laravel devolvió errores (validación fallida),
    // abrimos el modal automáticamente al cargar la página.
    @if($errors-> any())
    document.addEventListener('DOMContentLoaded', function() {
        openModal();
    });
    @endif

    function buscarPaciente() {
        const dni = document.getElementById('Afiliado_DNI').value;
        const btnBusqueda = event.currentTarget;

        if (dni.length !== 8) {
            Swal.fire({
                icon: 'warning'
                , title: 'DNI Inválido'
                , text: 'Por favor, ingrese 8 dígitos.'
            });
            return;
        }

        // Efecto de carga en el botón
        const originalContent = btnBusqueda.innerHTML;
        btnBusqueda.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btnBusqueda.disabled = true;

        fetch(`/paciente/buscar/${dni}`)
            .then(response => {
                if (!response.ok) throw new Error('No encontrado');
                return response.json();
            })
            .then(data => {
                // Cargamos el nombre completo en el campo correspondiente
                // Ajusta el ID 'Afiliado_Nombres' según tu formulario
                const inputNombre = document.querySelector('input[name="Afiliado_Nombres"]');
                if (inputNombre) {
                    inputNombre.value = data.nombres;
                    inputNombre.classList.add('bg-green-50'); // Feedback visual de éxito
                }

                Swal.fire({
                    icon: 'success'
                    , title: 'Paciente Encontrado'
                    , text: data.nombres
                    , timer: 1500
                    , showConfirmButton: false
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'info'
                    , title: 'No registrado'
                    , text: 'El paciente no existe en el sistema base de la clínica. Puede registrar los datos manualmente.'
                });
            })
            .finally(() => {
                btnBusqueda.innerHTML = originalContent;
                btnBusqueda.disabled = false;
            });
    }

    function confirmarEnvio(id, email) {
        // Verifica que SweetAlert esté cargado
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 no está cargado');
            document.getElementById('form-enviar-' + id).submit(); // Envío directo si falla Swal
            return;
        }

        Swal.fire({
            title: '¿Enviar Tarjeta Digital?'
            , text: "Se enviará el contrato y la tarjeta de felicitaciones a: " + email
            , icon: 'question'
            , showCancelButton: true
            , confirmButtonColor: '#B11A1A'
            , cancelButtonColor: '#8B8889'
            , confirmButtonText: 'Sí, enviar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Enviando...'
                    , text: 'Por favor espere'
                    , allowOutsideClick: false
                    , didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Este ID debe coincidir con el ID del formulario en la tabla
                document.getElementById('form-enviar-' + id).submit();
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Alerta de éxito desde el controlador
        @if(session('success'))
        Swal.fire({
            icon: 'success'
            , title: '¡Operación Exitosa!'
            , text: "{{ session('success') }}"
            , confirmButtonColor: '#B11A1A'
        });
        @endif

        // Alerta de error desde el controlador
        @if(session('error'))
        Swal.fire({
            icon: 'error'
            , title: 'Ocurrió un error'
            , text: "{{ session('error') }}"
            , confirmButtonColor: '#B11A1A'
        });
        @endif
    });

    function editAfiliado(afiliado) {
    const form = document.getElementById('formAfiliado');
    const methodField = document.getElementById('methodField');
    const fechaInput = document.getElementById('Fecha_Registro');
    // 1. Cambiar Action y Método
    form.action = `/afiliados/${afiliado.ID_Registro}`;
    methodField.value = 'PUT';
    if (fechaInput) {
        fechaInput.removeAttribute('min'); 
    }

    // 2. Llenar campos con validación de nulos
    const setVal = (name, val) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (input) input.value = val || '';
    };

    setVal('Afiliado_DNI', afiliado.Afiliado_DNI);
    setVal('Afiliado_Nombres', afiliado.Afiliado_Nombres);
    setVal('Afiliado_Telefono', afiliado.Afiliado_Telefono);
    setVal('Afiliado_Email', afiliado.Afiliado_Email);
    setVal('Afiliado_Direccion', afiliado.Afiliado_Direccion);
    setVal('Orientador', afiliado.Orientador);

    // Datos Apoderado
    setVal('Apoderado_Parentesco', afiliado.Apoderado_Parentesco);
    setVal('Apoderado_Nombres', afiliado.Apoderado_Nombres);
    setVal('Apoderado_DNI', afiliado.Apoderado_DNI);
    setVal('Apoderado_Telefono', afiliado.Apoderado_Telefono);
    setVal('Apoderado_Direccion', afiliado.Apoderado_Direccion);
    setVal('Apoderado_Email', afiliado.Apoderado_Email);

    // Checkbox
    const checkFirma = form.querySelector('[name="Tiene_Firma_Huella"]');
    if (checkFirma) checkFirma.checked = (afiliado.Tiene_Firma_Huella == 1);

    // 3. UI: Cambiar título y botón
    document.querySelector('#modalRegistro h2').innerHTML = '<i class="fa-regular fa-pen-to-square"></i> Actualizar Afiliado #' + afiliado.ID_Registro;
    form.querySelector('button[type="submit"]').innerHTML = '<i class="fa-solid fa-sync"></i> Actualizar Cambios';

    // 4. Abrir Modal
    document.getElementById('modalRegistro').classList.remove('hidden');
}
    // Función para resetear el modal cuando sea "Nuevo Registro"
    function openCreateModal() {
        const form = document.getElementById('formAfiliado');
        form.reset();
        form.action = "{{ route('afiliados.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.querySelector('#modalRegistro h2').innerHTML = '<i class="fa-regular fa-id-card"></i> Registro de Afiliado';
        document.getElementById('modalRegistro').classList.remove('hidden');
    }

</script>
@endpush
