<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Petra Informatics Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'petra-orange': '#f7941d',
                        'petra-blue': '#1d3c74',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="h-screen flex">
    <div class="flex-1 flex flex-col justify-center bg-gray-50">
        <div class="max-w-md mx-auto w-full px-8">
            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-4 mb-4">
                    <img src="https://upload.wikimedia.org/wikipedia/id/thumb/4/4d/UK_PETRA_LOGO.svg/1200px-UK_PETRA_LOGO.svg.png" 
                         alt="Petra Logo" class="h-12">
                    <img src="https://petra.ac.id/img/logo-text.2e8a4502.png" 
                         alt="PCU Logo" class="h-12">
                </div>
                <h1 class="text-4xl font-bold mb-2 text-petra-blue">Welcome to Petra<br>Informatics Laboratory</h1>
                <p class="text-gray-600">Sign in with your Google account to access lab management system</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                    {{ session('info') }}
                </div>
            @endif

            <div class="space-y-4">
                <a href="{{ route('auth.google') }}" 
                   class="w-full bg-white border border-gray-300 text-gray-700 py-4 px-6 rounded-lg text-center font-medium hover:bg-gray-50 transition flex items-center justify-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Sign in with Google</span>
                </a>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        By signing in, you agree to use your Google account for authentication.<br>
                        Contact admin for role assignment and lab access.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 relative bg-cover bg-center" 
         style="background-image: url('https://informatics.petra.ac.id/wp-content/uploads/2023/07/GSP_7832.jpg')">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="absolute bottom-8 left-8 text-white">
            <h2 class="text-3xl font-bold mb-2">Modern Lab Management</h2>
            <p class="text-lg">Efficient equipment tracking and maintenance system</p>
        </div>
    </div>
</body>
</html>