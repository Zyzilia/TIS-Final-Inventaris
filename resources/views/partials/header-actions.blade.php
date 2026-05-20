<div class="flex items-center gap-4 relative">
    <!-- Profile Avatar Dropdown -->
    <div class="relative">
        <div onclick="toggleProfileDropdown(this)" class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border border-gray-200 shadow-sm ml-2 cursor-pointer shrink-0">
            <img src="https://ui-avatars.com/api/?name=Admin&background=9A82EA&color=fff" class="profileImg w-full h-full object-cover" alt="Profile">
        </div>
        <div class="profileDropdown hidden absolute top-12 right-0 bg-white border border-gray-100 rounded-2xl shadow-lg w-56 z-[100] p-4 flex flex-col gap-3">
            <div class="border-b border-gray-50 pb-3 flex flex-col">
                <span class="dropdownUserName font-bold text-gray-800 text-sm text-left">Loading...</span>
                <span class="dropdownUserEmail text-gray-400 text-[10px] text-left">Loading...</span>
                <span class="dropdownUserRole mt-1 text-[9px] font-semibold text-accent bg-violet-50 px-2 py-0.5 rounded-full border border-violet-100 self-start">Admin</span>
            </div>
            <button onclick="logout()" class="w-full text-left text-xs font-semibold text-red-500 hover:text-red-600 transition flex items-center gap-2 py-1">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar / Logout
            </button>
        </div>
    </div>
</div>
