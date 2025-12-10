<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Javadwipa BMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        /* Full screen container dengan background image */
        .login-container {
            display: flex;
            height: 100vh;
            position: relative;
            background-image: url('{{ asset('assets/media/backgrounds/javadwipa-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Overlay gelap (opsional untuk readability) */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(51, 78, 172, 0.3) 0%, rgba(51, 78, 172, 0.1) 50%, rgba(255, 255, 255, 0.9) 100%);
            z-index: 1;
        }

        /* Form area di kanan */
        .login-right {
            position: relative;
            margin-left: auto;
            width: 45%;
            min-width: 450px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            z-index: 10;
        }

        .login-form-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 50px 45px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15),
                        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            position: relative;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #334EAC;
            margin-bottom: 28px;
            line-height: 1.3;
        }

        .login-title span {
            font-weight: 400;
            display: block;
            color: #6B7280;
            font-size: 16px;
            margin-top: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px 13px 48px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
            color: #1F2937;
        }

        .form-input:focus {
            outline: none;
            border-color: #334EAC;
            background: white;
            box-shadow: 0 0 0 3px rgba(51, 78, 172, 0.1);
        }

        .form-input::placeholder {
            color: #D1D5DB;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 4px;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #334EAC;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 6px;
            accent-color: #334EAC;
        }

        .remember-me label {
            font-size: 14px;
            color: #6B7280;
            cursor: pointer;
            user-select: none;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background: #334EAC;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .login-button:hover {
            background: #2a3f8f;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(51, 78, 172, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .error-message {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* RESPONSIVE */

        /* Large Desktop (1920px+) */
        @media (min-width: 1920px) {
            .login-right {
                width: 40%;
            }

            .login-form-container {
                max-width: 520px;
                padding: 60px 50px;
            }

            .login-title {
                font-size: 32px;
            }
        }

        /* Standard Laptop (1366px - 1919px) */
        @media (min-width: 1367px) and (max-width: 1919px) {
            .login-right {
                width: 45%;
                min-width: 420px;
            }

            .login-form-container {
                max-width: 460px;
                padding: 50px 45px;
            }
        }

        /* Small Laptop (1024px - 1366px) */
        @media (min-width: 1024px) and (max-width: 1366px) {
            .login-right {
                width: 48%;
                min-width: 400px;
                padding: 30px;
            }

            .login-form-container {
                max-width: 420px;
                padding: 40px 35px;
            }

            .login-title {
                font-size: 24px;
            }

            .form-input {
                padding: 12px 14px 12px 44px;
                font-size: 14px;
            }
        }

        /* Tablet (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .login-container::before {
                background: rgba(51, 78, 172, 0.85);
            }

            .login-right {
                width: 100%;
                min-width: auto;
                max-width: 500px;
                margin: 0 auto;
            }

            .login-form-container {
                background: rgba(255, 255, 255, 0.95);
            }
        }

        /* Mobile (< 768px) */
        @media (max-width: 767px) {
            .login-container {
                background-position: center;
            }

            .login-container::before {
                background: rgba(51, 78, 172, 0.9);
            }

            .login-right {
                width: 100%;
                min-width: auto;
                padding: 20px;
            }

            .login-form-container {
                background: rgba(255, 255, 255, 0.98);
                padding: 40px 30px;
                border-radius: 24px;
                max-width: 100%;
            }

            .login-title {
                font-size: 26px;
                margin-bottom: 24px;
            }

            .login-title span {
                font-size: 15px;
            }

            .form-input {
                padding: 12px 14px 12px 44px;
                font-size: 14px;
            }

            .login-button {
                padding: 13px;
            }
        }

        @media (max-width: 480px) {
            .login-form-container {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 24px;
            }

            .form-label {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Form di kanan dengan background transparan -->
        <div class="login-right">
            <div class="login-form-container">
                <h1 class="login-title">
                    Log In
                    <span>to your account to track your building</span>
                </h1>

                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Username or Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 5.83333L9.16667 10L15.8333 5.83333M3.33333 14.1667H16.6667C17.5871 14.1667 18.3333 13.4205 18.3333 12.5V5C18.3333 4.07953 17.5871 3.33333 16.6667 3.33333H3.33333C2.41286 3.33333 1.66667 4.07953 1.66667 5V12.5C1.66667 13.4205 2.41286 14.1667 3.33333 14.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input type="text" id="email" name="email" class="form-input" placeholder="Username or Email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.83333 9.16667V5.83333C5.83333 3.53215 7.69881 1.66667 10 1.66667C12.3012 1.66667 14.1667 3.53215 14.1667 5.83333V9.16667M4.16667 9.16667H15.8333C16.7538 9.16667 17.5 9.91286 17.5 10.8333V16.6667C17.5 17.5871 16.7538 18.3333 15.8333 18.3333H4.16667C3.24619 18.3333 2.5 17.5871 2.5 16.6667V10.8333C2.5 9.91286 3.24619 9.16667 4.16667 9.16667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg id="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 5C5.83333 5 2.5 10 2.5 10C2.5 10 5.83333 15 10 15C14.1667 15 17.5 10 17.5 10C17.5 10 14.1667 5 10 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="login-button">Log In</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>
