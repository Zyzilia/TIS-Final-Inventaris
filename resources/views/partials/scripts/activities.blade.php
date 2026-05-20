async function loadActivities() {
    const activityList = document.getElementById('activityList');
    if (!activityList) return;
    
    try {
        const res = await axios.get('/api/activities');
        const logs = res.data.data;
        activityList.innerHTML = '';
        
        if (logs.length === 0) {
            activityList.innerHTML = '<p class="text-gray-400 text-xs italic py-4 text-center">Belum ada aktivitas</p>';
            return;
        }

        logs.forEach(act => {
            let badgeClass = 'text-green-500';
            if (act.action === 'Refund' || act.action === 'Delete') {
                badgeClass = 'text-red-500';
            } else if (act.action === 'Update' || act.action === 'Audit') {
                badgeClass = 'text-blue-500';
            }
            
            const avatarName = act.user_name || 'Admin';
            const iconHtml = getIconHtml(act.item_type || 'gpu');
            
            activityList.innerHTML += `
                <div class="flex items-center justify-between text-xs py-2 border-b border-gray-50 last:border-0 gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(avatarName)}&background=random" class="w-8 h-8 rounded-full shadow-sm shrink-0">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 truncate text-xs" title="${act.description}">${act.description}</p>
                            <p class="text-[10px] ${badgeClass} font-medium">${act.action} • <span class="text-gray-400 font-mono text-[10px]">${act.order_id || ''}</span></p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end shrink-0 text-right">
                        <div class="font-semibold text-gray-800 text-xs">${act.amount || ''}</div>
                        <div class="flex mt-1">
                            <div class="w-6 h-6 rounded-md bg-gray-50 flex justify-center items-center text-[10px] shadow-sm border border-gray-200 relative">
                                ${iconHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (err) {
        console.error(err);
        activityList.innerHTML = '<p class="text-red-400 text-xs italic py-4 text-center">Gagal memuat aktivitas</p>';
    }
}
