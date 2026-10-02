<!-- App Js (Mandatory in All Pages) -->
@vite(['resources/js/app.js'])

@auth
<script>
// ══ Global Staff Notification Bell (All Pages) ══════════════════════════
(function() {
    const CSRF_T = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const APP_BASE = "{{ rtrim(url('/'), '/') }}";
    const seenIds = new Set();
    const ICONS = { bell: '🔔', task: '✅', warning: '⚠️', info: 'ℹ️' };

    async function fetchNotifs() {
        try {
            const res  = await fetch(`${APP_BASE}/admin/notifications/mine`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.success) return;

            const badge = document.getElementById('topbar-notif-badge');
            const list  = document.getElementById('topbar-notif-list');
            const count = document.getElementById('topbar-notif-count');
            const total = data.count;

            if (!badge || !list) return;

            if (total === 0) {
                badge.classList.add('hidden');
                list.innerHTML = '<p class="p-4 text-center text-[#B0ADA8] text-xs">لا توجد إشعارات جديدة</p>';
                return;
            }

            // Update badge
            badge.textContent = total > 9 ? '9+' : total;
            badge.classList.remove('hidden');
            if (count) count.textContent = `${total} إشعار جديد`;

            // Render list
            list.innerHTML = data.notifications.map(n => `
                <div class="flex items-start gap-3 px-4 py-3 hover:bg-[#FAF9F5] transition-all" id="tb-notif-${n.id}">
                    <span class="text-base flex-shrink-0">${ICONS[n.icon] ?? '🔔'}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-[#303334] leading-snug">${n.title}</p>
                        ${n.body ? `<p class="text-[11px] text-[#73777A] mt-0.5 line-clamp-2">${n.body}</p>` : ''}
                        <p class="text-[10px] text-[#B0ADA8] mt-1">من: ${n.sender}</p>
                    </div>
                    <button onclick="dismissTopbarNotif(${n.id})"
                            class="flex-shrink-0 size-5 rounded text-[#B0ADA8] hover:text-red-500 text-xs flex items-center justify-center">✕</button>
                </div>
            `).join('');

            // Play beep for new ones
            data.notifications.forEach(n => {
                if (seenIds.has(n.id)) return;
                seenIds.add(n.id);
                try {
                    const ctx  = new (window.AudioContext || window.webkitAudioContext)();
                    const osc  = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain); gain.connect(ctx.destination);
                    osc.frequency.value = 640; gain.gain.value = 0.12;
                    osc.start(); osc.stop(ctx.currentTime + 0.15);
                } catch(e) {}
            });
        } catch(e) {}
    }

    window.dismissTopbarNotif = async function(id) {
        try {
            await fetch(`${APP_BASE}/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_T, 'Accept': 'application/json' }
            });
        } catch(e) {}
        document.getElementById('tb-notif-' + id)?.remove();
        seenIds.add(id);
        fetchNotifs();
    };

    // Poll immediately then every 30s
    fetchNotifs();
    setInterval(fetchNotifs, 30000);
})();
</script>
@endauth