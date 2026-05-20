@extends('layouts.app')

@section('title', 'Login - Inventory Management')

@section('body-class', 'h-screen w-full flex items-center justify-center p-4')

@section('styles')
    <style>
        body { background-color: #E5E7EB; }
    </style>
@endsection

@section('content')
    <div class="bg-white p-10 rounded-[2rem] shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-darknav text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg">
                <i class="fa-solid fa-asterisk"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Please enter your details to sign in.</p>
        </div>

        <form id="loginForm" class="flex flex-col gap-5">
            <div id="errorAlert" class="hidden bg-red-100 text-red-600 p-3 rounded-xl text-sm font-medium text-center"></div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition" placeholder="admin@example.com">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition" placeholder="••••••••">
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-accent text-white font-semibold py-3 rounded-xl shadow-md hover:bg-violet-600 transition flex justify-center items-center gap-2 mt-2">
                Sign In
            </button>
        </form>
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

            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Signing in...';
            btn.disabled = true;
            alertBox.classList.add('hidden');

            try {
                const response = await axios.post('/api/auth/login', { email, password });
                const token = response.data.data.access_token;
                localStorage.setItem('jwt_token', token);
                localStorage.setItem('user_data', JSON.stringify(response.data.data.user));
                window.location.href = '/dashboard';
            } catch (error) {
                alertBox.textContent = error.response?.data?.message || 'Failed to login. Please check your credentials.';
                alertBox.classList.remove('hidden');
                btn.innerHTML = 'Sign In';
                btn.disabled = false;
            }
        });
    </script>
@endsection
