<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clínica La Luz') - Gestión de Afiliados</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       tailwind.config = {
        theme: {
            extend: {
                colors: {
                    // Paleta Clínica La Luz (Basada en tonos Cian/Turquesa corporativos)
                 clinica: {
                        base: '#B11A1A',     // CLL GRANATE (Principal)
                        hover: '#8f1515',    // Un tono más oscuro para hovers
                        gray: '#8B8889',     // CLL GRIS (Texto secundario/Bordes)
                        light: '#fdf2f2',    // Fondo muy suave rojizo
                        text: '#524f50'      // Gris oscuro para lectura
                    }
                },
                fontFamily: {
                   sans: ['Inter', 'system-ui', 'sans-serif'], // Opcional: Una fuente más moderna
                }
            }
        }
    }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">

    @auth
    <nav class="bg-white shadow-md border-b-4 border-clinica-500 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-clinica-500 text-white p-1.5 rounded-lg">
                        <i class="fa-solid fa-hospital-user text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-clinica-900 leading-tight">LUZCARD</h1>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Sistema de Afiliados</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600 hidden md:block">
                        Hola, <span class="font-bold text-clinica-800">{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Cerrar Sesión">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="py-6">
        @yield('content')
    </main>

    <footer class="text-center py-4 text-gray-400 text-xs">
        &copy; {{ date('Y') }} Clínica La Luz - Departamento de TI
    </footer>

    @stack('scripts')
</body>
</html>