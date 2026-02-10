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
                <input type="text" name="buscar" value="{{ request('buscar') }}" 
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-clinica-base focus:border-clinica-base sm:text-sm" 
                       placeholder="Buscar por DNI o Nombres...">
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-clinica-base uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 relative">
                        <span class="sr-only">Editar</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registros as $reg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">#{{ $reg->ID_Registro }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reg->Afiliado_Nombres }}</div>
                            <div class="text-xs text-gray-500">{{ $reg->Fecha_Registro ? $reg->Fecha_Registro->format('d/m/Y') : '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $reg->Afiliado_DNI }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reg->Afiliado_Telefono ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reg->Estado_Registro === 'ACT' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $reg->Estado_Registro }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-clinica-base hover:text-clinica-hover"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No se encontraron registros.</td>
                    </tr>
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
        if(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Evitar scroll de fondo
        }
    }

    function closeModal() {
        const modal = document.getElementById('modalRegistro');
        if(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restaurar scroll
        }
    }

    // LÓGICA CLAVE: Si Laravel devolvió errores (validación fallida),
    // abrimos el modal automáticamente al cargar la página.
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openModal();
        });
    @endif
    function buscarPaciente() {
    const dni = document.getElementById('Afiliado_DNI').value;
    const btnBusqueda = event.currentTarget;

    if (dni.length !== 8) {
        Swal.fire({
            icon: 'warning',
            title: 'DNI Inválido',
            text: 'Por favor, ingrese 8 dígitos.'
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
                icon: 'success',
                title: 'Paciente Encontrado',
                text: data.nombres,
                timer: 1500,
                showConfirmButton: false
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'info',
                title: 'No registrado',
                text: 'El paciente no existe en el sistema base de la clínica. Puede registrar los datos manualmente.'
            });
        })
        .finally(() => {
            btnBusqueda.innerHTML = originalContent;
            btnBusqueda.disabled = false;
        });
}
</script>
@endpush