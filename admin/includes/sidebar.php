<style>
    /* Only 3 lines of CSS needed for the toggle animation */
    #sidebar {
        transition: width 0.3s ease-in-out;
        /* Changed to 'visible' so tooltips can escape the sidebar in BOTH open and closed states */
        overflow: visible; 
        position: relative;
    }
    #sidebar.collapsed {
        width: 80px !important;
    }
    
    /* Base rule to hide texts when collapsed */
    #sidebar.collapsed .nav-text,
    #sidebar.collapsed .sidebar-header-text {
        display: none;
    }

    /* ── Center logo when collapsed ── */
    #sidebar.collapsed > div:first-child {
        padding-left: 0 !important;
        padding-right: 0 !important;
        justify-content: center !important;
        position: relative;
    }

    /* ── Logo Hover State (Disappears) ── */
    #sidebar.collapsed > div:first-child > div:first-child {
        margin-right: 0 !important;
        padding: 10px;
        margin: 10px 0;
        border-radius: 8px;
        transition: opacity 0.2s ease;
        opacity: 1;
    }
    #sidebar.collapsed > div:first-child:hover > div:first-child {
        opacity: 0 !important;
    }

    /* ── Toggle Icon Hover State (Appears over logo) ── */
    #sidebar.collapsed > div:first-child > div:last-child {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 8px;
        z-index: 20;
        opacity: 0; 
        pointer-events: none; 
        transition: opacity 0.2s ease;
    }
    #sidebar.collapsed > div:first-child:hover > div:last-child {
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    /* ══════════════════════════════════════════════════════════ */
    /* ── "Open/Close sidebar" Tooltip on Toggle Button ── */
    /* ══════════════════════════════════════════════════════════ */
    #sidebarToggle {
        position: relative; 
    }

    #sidebarToggle::after {
        content: "Close sidebar"; 
        position: absolute;
        left: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%) translateX(8px);
        
        /* Updated background and text colors */
        background-color: #EA580C; 
        color: #ffffff; 
        
        font-size: 13px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 8px;
        white-space: nowrap;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #EA580C; /* Added border match */
        opacity: 0;
        visibility: hidden;
        pointer-events: none; 
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        z-index: 1000;
    }

    #sidebar.collapsed #sidebarToggle::after {
        content: "Open sidebar";
    }

    #sidebarToggle:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(0);
    }

    /* Dark mode support */
    .dark #sidebarToggle::after {
        background-color: #1f2937;
        /* Keeping white text for contrast in dark mode */
        color: #f9fafb; 
        border-color: #374151;
    }


    /* ══════════════════════════════════════════════════════════ */
    /* ── Floating Tooltips for Collapsed Nav Icons ── */
    /* ══════════════════════════════════════════════════════════ */

    /* Override the nav's scroll behavior when collapsed so tooltips don't get clipped */
    #sidebar.collapsed nav,
    #sidebar.collapsed > div:last-child {
        overflow: visible !important;
    }

    #sidebar.collapsed p.nav-text {
        display: none !important;
    }

    #sidebar.collapsed nav a,
    #sidebar.collapsed > div:last-child a {
        position: relative;
        justify-content: center !important;
    }

    #sidebar.collapsed nav a .nav-text,
    #sidebar.collapsed > div:last-child a .nav-text {
        display: block !important;
        position: absolute;
        left: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%) translateX(8px);
        background-color: #EA580C; 
        color: #ffffff;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 8px;
        white-space: nowrap;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #f3f4f6;
        opacity: 0;
        visibility: hidden;
        pointer-events: none; 
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        z-index: 1000;
    }

    #sidebar.collapsed nav a:hover .nav-text,
    #sidebar.collapsed > div:last-child a:hover .nav-text {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(0);
    }

    /* Dark mode support for nav tooltips */
    .dark #sidebar.collapsed nav a .nav-text,
    .dark #sidebar.collapsed > div:last-child a .nav-text {
        background-color: #1f2937;
        color: #f9fafb;
        border-color: #374151;
    }
</style>

<aside id="sidebar"
     class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col flex-shrink-0">
    
    <!-- <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center overflow-hidden">
            <img src="../assets/Logo 3.png"
                 alt="AccessEdu Logo"
                 class="w-10 h-10 object-contain mr-3 flex-shrink-0">
            <h1 class="text-xl font-extrabold tracking-tight whitespace-nowrap sidebar-header-text">
                <span class="text-brandOrange">Access</span>
                <span class="text-gray-800 dark:text-gray-100">Edu</span>
            </h1>
        </div>

        <div class="flex items-center gap-1">
            <button id="sidebarToggle"
            class="group w-9 h-9 flex items-center justify-center rounded-lg hover:bg-orange-50 dark:hover:bg-gray-700 transition flex-shrink-0 cursor-pointer">
        <svg id="toggleIcon"
             xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-brandOrange transition-colors duration-200 transition-transform duration-300"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="2"/>
            <line x1="9" y1="4" x2="9" y2="20" stroke-width="2"/>
        </svg>
    </button>
        </div>
    </div> -->

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 nav-text">Main</p>
        <a href="dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="nav-text">Dashboard</span>
        </a>
        <a href="students.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'students.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            <span class="nav-text">Students</span>
        </a>
        <a href="courses.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'courses.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span class="nav-text">Courses</span>
        </a>
        <a href="modules.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'modules.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span class="nav-text">Modules</span>
        </a>
        <a href="lessons.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'lessons.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="nav-text">Lessons</span>
        </a>
        <a href="enrollments.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'enrollments.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span class="nav-text">Enrollments</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider pt-4 mb-2 nav-text">Finance</p>
        <a href="payments.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'payments.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="nav-text">Payments</span>
        </a>
        <a href="certificates.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'certificates.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke="join="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            <span class="nav-text">Certificates</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider pt-4 mb-2 nav-text">Other</p>
        <a href="contacts.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'contacts.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
            <span class="nav-text">Messages</span>
        </a>
        <a href="reviews.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'reviews.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span class="nav-text">Reviews</span>
        </a>
        <a href="reports.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="nav-text">Reports</span>
        </a>
        <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'bg-brandOrange bg-opacity-10 text-brandOrange' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brandOrange'; ?>">
            <svg class="w-5 h-5 flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="nav-text">Profile</span>
        </a>
    </nav>

    <div class="border-t border-gray-100 dark:border-gray-700 p-3 flex-shrink-0">
        <a href="../auth/logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-500 bg-red-300 dark:bg-red-900/30 hover:bg-red-500 dark:hover:bg-red-900/50 hover:text-red-800 transition-all duration-200 w-full justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <span class="nav-text">Logout</span>
        </a>
    </div>
</aside>

<script>
const sidebar = document.getElementById("sidebar");
const btn = document.getElementById("sidebarToggle");

btn.addEventListener("click", function() {
    sidebar.classList.toggle("collapsed");
});
</script>