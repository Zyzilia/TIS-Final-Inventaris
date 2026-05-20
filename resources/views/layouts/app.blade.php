<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PC Parts Inventory Management')</title>
    
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
                        darknav: '#1B1C22',
                        darkcard: '#1F2128',
                        accent: '#9A82EA',
                        accentlight: '#D3C6F9',
                        bgmain: '#F4F5F7',
                        textgray: '#8C8E9B'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #E6E8EE; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 20px;}
        .glass-panel { background: white; border-radius: 2rem; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: 8px solid white; display: flex; overflow: hidden; width: 100%; max-width: 1400px; height: 95vh; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        #chartjs-tooltip { opacity: 1; position: absolute; background: rgba(0, 0, 0, 0.8); color: white; border-radius: 8px; pointer-events: none; transform: translate(-50%, 0); transition: all .1s ease; font-size: 12px; padding: 8px; z-index: 10; }
    </style>
    @yield('styles')
</head>
<body class="@yield('body-class', '')">

    @yield('content')

    @yield('scripts')
</body>
</html>
