<button id="darkModeToggle"
class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-orange-50 dark:hover:bg-gray-700 transition flex-shrink-0 cursor-pointer text-gray-400 hover:text-brandOrange"
title="Toggle dark mode">
    <svg id="darkModeIcon"
         xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>

<div class="relative" id="adminNotifWrapper">
    <button id="adminNotifBtn" class="relative text-gray-400 hover:text-brandOrange transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        <span id="adminNotifBadge" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 hidden">0</span>
    </button>

    <div id="adminNotifDropdown" class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-[100]">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
            <button id="adminMarkAllRead" class="text-xs text-brandOrange hover:text-brandOrangeHover font-medium">Mark all read</button>
        </div>
        <div id="adminNotifList" class="max-h-80 overflow-y-auto">
            <div class="p-4 text-center text-sm text-gray-400">Loading...</div>
        </div>
    </div>
</div>

<script>
(function() {
    var btn = document.getElementById('adminNotifBtn');
    var dropdown = document.getElementById('adminNotifDropdown');
    var wrapper = document.getElementById('adminNotifWrapper');
    var list = document.getElementById('adminNotifList');
    var badge = document.getElementById('adminNotifBadge');
    var markAllBtn = document.getElementById('adminMarkAllRead');

    if (!btn || !dropdown) return;

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var isHidden = dropdown.classList.contains('hidden');
        if (isHidden) {
            fetchAdminNotifs();
        }
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') dropdown.classList.add('hidden');
    });

    function fetchAdminNotifs() {
        fetch('get_notifications.php')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) return;
                updateBadge(data.count);
                renderNotifs(data.notifications || []);
            })
            .catch(function() {});
    }

    function updateBadge(count) {
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function renderNotifs(notifs) {
        if (!list) return;
        if (!notifs.length) {
            list.innerHTML = '<div class="p-6 text-center text-sm text-gray-400">No notifications</div>';
            return;
        }
        var html = '';
        notifs.forEach(function(n) {
            var icon = getIcon(n.type);
            html += '<div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 cursor-pointer admin-notif-item" data-id="' + n.id + '" data-link="' + (n.link || '') + '">';
            html += '<span class="text-lg flex-shrink-0 mt-0.5">' + icon + '</span>';
            html += '<div class="flex-1 min-w-0">';
            html += '<p class="text-sm text-gray-700 leading-snug">' + escapeHtml(n.message) + '</p>';
            html += '<p class="text-xs text-gray-400 mt-1">' + n.time_ago + '</p>';
            html += '</div>';
            html += '</div>';
        });
        list.innerHTML = html;

        document.querySelectorAll('.admin-notif-item').forEach(function(el) {
            el.addEventListener('click', function() {
                var id = el.getAttribute('data-id');
                var link = el.getAttribute('data-link');
                if (id) {
                    fetch('get_notifications.php?action=mark_read&id=' + id);
                }
                if (link) window.location.href = link;
            });
        });
    }

    function getIcon(type) {
        switch(type) {
            case 'enrollment': return '\ud83d\udccb';
            case 'payment': return '\ud83d\udcb0';
            case 'student': return '\ud83d\udc64';
            case 'course': return '\ud83d\udcda';
            default: return '\ud83d\udd14';
        }
    }

    function escapeHtml(text) {
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('get_notifications.php?action=mark_all_read')
                .then(function() {
                    updateBadge(0);
                    list.innerHTML = '<div class="p-6 text-center text-sm text-gray-400">No notifications</div>';
                });
        });
    }

    fetchAdminNotifs();
    setInterval(fetchAdminNotifs, 15000);
})();

// Dark mode toggle
const darkModeBtn = document.getElementById('darkModeToggle');
const darkModePath = document.querySelector('#darkModeIcon path');
if (darkModeBtn && darkModePath) {
    darkModeBtn.addEventListener('click', function() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
        updateDarkModeIcon(isDark);
    });
    function updateDarkModeIcon(isDark) {
        if (isDark) {
            darkModePath.setAttribute('d', 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z');
            darkModeBtn.classList.add('text-brandOrange');
        } else {
            darkModePath.setAttribute('d', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z');
            darkModeBtn.classList.remove('text-brandOrange');
        }
    }
    updateDarkModeIcon(document.documentElement.classList.contains('dark'));
}
</script>
