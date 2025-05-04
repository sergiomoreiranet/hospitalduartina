<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Gestão Operacional - Hospital Santa Luzia</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('imagens/favicon-16x16.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                background: radial-gradient(circle at center, #006D87 0%, #004258 100%);
                font-family: 'Figtree', sans-serif;
            }
            .login-container {
                width: 100%;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .logo-container {
                margin-bottom: 2rem;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .hospital-logo {
                width: auto;
                height: 80px;
                margin-bottom: 1.5rem;
                display: block;
            }
            .system-title {
                color: white;
                font-size: 1.75rem;
                font-weight: 600;
                margin: 0;
                text-align: center;
                letter-spacing: 0.5px;
            }
            .login-card {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                width: 100%;
                max-width: 400px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .login-subtitle {
                text-align: center;
                color: #666;
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .input-group {
                margin-bottom: 1.5rem;
            }
            .input-label {
                display: flex;
                align-items: center;
                margin-bottom: 0.5rem;
                color: #4a5568;
                font-size: 0.9rem;
                font-weight: 500;
            }
            .input-label svg {
                width: 16px;
                height: 16px;
                margin-right: 0.5rem;
                color: #006D87;
            }
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                background-color: #f8fafc;
                font-size: 0.95rem;
                transition: all 0.3s;
            }
            .form-input:focus {
                outline: none;
                border-color: #006D87;
                background-color: white;
            }
            .form-input::placeholder {
                color: #94a3b8;
            }
            .remember-group {
                margin: 1rem 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #666;
            }
            .remember-checkbox {
                width: 1rem;
                height: 1rem;
            }
            .login-button {
                width: 100%;
                padding: 0.75rem;
                background: linear-gradient(to right, #006D87, #004258);
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s;
            }
            .login-button:hover {
                background: linear-gradient(to right, #004258, #003246);
            }
            .input-icon {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                cursor: pointer;
            }
            .input-wrapper {
                position: relative;
            }
            .error-message {
                color: #e53e3e;
                font-size: 0.95rem;
                margin-bottom: 1rem;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo-container">
                <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="hospital-logo">
                <h1 class="system-title">Área Restrita</h1>
            </div>

            <div class="login-card">
                <p class="login-subtitle">Digite seu CPF e sua senha</p>

                @if($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="text" 
                                   class="form-input" 
                                   name="cpf" 
                                   id="cpf" 
                                   placeholder="CPF"
                                   value="{{ old('cpf') }}"
                                   required 
                                   autofocus>
                            <span class="input-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M17 6v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M13 10H7m3-8v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="password" 
                                   class="form-input" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Senha"
                                   required>
                            <span class="input-icon" onclick="togglePassword()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M2.5 9.17a.5.5 0 00-.393.809C3.318 11.873 6.316 15 10 15c3.684 0 6.682-3.127 7.893-5.021a.5.5 0 00-.393-.809M10 12a2 2 0 100-4 2 2 0 000 4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="remember-group">
                        <input type="checkbox" 
                               class="remember-checkbox"
                               name="remember" 
                               id="remember" 
                               disabled>
                        <label for="remember">Lembrar senha desativado</label>
                    </div>

                    <button type="submit" class="login-button">
                        Acessar
                    </button>
                </form>
            </div>
        </div>

        <script>
            // Máscara do CPF
            document.getElementById('cpf').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                if (value.length > 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
                } else if (value.length > 3) {
                    value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
                }
                e.target.value = value;
            });
            // Mostrar/ocultar senha
            function togglePassword() {
                var input = document.getElementById('password');
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        </script>
    </body>
</html>
