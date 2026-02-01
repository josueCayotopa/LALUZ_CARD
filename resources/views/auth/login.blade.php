@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex bg-white">
    
    <div class="hidden lg:flex lg:w-1/2 bg-clinica-base justify-center items-center relative overflow-hidden">
        <div class="absolute bg-white opacity-5 w-96 h-96 rounded-full -top-20 -left-20 blur-3xl mix-blend-overlay"></div>
        <div class="absolute bg-black opacity-10 w-64 h-64 rounded-full bottom-10 right-10 blur-2xl mix-blend-multiply"></div>
        
        <div class="text-center relative z-10 px-10">
            <div class="mb-8 flex justify-center">
               {{-- <img src="{{ asset('images/logo-clinica-blanco.png') }}" alt="Logo Clínica La Luz" class="h-24 w-auto"> --}}
               <div class="h-28 w-28 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white text-5xl shadow-xl border border-white/20">
                    <i class="fa-solid fa-hospital-user"></i>
               </div>
            </div>
            
            <h1 class="text-4xl font-bold text-white mb-4 tracking-tight drop-shadow-md">LUZCARD</h1>
            <p class="text-red-100 text-lg max-w-md mx-auto leading-relaxed font-light">
                Sistema Integrado de Gestión de Afiliados. 
                <br class="hidden xl:block"> Tecnología al servicio de la salud.
            </p>
        </div>
    </div>

    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-gray-50 lg:bg-white">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            
            <div class="lg:hidden text-center mb-10">
                <div class="inline-flex h-16 w-16 bg-clinica-base rounded-xl items-center justify-center text-white text-3xl shadow-md mb-4">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Acceso LUZCARD</h2>
            </div>

            <div class="mb-8">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 hidden lg:block">
                    Bienvenido
                </h2>
                <p class="mt-2 text-sm text-clinica-gray font-medium">
                    Ingrese sus credenciales para acceder al sistema.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-clinica-base p-4 rounded-r-md animate-pulse">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-clinica-base"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-800 font-bold">
                                Error de autenticación
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="empresa" class="block text-sm font-bold text-clinica-gray mb-1">
                            Sede / Empresa
                        </label>
                        <div class="relative">
                             <select id="empresa" name="empresa" required class="appearance-none block w-full px-4 py-3.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-clinica-base focus:border-transparent sm:text-sm transition bg-white text-gray-700">
                                <option value="" disabled selected>Seleccione su sede...</option>
                                <option value="CLINICA LA LUZ SAC">CLINICA LA LUZ SAC (Lima)</option>
                                <option value="CLINICA LA LUZ JAEN">CLINICA LA LUZ JAEN</option>
                                <option value="CLINICA LA LUZ TACNA">CLINICA LA LUZ TACNA</option>
                                <option value="CLINICA LA LUZ OFTALMOLOGIA - BREÑA">BREÑA (Oftalmología)</option>
                                <option value="INSTITUTO OFTALMOLOGICO LA LUZ">INSTITUTO OFTALMOLOGICO</option>
                                <option value="SELUCE">SELUCE</option>
                                <option value="ETEL MEDIC">ETEL MEDIC</option>
                                <option value="EMPRESA_PRUEBAS">PRUEBAS (Desarrollo)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-clinica-gray">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="nom_usuario" class="block text-sm font-bold text-clinica-gray">
                            Usuario
                        </label>
                        <div class="mt-1 relative">
                            <input id="nom_usuario" name="nom_usuario" type="text" required 
                                   class="appearance-none block w-full px-4 py-3.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-clinica-base focus:border-transparent sm:text-sm transition"
                                   placeholder="Ej: JPEREZ">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-bold text-clinica-gray">
                            Contraseña
                        </label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" required 
                                   class="appearance-none block w-full px-4 py-3.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-clinica-base focus:border-transparent sm:text-sm transition"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-clinica-base hover:bg-clinica-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-clinica-base transition transform hover:-translate-y-0.5">
                            Ingresar al Sistema
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-10 border-t border-gray-100 pt-6">
                <p class="text-xs text-center text-clinica-gray">
                    &copy; {{ date('Y') }} Clínica La Luz. Todos los derechos reservados.
                    <br>Departamento de Tecnología de la Información.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection