<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PC Parts Inventory Management')</title>
    
    <!-- Custom Favicon to match the app logo -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Ccircle cx='256' cy='256' r='256' fill='%231B1C22'/%3E%3Cg transform='translate(60,60) scale(0.8)'%3E%3Cpath fill='%23ffffff' d='M256 48c0-13.3-10.7-24-24-24s-24 10.7-24 24V195.4l-127.7-73.7c-11.5-6.6-26.2-2.7-32.8 8.8s-2.7 26.2 8.8 32.8L184 237.1 56.3 310.8c-11.5 6.6-15.5 21.3-8.8 32.8s21.3 15.5 32.8 8.8L208 278.6V426c0 13.3 10.7 24 24 24s24-10.7 24-24V278.6l127.7 73.7c11.5 6.6 26.2 2.7 32.8-8.8s2.7-26.2-8.8-32.8L280 237.1l127.7-73.7c11.5-6.6 15.5-21.3 8.8-32.8s-21.3-15.5-32.8-8.8L256 195.4V48z'/%3E%3C/g%3E%3C/svg%3E">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        accent: '#8b5cf6',
                        bgmain: '#f9fafb',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F4F5F7; height: 100vh; margin: 0; overflow: hidden; }
        .glass-panel { background: white; display: flex; overflow: hidden; width: 100vw; height: 100vh; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        #chartjs-tooltip { opacity: 1; position: absolute; background: rgba(0, 0, 0, 0.8); color: white; border-radius: 8px; pointer-events: none; transform: translate(-50%, 0); transition: all .1s ease; font-size: 12px; padding: 8px; z-index: 10; }
        
        /* Custom UI classes */
        .card-standard {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #f3f4f6;
        }
    </style>
    @yield('styles')
</head>
<body class="@yield('body-class', 'text-gray-900')">

    <div class="main-container">
        @yield('content')
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let bgColor = 'bg-darknav';
            let icon = 'fa-circle-check text-accent';
            
            if (type === 'error') {
                bgColor = 'bg-red-500';
                icon = 'fa-circle-xmark text-white';
            } else if (type === 'warning') {
                bgColor = 'bg-amber-500';
                icon = 'fa-triangle-exclamation text-white';
            }
            
            toast.className = `transform transition-all duration-300 ease-out translate-x-full opacity-0 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] text-white ${bgColor} pointer-events-auto min-w-[250px]`;
            toast.innerHTML = `
                <i class="fa-solid ${icon} text-lg"></i>
                <span class="text-sm font-medium tracking-wide flex-1">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 text-white/60 hover:text-white transition"><i class="fa-solid fa-xmark"></i></button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                });
            });
            
            // Remove after 3.5s
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
        
        // Override default alert
        window.alert = function(message) {
            const isError = message.toLowerCase().includes('gagal') || 
                            message.toLowerCase().includes('failed') || 
                            message.toLowerCase().includes('error') ||
                            message.toLowerCase().includes('please');
            showToast(message, isError ? 'error' : 'success');
        };
    </script>

    @yield('scripts')
</body>
</html>
