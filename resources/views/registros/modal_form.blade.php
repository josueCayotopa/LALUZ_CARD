<div id="modalRegistro" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto transform transition-all scale-100 font-sans">
        
        <div class="bg-[#B11A1A] px-6 py-4 flex justify-between items-center sticky top-0 z-20 shadow-md">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 tracking-wide">
                <i class="fa-regular fa-id-card"></i> Registro de Afiliado
            </h2>
            <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white text-2xl transition hover:scale-110">&times;</button>
        </div>

        <form action="{{ route('afiliados.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row justify-between items-center text-sm shadow-sm">
                <div class="flex items-center gap-2 mb-2 sm:mb-0">
                    <div class="w-8 h-8 rounded-full bg-[#fdf2f2] flex items-center justify-center text-[#B11A1A]">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                    <div>
                        <span class="block text-[#8B8889] font-bold text-xs uppercase tracking-wider">Fecha de Registro</span>
                        <span class="font-semibold text-gray-800">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-[#fdf2f2] flex items-center justify-center text-[#B11A1A]">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <span class="block text-[#8B8889] font-bold text-xs uppercase tracking-wider">Registrado por</span>
                        <span class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'Usuario Sistema' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-[#B11A1A] font-bold border-b border-gray-200 pb-2 mb-6 flex items-center gap-3 text-lg">
                    <span class="bg-[#fdf2f2] text-[#B11A1A] border border-red-100 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-sm">1</span>
                    Datos del Paciente
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-1.5 group-hover:text-[#B11A1A] transition-colors">DNI *</label>
                        <input type="text" name="Afiliado_DNI" maxlength="8" value="{{ old('Afiliado_DNI') }}" required
                               placeholder="Ingrese 8 dígitos"
                               class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-700 text-sm shadow-sm transition-all duration-200 
                                      hover:border-[#B11A1A] hover:bg-[#fffafa] hover:shadow-md
                                      focus:border-[#B11A1A] focus:ring-1 focus:ring-[#B11A1A] focus:outline-none 
                                      @error('Afiliado_DNI') border-red-500 @enderror">
                        @error('Afiliado_DNI') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-1.5 group-hover:text-[#B11A1A] transition-colors">Nombres Completos *</label>
                        <input type="text" name="Afiliado_Nombres" value="{{ old('Afiliado_Nombres') }}" required
                               placeholder="Nombres y Apellidos"
                               class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-700 text-sm shadow-sm transition-all duration-200 
                                      hover:border-[#B11A1A] hover:bg-[#fffafa] hover:shadow-md
                                      focus:border-[#B11A1A] focus:ring-1 focus:ring-[#B11A1A] focus:outline-none">
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-1.5 group-hover:text-[#B11A1A] transition-colors">Teléfono</label>
                        <input type="text" name="Afiliado_Telefono" value="{{ old('Afiliado_Telefono') }}"
                               placeholder="Número de contacto"
                               class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-700 text-sm shadow-sm transition-all duration-200 
                                      hover:border-[#B11A1A] hover:bg-[#fffafa] hover:shadow-md
                                      focus:border-[#B11A1A] focus:ring-1 focus:ring-[#B11A1A] focus:outline-none">
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-1.5 group-hover:text-[#B11A1A] transition-colors">Email</label>
                        <input type="email" name="Afiliado_Email" value="{{ old('Afiliado_Email') }}"
                               placeholder="correo@ejemplo.com"
                               class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-700 text-sm shadow-sm transition-all duration-200 
                                      hover:border-[#B11A1A] hover:bg-[#fffafa] hover:shadow-md
                                      focus:border-[#B11A1A] focus:ring-1 focus:ring-[#B11A1A] focus:outline-none">
                    </div>

                    <div class="md:col-span-2 group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-1.5 group-hover:text-[#B11A1A] transition-colors">Dirección</label>
                        <input type="text" name="Afiliado_Direccion" value="{{ old('Afiliado_Direccion') }}"
                               placeholder="Dirección completa de domicilio"
                               class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-700 text-sm shadow-sm transition-all duration-200 
                                      hover:border-[#B11A1A] hover:bg-[#fffafa] hover:shadow-md
                                      focus:border-[#B11A1A] focus:ring-1 focus:ring-[#B11A1A] focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 class="text-[#8B8889] font-bold border-b border-gray-200 pb-2 mb-4 flex items-center gap-3 text-lg">
                    <span class="bg-white text-[#8B8889] border border-gray-300 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-sm">2</span>
                    Datos del Apoderado <span class="text-xs font-normal text-gray-400 ml-auto bg-white px-2 py-1 rounded border border-gray-200">Opcional</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="group">
                        <label class="block text-xs font-bold text-[#8B8889] uppercase mb-1.5">Parentesco</label>
                        <select name="Apoderado_Parentesco" 
                                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm transition-all 
                                       hover:border-[#8B8889] focus:border-[#8B8889] focus:ring-1 focus:ring-[#8B8889] focus:outline-none">
                            <option value="">Seleccione...</option>
                            <option value="PADRE" {{ old('Apoderado_Parentesco') == 'PADRE' ? 'selected' : '' }}>Padre</option>
                            <option value="MADRE" {{ old('Apoderado_Parentesco') == 'MADRE' ? 'selected' : '' }}>Madre</option>
                            <option value="HIJO" {{ old('Apoderado_Parentesco') == 'HIJO' ? 'selected' : '' }}>Hijo/a</option>
                            <option value="ESPOSO" {{ old('Apoderado_Parentesco') == 'ESPOSO' ? 'selected' : '' }}>Esposo/a</option>
                            <option value="OTRO" {{ old('Apoderado_Parentesco') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-bold text-[#8B8889] uppercase mb-1.5">Nombres Apoderado</label>
                        <input type="text" name="Apoderado_Nombres" value="{{ old('Apoderado_Nombres') }}" 
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm transition-all hover:border-[#8B8889] focus:border-[#8B8889] focus:ring-1 focus:ring-[#8B8889] focus:outline-none">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-bold text-[#8B8889] uppercase mb-1.5">DNI</label>
                        <input type="text" name="Apoderado_DNI" maxlength="8" value="{{ old('Apoderado_DNI') }}" 
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm transition-all hover:border-[#8B8889] focus:border-[#8B8889] focus:ring-1 focus:ring-[#8B8889] focus:outline-none">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-bold text-[#8B8889] uppercase mb-1.5">Teléfono</label>
                        <input type="text" name="Apoderado_Telefono" value="{{ old('Apoderado_Telefono') }}" 
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm transition-all hover:border-[#8B8889] focus:border-[#8B8889] focus:ring-1 focus:ring-[#8B8889] focus:outline-none">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-[#B11A1A] font-bold border-b border-gray-200 pb-2 mb-4 flex items-center gap-3 text-lg">
                    <span class="bg-[#fdf2f2] text-[#B11A1A] border border-red-100 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-sm">3</span>
                    Documentación
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    <div class="group">
                        <label class="block text-sm font-bold text-[#8B8889] mb-2 group-hover:text-[#B11A1A] transition-colors">Contrato Adjunto</label>
                        <input type="file" name="Contrato_adjunto" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-[#fdf2f2] file:text-[#B11A1A]
                            hover:file:bg-red-100 file:transition-colors cursor-pointer border border-gray-300 rounded-md">
                    </div>
                    <div class="flex items-center pb-2">
                        <label class="flex items-center space-x-3 cursor-pointer p-3 rounded-md border border-transparent hover:bg-gray-50 hover:border-gray-200 w-full transition-all">
                            <input type="checkbox" name="Tiene_Firma_Huella" value="1" {{ old('Tiene_Firma_Huella') ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#B11A1A] border-gray-300 rounded focus:ring-[#B11A1A]">
                            <span class="text-[#8B8889] font-bold text-sm">¿Tiene Firma y Huella digital?</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition shadow-sm">
                    Cancelar
                </button>
                <button type="submit" class="px-8 py-2.5 rounded-lg bg-[#B11A1A] text-white font-bold shadow-md hover:bg-[#8f1515] hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Guardar Registro
                </button>
            </div>
        </form>
    </div>
</div>