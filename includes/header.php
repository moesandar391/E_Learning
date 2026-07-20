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
            darkMode: 'class',
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
    <style>
        html.dark,
        html.dark body,
        html.dark .bg-\[#F8F9FA\] { background-color: #0f172a !important; }
        html.dark .bg-white,
        html.dark .bg-white\/90 { background-color: #1e293b !important; }
        html.dark .bg-gray-50 { background-color: #1e293b !important; }
        html.dark .bg-gray-100 { background-color: #334155 !important; }
        html.dark .bg-gray-200 { background-color: #374151 !important; }
        html.dark .bg-\[\#F1F3F5\] { background-color: #1e293b !important; }
        html.dark .bg-\[\#FFEAD6\] { background-color: #1e293b !important; }
        html.dark .bg-\[\#DCE9FF\] { background-color: #1e293b !important; }
        html.dark .bg-\[\#5C728D\] { background-color: #1e293b !important; }
        html.dark .bg-\[\#0B1120\] { background-color: #0f172a !important; }
        html.dark .bg-\[\#1A4B6E\] { background-color: #0f172a !important; }
        html.dark .bg-black\/5 { background-color: rgba(0,0,0,0.2) !important; }

        html.dark .text-gray-900,
        html.dark .text-gray-800,
        html.dark .text-\[#0F172A\],
        html.dark .text-\[#020617\],
        html.dark .text-slate-900 { color: #ffffff !important; }
        html.dark .text-gray-700,
        html.dark .text-slate-800,
        html.dark .text-slate-700 { color: #ffffff !important; }
        html.dark .text-gray-600 { color: #f8fafc !important; }
        html.dark .text-gray-500,
        html.dark .text-slate-600 { color: #f1f5f9 !important; }
        html.dark .text-gray-400,
        html.dark .text-slate-400 { color: #e2e8f0 !important; }
        html.dark .text-gray-200 { color: #ffffff !important; }
        html.dark .text-\[#566473\],
        html.dark .text-brandTextGray { color: #f1f5f9 !important; }
        html.dark .text-slate-500 { color: #ffffff !important; }
        html.dark .text-slate-300 { color: #f1f5f9 !important; }
        html.dark .text-\[#A87034\],
        html.dark .text-brandOchre { color: #d48d4b !important; }
        html.dark .text-brandOrange { color: #FF8A00 !important; }
        html.dark .text-orange-500,
        html.dark .text-orange-600 { color: #fb923c !important; }
        html.dark .text-blue-500 { color: #93c5fd !important; }
        html.dark .text-blue-800 { color: #bfdbfe !important; }
        html.dark .text-green-500 { color: #6ee7b7 !important; }
        html.dark .text-green-600,
        html.dark .text-green-700 { color: #6ee7b7 !important; }
        html.dark .text-red-500,
        html.dark .text-red-700 { color: #fca5a5 !important; }

        html.dark .border-gray-100,
        html.dark .border-gray-200,
        html.dark .border-gray-300,
        html.dark .border-b { border-color: #334155 !important; }
        html.dark .border-gray-400 { border-color: #475569 !important; }
        html.dark .border-blue-200 { border-color: rgba(37, 99, 235, 0.3) !important; }
        html.dark .border-orange-100,
        html.dark .border-orange-200 { border-color: #475569 !important; }
        html.dark .border-orange-600 { border-color: #c2410c !important; }
        html.dark .border-brandOrange { border-color: #FF8A00 !important; }
        html.dark .border-gray-700 { border-color: #475569 !important; }
        html.dark .border-white\/20 { border-color: rgba(255,255,255,0.05) !important; }
        html.dark .border-green-200 { border-color: rgba(5, 150, 105, 0.3) !important; }
        html.dark .border-red-200 { border-color: rgba(220, 38, 38, 0.3) !important; }

        html.dark .hover\:bg-gray-50:hover { background-color: #334155 !important; }
        html.dark .hover\:bg-gray-100:hover { background-color: #334155 !important; }
        html.dark .hover\:bg-gray-200:hover { background-color: #374151 !important; }
        html.dark .hover\:bg-orange-50:hover { background-color: #1e293b !important; }
        html.dark .hover\:bg-\[#161e33\]:hover { background-color: #1e293b !important; }
        html.dark .hover\:bg-\[#E07A00\]:hover { background-color: #c25f00 !important; }
        html.dark .hover\:bg-white\/20:hover { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .hover\:bg-gray-500:hover { background-color: #475569 !important; }

        html.dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.3) !important; }
        html.dark .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5) !important; }

        html.dark .notif-item.bg-orange-50 { background-color: rgba(255, 138, 0, 0.1) !important; }
        html.dark .bg-brandOrange { background-color: #FF8A00 !important; }
        html.dark .bg-orange-50 { background-color: rgba(255, 138, 0, 0.1) !important; }
        html.dark .bg-orange-500 { background-color: #ea580c !important; }
        html.dark .bg-orange-600 { background-color: #c2410c !important; }
        html.dark .bg-blue-100 { background-color: rgba(37, 99, 235, 0.2) !important; }
        html.dark .bg-green-50 { background-color: rgba(5, 150, 105, 0.15) !important; }
        html.dark .bg-green-100 { background-color: rgba(5, 150, 105, 0.2) !important; }
        html.dark .bg-green-500 { background-color: #059669 !important; }

        html.dark .from-orange-50 { --tw-gradient-from: #334155 !important; }
        html.dark .from-blue-50\/50 { --tw-gradient-from: rgba(37, 99, 235, 0.15) !important; }
        html.dark .from-green-50\/50 { --tw-gradient-from: rgba(5, 150, 105, 0.15) !important; }
        html.dark .from-orange-50\/50 { --tw-gradient-from: rgba(255, 138, 0, 0.1) !important; }
        html.dark .from-orange-400 { --tw-gradient-from: #ea580c !important; }
        html.dark .from-blue-400 { --tw-gradient-from: #3b82f6 !important; }
        html.dark .from-green-400 { --tw-gradient-from: #22c55e !important; }
        html.dark .to-white { --tw-gradient-to: #1e293b !important; }
        html.dark .to-amber-50 { --tw-gradient-to: #334155 !important; }
        html.dark .to-orange-600 { --tw-gradient-to: #c2410c !important; }
        html.dark .to-blue-600 { --tw-gradient-to: #2563eb !important; }
        html.dark .to-green-600 { --tw-gradient-to: #059669 !important; }
        html.dark .from-\[#1A4B6E\] { --tw-gradient-from: #0f172a !important; }
        html.dark .to-\[#0F3147\] { --tw-gradient-to: #1e293b !important; }
        html.dark .from-black\/60 { --tw-gradient-from: rgba(0,0,0,0.8) !important; }

        html.dark .text-indigo-600 { color: #a5b4fc !important; }
        html.dark .text-emerald-600 { color: #6ee7b7 !important; }
        html.dark .text-rose-600 { color: #fda4af !important; }
        html.dark .text-amber-600 { color: #fbbf24 !important; }
        html.dark .text-violet-600 { color: #c4b5fd !important; }
        html.dark .text-sky-600 { color: #7dd3fc !important; }
        html.dark .text-cyan-600 { color: #67e8f9 !important; }
        html.dark .text-fuchsia-600 { color: #f0abfc !important; }
        html.dark .text-teal-600 { color: #5eead4 !important; }
        html.dark .text-slate-500 { color: #ffffff !important; }
        html.dark .text-blue-600 { color: #93c5fd !important; }
        html.dark .text-yellow-400 { color: #fbbf24 !important; }
        html.dark .text-gray-300 { color: #e2e8f0 !important; }
        html.dark .text-orange-200 { color: #fdba74 !important; }
        html.dark .text-blue-200 { color: #bfdbfe !important; }
        html.dark .text-green-200 { color: #bbf7d0 !important; }

        html.dark .bg-blue-50 { background-color: rgba(37, 99, 235, 0.15) !important; }
        html.dark .bg-blue-600 { background-color: #2563eb !important; }

        html.dark .from-blue-50 { --tw-gradient-from: #1e3a5f !important; }
        html.dark .from-green-50 { --tw-gradient-from: #1a3a2a !important; }
        html.dark .to-indigo-50 { --tw-gradient-to: #2e1a47 !important; }

        html.dark .border-blue-500 { border-color: #3b82f6 !important; }

        html.dark .hover\:text-\[#FF8A00\]:hover { color: #FF8A00 !important; }

        html.dark .placeholder-gray-400::placeholder,
        html.dark .placeholder-slate-300\/80::placeholder { color: #64748b !important; }
    </style>
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

            <button id="mobileMenuBtn" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg border border-orange-200 bg-orange-50 text-slate-600 hover:text-brandOchre hover:bg-orange-100 transition flex-shrink-0 cursor-pointer" title="Menu">
                <svg id="menuOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg id="menuCloseIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <nav class="hidden md:flex items-center space-x-12 font-medium text-gray-500 dark:text-slate-200">

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
            <a href="../users/viewAllCourses.php?filter=All" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">All Courses</a>
            <a href="../users/viewAllCourses.php?filter=Cambridge" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">Cambridge</a>
            <a href="../users/viewAllCourses.php?filter=English%20Grammar" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">English Grammar</a>
            <a href="../users/viewAllCourses.php?filter=Writing%20Course" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">Writing Course</a>
            <a href="../users/viewAllCourses.php?filter=General%20English" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">General English</a>
            <a href="../users/viewAllCourses.php?filter=English%20for%20Kids" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-brandOchre">English for Kids</a>
        </div>
    </div>
    <a href="../users/reviews.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        Review
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>
    <a href="../users/about.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        About
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>

    <a href="../users/contact.php" class="group relative py-2 hover:text-brandOchre transition-colors duration-300">
        Contact
        <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
    </a>

</nav>
            <div class="relative w-72" id="headerSearchWrapper">

    <div class="flex items-center bg-white border border-gray-200 rounded-full shadow-sm hover:shadow-md transition-all duration-300 px-3 py-1.5">

        <!-- Search Icon -->
        <svg class="w-5 h-5 text-gray-400 mr-2"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
            </path>
        </svg>

        <!-- Input -->
        <input
            id="headerSearchInput"
            type="text"
            placeholder="Search courses..."
            autocomplete="off"
            class="flex-1 bg-transparent text-sm text-gray-700 outline-none placeholder-gray-400"
        >

        <!-- Search Button -->
        <button
            id="headerSearchBtn"
            type="button"
            class="ml-2 w-8 h-8 flex items-center justify-center rounded-full bg-brandOrange text-white hover:scale-105 transition-transform duration-200">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                </path>
            </svg>

        </button>

    </div>


    <!-- Search Results Dropdown -->
    <div id="headerSearchResults"
         class="absolute left-0 top-full mt-3 w-full bg-white rounded-2xl shadow-xl border border-gray-100 z-[100] hidden overflow-hidden">
    </div>

</div>

            <div class="flex items-center space-x-3">
                
               <button id="userDarkModeToggle"
                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-orange-200 bg-orange-50 hover:bg-orange-100 dark:hover:bg-gray-700 transition flex-shrink-0 cursor-pointer text-slate-600 hover:text-brandOchre"
                    title="Toggle dark mode">
                    <svg id="userDarkModeIcon"
                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

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
                        <button id="notifBtn" class="relative w-9 h-9 flex items-center justify-center rounded-lg border border-orange-200 bg-orange-50 text-slate-600 hover:text-brandOchre hover:bg-orange-100 transition flex-shrink-0 cursor-pointer" title="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span id="notifBadge" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 <?php echo $notif_count > 0 ? '' : 'hidden'; ?>">
                                <?php echo $notif_count; ?>
                            </span>
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

                        <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-200 py-1.5 z-[100]">
                            
                            <a href="profile.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                My Profile
                            </a>

                            <a href="my_learning.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                My Dashboard
                            </a>
                            
                            <a href="my_review.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                My Reviews
                            </a>
                            <hr class="border-gray-100 my-2">

                            <a href="../auth/logout.php" class="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-orange-50 transition">
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
                <?php if (!isset($_SESSION['username'])): ?>
                <div class="hidden lg:block">
                    <button onclick="window.location.href='../auth/register.php'"
                        class="group relative overflow-hidden rounded-full border-2 border-brandOrange bg-white px-6 py-2 font-semibold text-brandOrange transition-all duration-300 hover:shadow-lg">
                        <span class="absolute left-[calc(-100%-1.2rem)] top-0 h-full w-[calc(100%+1.2rem)] bg-brandOrange transition-transform duration-300 [clip-path:polygon(0_0,calc(100%-1.2rem)_0,100%_50%,calc(100%-1.2rem)_100%,0_100%)] group-hover:translate-x-full">
                        </span>

                        <span class="relative z-10 transition-colors duration-300 group-hover:text-white">
                        Get Started
                        </span>
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Overlay -->
            <div id="mobileMenu" class="fixed inset-0 z-40 hidden lg:hidden">
                <div id="mobileMenuBackdrop" class="absolute inset-0 bg-black/50"></div>
                <div class="absolute left-0 top-0 h-full w-72 max-w-[85vw] bg-white shadow-xl pt-20 overflow-y-auto">
                    <div class="px-4 py-4 space-y-1">
                        <a href="../users/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Home
                        </a>

                        <div>
                            <button onclick="toggleMobileCourses()" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Courses
                                </span>
                                <svg id="mobileCoursesArrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="mobileCoursesSub" class="hidden pl-4 space-y-1 pb-1">
                                <a href="../users/viewAllCourses.php?filter=All" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">All Courses</a>
                                <a href="../users/viewAllCourses.php?filter=Cambridge" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">Cambridge</a>
                                <a href="../users/viewAllCourses.php?filter=English%20Grammar" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">English Grammar</a>
                                <a href="../users/viewAllCourses.php?filter=Writing%20Course" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">Writing Course</a>
                                <a href="../users/viewAllCourses.php?filter=General%20English" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">General English</a>
                                <a href="../users/viewAllCourses.php?filter=English%20for%20Kids" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-orange-50 hover:text-brandOchre rounded-lg transition">English for Kids</a>
                            </div>
                        </div>

                        <a href="../users/reviews.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Review
                        </a>

                        <a href="../users/about.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            About
                        </a>

                        <a href="../users/contact.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Contact
                        </a>
                    </div>

                    <div class="border-t border-gray-100 px-6 py-4 space-y-2">
                        <?php if (isset($_SESSION['username'])): ?>
                            <a href="profile.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                My Profile
                            </a>
                            <a href="my_learning.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                My Dashboard
                            </a>
                            <a href="my_review.php" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-brandOchre font-medium transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                My Reviews
                            </a>
                            <hr class="border-gray-100 my-2">
                            <a href="../auth/logout.php" class="flex items-center px-4 py-3 rounded-lg text-red-500 hover:bg-orange-50 font-medium transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </a>
                        <?php else: ?>
                            <a href="../auth/login.php" class="flex items-center justify-center px-6 py-3 bg-brandOrange text-white font-semibold rounded-full hover:bg-brandOrangeHover transition text-sm">Login</a>
                            <a href="../auth/register.php" class="flex items-center justify-center px-6 py-3 border-2 border-brandOrange text-brandOrange font-semibold rounded-full hover:bg-brandOrange hover:text-white transition text-sm">Get Started</a>
                        <?php endif; ?>
                    </div>
                </div>
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

    // Dark mode toggle
    const userDarkBtn = document.getElementById('userDarkModeToggle');
    const userDarkPath = document.querySelector('#userDarkModeIcon path');
    if (userDarkBtn && userDarkPath) {
        userDarkBtn.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('user-theme', isDark ? 'dark' : 'light');
            updateUserDarkIcon(isDark);
        });
        function updateUserDarkIcon(isDark) {
            if (isDark) {
                userDarkPath.setAttribute('d', 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z');
                userDarkBtn.classList.add('text-brandOrange');
            } else {
                userDarkPath.setAttribute('d', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z');
                userDarkBtn.classList.remove('text-brandOrange');
            }
        }
        var storedUser = localStorage.getItem('user-theme');
        if (storedUser) {
            document.documentElement.classList.toggle('dark', storedUser === 'dark');
            updateUserDarkIcon(storedUser === 'dark');
        } else {
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', prefersDark);
            updateUserDarkIcon(prefersDark);
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('user-theme')) {
                document.documentElement.classList.toggle('dark', e.matches);
                updateUserDarkIcon(e.matches);
            }
        });
    }
    </script>

<script>
(function() {
    var input = document.getElementById('headerSearchInput');
    var btn = document.getElementById('headerSearchBtn');
    var resultsEl = document.getElementById('headerSearchResults');
    var wrapper = document.getElementById('headerSearchWrapper');
    if (!input || !btn || !resultsEl || !wrapper) return;

    var debounceTimer;

    function doSearch(q) {
        q = q.trim();
        if (q.length < 1) {
            resultsEl.classList.add('hidden');
            resultsEl.innerHTML = '';
            return;
        }
        fetch('../users/search_ajax.php?q=' + encodeURIComponent(q))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || data.length === 0) {
                    resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">No results found</div>';
                    resultsEl.classList.remove('hidden');
                    return;
                }
                var html = '';
                data.forEach(function(m) {
                    var img = m.image
                        ? '<img src="../uploads/modules/' + encodeURIComponent(m.image) + '" alt="" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">'
                        : '<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>';
                    var price = m.price > 0 ? number_format(m.price) + ' MMK' : 'Free';
                    html += '<a href="../users/details.php?module_id=' + m.id + '" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 last:border-b-0">';
                    html += img;
                    html += '<div class="flex-1 min-w-0">';
                    html += '<div class="text-sm font-medium text-gray-800 truncate">' + escapeHtml(m.module_name) + '</div>';
                    html += '<div class="text-xs text-gray-400 truncate">' + escapeHtml(m.course_name) + ' &middot; ' + escapeHtml(m.level || '') + '</div>';
                    html += '</div>';
                    html += '<span class="text-xs font-semibold text-brandOchre flex-shrink-0">' + price + '</span>';
                    html += '</a>';
                });
                resultsEl.innerHTML = html;
                resultsEl.classList.remove('hidden');
            })
            .catch(function() {
                resultsEl.classList.add('hidden');
            });
    }

    function number_format(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function submitSearch() {
        var q = input.value.trim();
        if (q) {
            window.location.href = '../users/viewAllCourses.php?search=' + encodeURIComponent(q);
        }
    }

    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() { doSearch(input.value); }, 300);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            resultsEl.classList.add('hidden');
            submitSearch();
        }
        if (e.key === 'Escape') {
            resultsEl.classList.add('hidden');
            input.blur();
        }
    });

    btn.addEventListener('click', function(e) {
        e.preventDefault();
        submitSearch();
    });

    document.addEventListener('click', function(e) {
        if (!wrapper.contains(e.target)) {
            resultsEl.classList.add('hidden');
        }
    });

    input.addEventListener('focus', function() {
        if (input.value.trim().length > 0) {
            doSearch(input.value);
        }
    });
})();
</script>

<script>
(function() {
    var menuBtn = document.getElementById('mobileMenuBtn');
    var menu = document.getElementById('mobileMenu');
    var backdrop = document.getElementById('mobileMenuBackdrop');
    var openIcon = document.getElementById('menuOpenIcon');
    var closeIcon = document.getElementById('menuCloseIcon');

    if (!menuBtn || !menu) return;

    function toggleMenu() {
        menu.classList.toggle('hidden');
        if (openIcon && closeIcon) {
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    }

    menuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMenu();
    });

    if (backdrop) {
        backdrop.addEventListener('click', function() {
            toggleMenu();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && menu && !menu.classList.contains('hidden')) {
            toggleMenu();
        }
    });

    var profileBtn = document.getElementById('profileBtn');
    if (profileBtn) {
        profileBtn.addEventListener('click', function() {
            if (menu && !menu.classList.contains('hidden')) {
                toggleMenu();
            }
        });
    }
})();

function toggleMobileCourses() {
    var sub = document.getElementById('mobileCoursesSub');
    var arrow = document.getElementById('mobileCoursesArrow');
    if (sub && arrow) {
        sub.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
}
</script>

</body>
</html>