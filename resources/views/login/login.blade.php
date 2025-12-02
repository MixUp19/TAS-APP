<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body id="main-body"
    class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-500">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Te Acerco Salud</h1>
            <p id="subtitle" class="text-gray-600 mt-2">Inicia sesión para continuar</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form id="auth-form" action="{{ route('iniciar.sesion') }}" method="POST" class="space-y-6">
                @csrf

                <div id="tipo-usuario-section">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Tipo de Usuario
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="setTipoUsuario('paciente')" id="btn-paciente"
                            class="tipo-usuario-btn active flex items-center justify-center py-3 px-4 border-2 border-indigo-600 bg-indigo-600 text-white rounded-lg font-medium transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Paciente
                        </button>
                        <button type="button" onclick="setTipoUsuario('admin')" id="btn-admin"
                            class="tipo-usuario-btn flex items-center justify-center py-3 px-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition duration-150 ease-in-out hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Administrador
                        </button>
                    </div>
                    <input type="hidden" name="tipo_usuario" id="tipo_usuario" value="paciente">
                </div>

                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                </path>
                            </svg>
                        </div>
                        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required
                            class="theme-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm @error('correo') border-red-300 @enderror"
                            placeholder="tu@ejemplo.com" autocomplete="email">
                    </div>
                    @error('correo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                        NIP (Contraseña)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input type="password" id="nip" name="nip" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                            required
                            class="theme-input block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm @error('nip') border-red-300 @enderror"
                            placeholder="••••••••" autocomplete="current-password">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="eye-icon" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">
                        8 Caracteres, una mayúscula, una minúscula y un número
                    </p>
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button id="submit-btn" type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <svg id="submit-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span id="submit-text">Iniciar Sesión</span>
                    </button>
                </div>
            </form>

            <div id="separator-section" class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span id="separator-text" class="px-2 bg-white text-gray-500">¿Nuevo aquí?</span>
                    </div>
                </div>
            </div>

            <div id="toggle-mode-section" class="mt-6">
                <button id="toggle-mode-btn" type="button" onclick="toggleMode()"
                    class="w-full flex justify-center items-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg id="toggle-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span id="toggle-text">Crear una cuenta</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentMode = 'login';
        let tipoUsuario = 'paciente';

        function setTipoUsuario(tipo) {
            tipoUsuario = tipo;
            document.getElementById('tipo_usuario').value = tipo;

            const btnPaciente = document.getElementById('btn-paciente');
            const btnAdmin = document.getElementById('btn-admin');
            const body = document.getElementById('main-body');
            const inputs = document.querySelectorAll('.theme-input');
            const submitBtn = document.getElementById('submit-btn');

            if (tipo === 'paciente') {

                btnPaciente.className = 'tipo-usuario-btn active flex items-center justify-center py-3 px-4 border-2 border-indigo-600 bg-indigo-600 text-white rounded-lg font-medium transition duration-150 ease-in-out';
                btnAdmin.className = 'tipo-usuario-btn flex items-center justify-center py-3 px-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition duration-150 ease-in-out hover:bg-gray-50';

                body.classList.remove('from-red-50', 'to-red-100');
                body.classList.add('from-blue-50', 'to-indigo-100');

                inputs.forEach(input => {
                    input.classList.remove('focus:ring-red-500', 'focus:border-red-500');
                    input.classList.add('focus:ring-indigo-500', 'focus:border-indigo-500');
                });

                submitBtn.classList.remove('bg-red-600', 'hover:bg-red-700', 'focus:ring-red-500');
                submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'focus:ring-indigo-500');

            } else {

                btnAdmin.className = 'tipo-usuario-btn active flex items-center justify-center py-3 px-4 border-2 border-red-600 bg-red-600 text-white rounded-lg font-medium transition duration-150 ease-in-out';
                btnPaciente.className = 'tipo-usuario-btn flex items-center justify-center py-3 px-4 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition duration-150 ease-in-out hover:bg-gray-50';

                body.classList.remove('from-blue-50', 'to-indigo-100');
                body.classList.add('from-red-50', 'to-red-100');

                inputs.forEach(input => {
                    input.classList.remove('focus:ring-indigo-500', 'focus:border-indigo-500');
                    input.classList.add('focus:ring-red-500', 'focus:border-red-500');
                });

                submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'focus:ring-indigo-500');
                submitBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'focus:ring-red-500');
            }


            updateRegisterButtonVisibility();
        }

        function updateRegisterButtonVisibility() {
            const toggleModeSection = document.getElementById('toggle-mode-section');
            const separator = document.getElementById('separator-section');

            if (currentMode === 'login' && tipoUsuario === 'paciente') {
                toggleModeSection.style.display = 'block';
                separator.style.display = 'block';
            } else if (currentMode === 'register') {
                toggleModeSection.style.display = 'block';
                separator.style.display = 'block';
            } else {
                toggleModeSection.style.display = 'none';
                separator.style.display = 'none';
            }
        }

        function togglePassword() {
            const nipInput = document.getElementById('nip');
            const eyeIcon = document.getElementById('eye-icon');

            if (nipInput.type === 'password') {
                nipInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                nipInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        function toggleMode() {
            const form = document.getElementById('auth-form');
            const subtitle = document.getElementById('subtitle');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitIcon = document.getElementById('submit-icon');
            const toggleText = document.getElementById('toggle-text');
            const toggleIcon = document.getElementById('toggle-icon');
            const separatorText = document.getElementById('separator-text');
            const tipoUsuarioSection = document.getElementById('tipo-usuario-section');

            if (currentMode === 'login') {
                currentMode = 'register';
                form.action = "{{ route('registrar.usuario') }}";
                subtitle.textContent = 'Crea tu cuenta para comenzar';
                submitText.textContent = 'Crear Cuenta';
                separatorText.textContent = '¿Ya tienes cuenta?';
                toggleText.textContent = 'Iniciar Sesión';

                tipoUsuarioSection.style.display = 'none';

                submitIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                `;

                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                `;

            } else {
                currentMode = 'login';
                form.action = "{{ route('iniciar.sesion') }}";
                subtitle.textContent = 'Inicia sesión para continuar';
                submitText.textContent = 'Iniciar Sesión';
                separatorText.textContent = '¿Nuevo aquí?';
                toggleText.textContent = 'Crear una cuenta';

                tipoUsuarioSection.style.display = 'block';

                submitIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                `;

                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                `;
            }

            updateRegisterButtonVisibility();

            document.getElementById('correo').value = '';
            document.getElementById('nip').value = '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateRegisterButtonVisibility();
        });
    </script>
</body>

</html>