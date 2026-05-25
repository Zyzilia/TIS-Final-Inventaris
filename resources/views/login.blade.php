@extends('layouts.app')

@section('title', 'Sign In - Inventaris System')

@section('content')
    <div class="flex-1 flex items-center justify-center p-6 bg-primary-50/50">
        <div class="card-standard p-10 w-full max-w-[440px] shadow-2xl bg-white rounded-[2.5rem] border-primary-100/50">
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-primary-900 text-white rounded-[2rem] flex items-center justify-center text-4xl mx-auto mb-6 shadow-2xl shadow-primary-900/30">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">System Login</h1>
                <p class="text-gray-500 font-medium mt-2">Inventory Management Access Panel</p>
            </div>

            <form id="loginForm" class="flex flex-col gap-6">
                <div id="errorAlert" class="hidden bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold text-center border border-red-100 animate-pulse"></div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Authorized Email</label>
                    <div class="relative group">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                        <input type="email" id="email" required 
                            class="w-full pl-11 pr-4 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-semibold text-gray-900" 
                            placeholder="admin@example.com">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Access Password</label>
                    <div class="relative group">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                        <input type="password" id="password" required 
                            class="w-full pl-11 pr-4 py-4 rounded-2xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-semibold text-gray-900" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-xs font-bold text-gray-500 group-hover:text-gray-700 transition-colors">Remember device</span>
                    </label>
                    <a href="#" class="text-xs font-bold text-primary-600 hover:text-primary-700 transition-colors">Forgot key?</a>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-primary-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-primary-900/20 hover:bg-primary-800 transition-all flex justify-center items-center gap-3 mt-2 text-sm tracking-widest">
                    AUTHENTICATE
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                    Enterprise Solutions &copy; 2026
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('errorAlert');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-lg"></i> VALIDATING...';
            btn.disabled = true;
            alertBox.classList.add('hidden');

            try {
                const response = await axios.post('/api/auth/login', { email, password });
                const token = response.data.data.access_token;
                localStorage.setItem('jwt_token', token);
                localStorage.setItem('user_data', JSON.stringify(response.data.data.user));
                window.location.href = '/dashboard';
            } catch (error) {
                alertBox.textContent = error.response?.data?.message || 'Authentication failed. Please check your credentials.';
                alertBox.classList.remove('hidden');
                btn.innerHTML = 'AUTHENTICATE';
                btn.disabled = false;
            }
        });
    </script>
@endsection
