<?php
 $pageTitles = [
    'dashboard.php'     => ['title' => 'Dashboard', 'subtitle' => 'Welcome back, ' . htmlspecialchars($_SESSION['username'] ?? 'Admin') . '!'],
    'students.php'      => ['title' => 'Students',    'subtitle' => 'Manage all registered students'],
    'courses.php'       => ['title' => 'Courses',     'subtitle' => 'Manage all courses'],
    'modules.php'       => ['title' => 'Modules',     'subtitle' => 'Manage modules within courses'],
    'lessons.php'       => ['title' => 'Lessons',     'subtitle' => 'Manage all lessons across modules'],
    'enrollments.php'   => ['title' => 'Enrollments', 'subtitle' => 'Track all student enrollments'],
    'payments.php'      => ['title' => 'Payments',    'subtitle' => 'Manage enrollment payments'],
    'certificates.php'  => ['title' => 'Certificates','subtitle' => 'Manage issued certificates'],
    'messages.php'      => ['title' => 'Contact Messages', 'subtitle' => 'Student inquiries from the contact form'],
    'reviews.php' => ['title' => 'Student Reviews', 'subtitle' => 'Student feedback and ratings for your modules'],
    'reports.php'       => ['title' => 'Reports',     'subtitle' => 'Generate and view detailed transaction insights'],
    'settings.php'      => ['title' => 'Profile',     'subtitle' => 'Manage your account profiles'],
];

 $currentPage = basename($_SERVER['PHP_SELF']);
 $pageInfo = $pageTitles[$currentPage] ?? ['title' => 'Admin', 'subtitle' => 'Welcome back, ' . htmlspecialchars($_SESSION['username'] ?? 'Admin') . '!'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?> | Access Edu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        brandBg: '#F8F9FA',
                        brandCard: '#FFFFFF',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .dark body,
        .dark .bg-brandBg { background-color: #0f172a; }
        .dark .bg-white { background-color: #1e293b; }
        .dark .bg-gray-50 { background-color: #1e293b; }
        .dark .bg-gray-100 { background-color: #334155; }
        .dark .text-gray-800,
        .dark .text-gray-900 { color: #f1f5f9; }
        .dark .text-gray-700 { color: #e2e8f0; }
        .dark .text-gray-600 { color: #cbd5e1; }
        .dark .text-gray-500 { color: #94a3b8; }
        .dark .text-gray-400 { color: #64748b; }
        .dark .text-gray-300 { color: #475569; }
        .dark .border-gray-200,
        .dark .border-gray-100,
        .dark .border-gray-50,
        .dark .divide-gray-100,
        .dark .border-b { border-color: #334155; }
        .dark .hover\:bg-gray-50:hover { background-color: #334155; }
        .dark .from-orange-100 { --tw-gradient-from: #334155; }
        .dark .to-orange-200 { --tw-gradient-to: #1e293b; }
        .dark .from-purple-100 { --tw-gradient-from: #374151; }
        .dark .to-purple-200 { --tw-gradient-to: #1e293b; }
        .dark .from-teal-100 { --tw-gradient-from: #374151; }
        .dark .to-teal-200 { --tw-gradient-to: #1e293b; }
        .dark .bg-orange-100\/50 { background-color: rgba(51, 65, 85, 0.5); }
        .dark thead.bg-orange-100\/50 { background-color: rgba(51, 65, 85, 0.5); }
        .dark .hover\:bg-orange-50:hover { background-color: #1e293b; }
        .dark .bg-black\/50 { background-color: rgba(0,0,0,0.7); }
        .dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.4); }
        .dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.3); }
        .dark .bg-green-50 { background-color: rgba(5, 150, 105, 0.15); }
        .dark .text-green-600,
        .dark .text-green-700 { color: #6ee7b7; }
        .dark .bg-green-500 { background-color: #059669; }
        .dark .bg-yellow-50 { background-color: rgba(202, 138, 4, 0.15); }
        .dark .text-yellow-600,
        .dark .text-yellow-700 { color: #fde68a; }
        .dark .bg-yellow-500 { background-color: #d97706; }
        .dark .bg-red-50 { background-color: rgba(220, 38, 38, 0.15); }
        .dark .text-red-600,
        .dark .text-red-700 { color: #fca5a5; }
        .dark .bg-red-500 { background-color: #dc2626; }
        .dark .bg-blue-50 { background-color: rgba(37, 99, 235, 0.15); }
        .dark .text-blue-600,
        .dark .text-blue-700 { color: #93c5fd; }
        .dark .bg-pink-50 { background-color: rgba(190, 24, 93, 0.15); }
        .dark .text-pink-600,
        .dark .text-pink-700 { color: #f9a8d4; }
        .dark .bg-purple-50 { background-color: rgba(126, 34, 206, 0.15); }
        .dark .text-purple-600,
        .dark .text-purple-800 { color: #c4b5fd; }
        .dark .bg-teal-50 { background-color: rgba(13, 148, 136, 0.15); }
        .dark .text-teal-600,
        .dark .text-teal-700 { color: #5eead4; }
        .dark .bg-indigo-50 { background-color: rgba(79, 70, 229, 0.15); }
        .dark .text-indigo-600 { color: #a5b4fc; }
        .dark .bg-orange-50 { background-color: rgba(255, 138, 0, 0.15); }
        .dark .bg-orange-100 { background-color: rgba(255, 138, 0, 0.2); }
        .dark .text-brandOrange { color: #ff9f33; }
        .dark .border-orange-200 { border-color: #475569; }
        .dark .border-green-200 { border-color: rgba(5, 150, 105, 0.3); }
        .dark .border-red-200 { border-color: rgba(220, 38, 38, 0.3); }
        .dark .border-blue-200 { border-color: rgba(37, 99, 235, 0.3); }
        .dark .border-yellow-200 { border-color: rgba(202, 138, 4, 0.3); }
        .dark .border-teal-200 { border-color: rgba(13, 148, 136, 0.3); }
        .dark .hover\:bg-green-100:hover { background-color: rgba(5, 150, 105, 0.2); }
        .dark .hover\:bg-red-100:hover { background-color: rgba(220, 38, 38, 0.2); }
        .dark .hover\:bg-blue-50:hover { background-color: rgba(37, 99, 235, 0.15); }
        .dark .hover\:bg-yellow-50:hover { background-color: rgba(202, 138, 4, 0.15); }
        .dark .hover\:bg-green-50:hover { background-color: rgba(5, 150, 105, 0.15); }
        .dark .hover\:bg-red-50:hover { background-color: rgba(220, 38, 38, 0.15); }
        .dark .hover\:text-green-700:hover { color: #6ee7b7; }
        .dark .hover\:text-red-700:hover { color: #fca5a5; }
        .dark .hover\:text-gray-600:hover,
        .dark .hover\:text-gray-800:hover { color: #e2e8f0; }
        .dark .bg-green-100 { background-color: rgba(5, 150, 105, 0.2); }
        .dark .bg-yellow-100 { background-color: rgba(202, 138, 4, 0.2); }
        .dark .bg-red-100 { background-color: rgba(220, 38, 38, 0.2); }
        .dark .bg-blue-100 { background-color: rgba(37, 99, 235, 0.2); }
        .dark .bg-teal-100 { background-color: rgba(13, 148, 136, 0.2); }
        .dark .bg-purple-100 { background-color: rgba(126, 34, 206, 0.2); }
        .dark .file\:bg-orange-50 { --tw-bg-opacity: 1; background-color: rgba(51, 65, 85, var(--tw-bg-opacity)); }
        .dark .hover\:file\:bg-orange-100:hover { --tw-bg-opacity: 1; background-color: rgba(71, 85, 105, var(--tw-bg-opacity)); }
        .dark .dialog-content { background-color: #1e293b !important; }
        .dark .dialog-title { color: #f1f5f9 !important; }
        .dark .hover\:bg-gray-200:hover { background-color: #334155; }
        .dark .bg-blue-50\/40 { background-color: rgba(37, 99, 235, 0.1); }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #475569;
        }

        /* Compact tables inside scrollable wrappers */
        .overflow-x-auto table th,
        .overflow-x-auto table td {
            padding: 0.4rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
</head>
<body class="bg-brandBg font-sans antialiased">

<!-- ✅ FIXED HEADER: sticky top-0 z-50 -->
<header class="h-16 bg-white border-b border-gray-200 flex items-center w-full sticky top-0 z-50">
    
    <div class="w-64 flex items-center px-6 border-r border-gray-300 flex-shrink-0">
        <img src="../assets/Logo 3.png" class="w-8 h-8 mr-3 object-contain" alt="Logo">
        <span class="text-xl font-bold tracking-tight text-gray-800">
            <span class="text-orange-500">Access</span>Edu
        </span>
        <button id="sidebarToggle" class="ml-auto text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2" stroke-width="2"/><line x1="9" y1="4" x2="9" y2="20" stroke-width="2"/></svg>
        </button>
    </div>

    <div class="flex-1 flex items-center justify-between px-8">
        <div>
            <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($pageInfo['title']) ?></h2>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($pageInfo['subtitle']) ?></p>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </a>
        </div>
    </div>
</header>

<script>
(function() {
    function setTheme(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('admin-theme', theme);
    }
    var stored = localStorage.getItem('admin-theme');
    if (stored) {
        setTheme(stored);
    } else {
        setTheme(window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (!localStorage.getItem('admin-theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
})();
</script>

<!-- ✅ FIXED LAYOUT: calc(100vh - 4rem) to account for fixed header -->
<div class="flex" style="height: calc(100vh - 4rem);">