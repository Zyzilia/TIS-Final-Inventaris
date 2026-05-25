<div class="flex items-center gap-4 relative">
    <!-- Global Search -->
    <div class="hidden md:flex relative group w-64 lg:w-96">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
        <input type="text" placeholder="Search for items, transactions..." class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
    </div>

    <!-- Notifications -->
    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 transition-all relative">
        <i class="fa-solid fa-bell"></i>
        <span class="absolute top-2.5 right-3 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
    </button>

    <!-- Profile Avatar Dropdown -->
    <div class="relative">
        <div onclick="toggleProfileDropdown(this)" class="w-10 h-10 rounded-xl bg-white border border-gray-200 shadow-sm cursor-pointer shrink-0 overflow-hidden hover:border-primary-300 transition-all flex items-center justify-center">
            <img src="https://ui-avatars.com/api/?name=Admin&background=7c3aed&color=fff" class="profileImg w-full h-full object-cover" alt="Profile">
        </div>
        <div class="profileDropdown hidden absolute top-14 right-0 bg-white border border-gray-100 rounded-2xl shadow-xl w-64 z-[100] p-4 flex flex-col gap-3">
            <div class="border-b border-gray-100 pb-4 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-lg">
                    A
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="dropdownUserName font-bold text-gray-900 truncate">Loading...</span>
                    <span class="dropdownUserEmail text-gray-500 text-xs truncate">Loading...</span>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <button class="w-full text-left px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-xs"></i> Account Settings
                </button>
                <button class="w-full text-left px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-xs"></i> Security
                </button>
            </div>
            <div class="border-t border-gray-100 pt-2">
                <button onclick="logout()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i> Sign Out
                </button>
            </div>
        </div>
    </div>
</div>
