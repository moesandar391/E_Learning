<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Edu - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandOrange: '#FF8A00',
                        brandOrangeHover: '#E07A00',
                        brandOchre: '#A87034',
                        brandTextGray: '#566473',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        spin: 'spin 2s linear infinite',
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-[#F8F9FA] font-sans antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden pt-20">

    <header class="w-full bg-white border-b border-gray-100 fixed top-0 left-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <img src="../assets/Logo 3.png" alt="Logo" class="h-10 w-auto">
                <span class="text-2xl font-serif font-bold text-brandOchre tracking-tight">
                    Access Edu
                </span>
            </div>

            <nav class="hidden md:flex items-center space-x-12 font-medium text-brandTextGray">

    <a href="../users/index.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        Home
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>

    <div class="relative group">
        <a href="../users/courses.php" class="relative flex items-center py-2 hover:text-brandOchre transition-colors duration-300">
            Courses
            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
            <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
        </a>

        <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
            <a href="../users/courses.php?filter=All" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">All Courses</a>
            <a href="../users/courses.php?filter=Cambridge" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">Cambridge</a>
            <a href="../users/courses.php?filter=English%20Grammar" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">English Grammar</a>
            <a href="../users/courses.php?filter=Writing%20Course" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">Writing Course</a>
            <a href="../users/courses.php?filter=General%20English" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">General English</a>
            <a href="../users/courses.php?filter=English%20for%20Kids" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">English for Kids</a>
        </div>
    </div>

    <a href="../users/about.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        About
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>

    <a href="../users/contact.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        Contact
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>

</nav>
            <div class="group flex items-center rounded-full bg-[#F1F3F5] border border-brandOrange transition-all duration-300">
    
    <input 
        type="text" 
        placeholder="Search courses..." 
        class="w-0 bg-transparent py-1.5 px-0 text-sm text-slate-700 outline-none transition-all duration-300 group-hover:w-56 group-hover:pl-4"
    >

    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full bg-brandOrange text-white transition-colors duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </a>
</div>

            <div class="flex items-center space-x-4">
               <div class="hidden lg:block">
    <button
        onclick="window.location.href='../auth/register.php'"
        class="group relative overflow-hidden rounded-full border-2 border-brandOrange bg-white px-6 py-2 font-semibold text-brandOrange transition-all duration-300 hover:shadow-lg">
        <span
            class="absolute left-[calc(-100%-1.2rem)] top-0 h-full w-[calc(100%+1.2rem)] bg-brandOrange transition-transform duration-300 [clip-path:polygon(0_0,calc(100%-1.2rem)_0,100%_50%,calc(100%-1.2rem)_100%,0_100%)] group-hover:translate-x-full">
        </span>

        <span class="relative z-10 transition-colors duration-300 group-hover:text-white">
            Get Started
        </span>
    </button>
</div>
                <?php 
                $header_profile_img = null;
                if (isset($_SESSION['user_id'])) {
                    require_once __DIR__ . '/../config/db.php';
                    require_once __DIR__ . '/notification_helper.php';
                    $q = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
                    $q->bind_param("i", $_SESSION['user_id']);
                    $q->execute();
                    $row = $q->get_result()->fetch_assoc();
                    $header_profile_img = $row ? ($row['profile_image'] ?? null) : null;
                    $notif_count = get_unread_notification_count($_SESSION['user_id']);
                }

                if (isset($_SESSION['username'])): 
                    $initial = strtoupper(substr($_SESSION['username'], 0, 1));
                ?>                    
                    <div class="relative" id="notifWrapper">
                        <button id="notifBtn" class="relative text-slate-600 hover:text-brandOchre transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span id="notifBadge" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 <?php echo $notif_count > 0 ? '' : 'hidden'; ?>"><?php echo $notif_count; ?></span>
                        </button>

                        <div id="notifDropdown" class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-[100] origin-top-right">
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                <button id="markAllReadBtn" class="text-xs text-brandOrange hover:text-brandOrangeHover font-medium">Mark all read</button>
                            </div>
                            <div id="notifList" class="max-h-80 overflow-y-auto">
                                <div class="p-4 text-center text-sm text-gray-400">Loading...</div>
                            </div>
                            <a href="notifications.php" class="block px-4 py-2.5 text-center text-sm text-brandOrange font-medium border-t border-gray-100 hover:bg-orange-50 transition rounded-b-xl">View All Notifications</a>
                        </div>
                    </div>

                    <div class="relative" id="profileWrapper">
                        <button id="profileBtn" class="w-10 h-10 rounded-full bg-brandOrange flex items-center justify-center text-white font-bold text-2xl border border-gray-200 overflow-hidden">
                            <?php if ($header_profile_img && file_exists(__DIR__ . '/../users/' . $header_profile_img)): ?>
                                <img src="../users/<?php echo htmlspecialchars($header_profile_img); ?>" alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-sm font-bold"><?php echo htmlspecialchars($initial); ?></span>
                            <?php endif; ?>
                        </button>

                        <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-44 bg-[#0B1120] rounded-xl shadow-xl border border-gray-700 py-1.5 z-[100]">
                            
                            <a href="profile.php" class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-[#161e33] transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                My Profile
                            </a>

                            <hr class="border-gray-700 my-2">

                            <a href="../auth/logout.php" class="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-[#161e33] transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </a>
                        </div>
                    </div>

                        <?php else: ?>
                    <div class="relative inline-block p-[2px] rounded-full overflow-hidden">
    
    <div class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,#00f7ff_0%,#7c3aed_50%,#ec4899_100%)]"></div>

    <a href="../auth/login.php" class="relative flex items-center justify-center px-6 py-1.5 bg-black text-brandOrange font-semibold rounded-full hover:bg-brandOrange hover:text-white transition text-sm">
        Login
    </a>
</div>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <script>
    (function() {
        var profileBtn = document.getElementById('profileBtn');
        var profileMenu = document.getElementById('profileMenu');
        var profileWrapper = document.getElementById('profileWrapper');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', function(e) {
            if (profileWrapper && !profileWrapper.contains(e.target)) {
                if (profileMenu) profileMenu.classList.add('hidden');
            }
            if (notifWrapper && !notifWrapper.contains(e.target)) {
                if (notifDropdown) notifDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (profileMenu) profileMenu.classList.add('hidden');
                if (notifDropdown) notifDropdown.classList.add('hidden');
            }
        });

        var notifBtn = document.getElementById('notifBtn');
        var notifDropdown = document.getElementById('notifDropdown');
        var notifWrapper = document.getElementById('notifWrapper');
        var notifList = document.getElementById('notifList');
        var notifBadge = document.getElementById('notifBadge');
        var markAllReadBtn = document.getElementById('markAllReadBtn');

        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var isHidden = notifDropdown.classList.contains('hidden');
                notifDropdown.classList.toggle('hidden');
                if (isHidden) {
                    fetchNotifications();
                }
                if (profileMenu) profileMenu.classList.add('hidden');
            });
        }

        function fetchNotifications() {
            fetch('../users/get_notifications.php')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.error) return;
                    updateBadge(data.count);
                    renderNotifications(data.notifications);
                })
                .catch(function() {});
        }

        function updateBadge(count) {
            if (!notifBadge) return;
            if (count > 0) {
                notifBadge.textContent = count;
                notifBadge.classList.remove('hidden');
            } else {
                notifBadge.classList.add('hidden');
            }
        }

        function renderNotifications(list) {
            if (!notifList) return;
            if (!list || list.length === 0) {
                notifList.innerHTML = '<div class="p-6 text-center text-sm text-gray-400">No notifications yet</div>';
                return;
            }
            var html = '';
            list.forEach(function(n) {
                var bg = n.is_read == 0 ? 'bg-orange-50' : '';
                var icon = getNotifIcon(n.type);
                html += '<a href="' + (n.link || '#') + '" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 notif-item ' + bg + '" data-id="' + n.id + '">';
                html += '<span class="text-lg flex-shrink-0 mt-0.5">' + icon + '</span>';
                html += '<div class="flex-1 min-w-0">';
                html += '<p class="text-sm text-gray-700 leading-snug">' + n.message + '</p>';
                html += '<p class="text-xs text-gray-400 mt-1">' + n.time_ago + '</p>';
                html += '</div>';
                if (n.is_read == 0) {
                    html += '<span class="w-2 h-2 rounded-full bg-brandOrange flex-shrink-0 mt-2"></span>';
                }
                html += '</a>';
            });
            notifList.innerHTML = html;

            document.querySelectorAll('.notif-item').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    var id = el.getAttribute('data-id');
                    if (id) {
                        fetch('../users/get_notifications.php?action=mark_read&id=' + id);
                    }
                });
            });
        }

        function getNotifIcon(type) {
            switch(type) {
                case 'enrollment': return '📋';
                case 'lesson': return '📖';
                case 'quiz': return '📝';
                case 'certificate': return '🎓';
                case 'payment': return '💰';
                default: return '🔔';
            }
        }

        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                fetch('../users/get_notifications.php?action=mark_all_read')
                    .then(function() {
                        updateBadge(0);
                        document.querySelectorAll('.notif-item').forEach(function(el) {
                            el.classList.remove('bg-orange-50');
                            var dot = el.querySelector('.w-2.h-2');
                            if (dot) dot.remove();
                        });
                    });
            });
        }

        setInterval(fetchNotifications, 15000);
    })();
    </script>

</body>
</html>