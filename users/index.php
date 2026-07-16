<?php 
require_once '../config/db.php';
include_once('../includes/header.php');

$query = "SELECT course_name, description FROM courses";
$result = $conn->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);

$popularQuery = "SELECT m.id AS module_id, m.name AS module_name, m.image AS module_image, m.price,
                        c.course_name, c.level, c.instructor_name, COUNT(l.id) AS total_lessons
                 FROM modules m
                 JOIN courses c ON m.course_id = c.id
                 LEFT JOIN lessons l ON m.id = l.module_id
                 GROUP BY m.id, m.name, m.image, m.price, c.course_name, c.level, c.instructor_name
                 ORDER BY m.id DESC
                 LIMIT 6";
$popularResult = $conn->query($popularQuery);
$popularModules = $popularResult->fetch_all(MYSQLI_ASSOC);

$heroEnrolled = false;
$enrolledModuleIds = [];
if (isset($_SESSION['user_id'])) {
    $enrollCheck = $conn->prepare("SELECT module_id FROM enrollments WHERE user_id = ? AND status = 'confirmed'");
    $enrollCheck->bind_param("i", $_SESSION['user_id']);
    $enrollCheck->execute();
    $enrollResult = $enrollCheck->get_result();
    while ($row = $enrollResult->fetch_assoc()) {
        $enrolledModuleIds[] = $row['module_id'];
    }
    $heroEnrolled = count($enrolledModuleIds) > 0;
}
?>
    <main class="w-full max-w-7xl mx-auto px-6 py-8 md:py-16 flex-1 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
        
        <div class="lg:col-span-5 space-y-6">
            <div class="inline-block bg-brandOrange text-white text-xs font-bold tracking-wider uppercase px-4 py-2 rounded-lg shadow-sm">
                EMPOWERING GLOBAL COMMUNICATORS
            </div>

            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-slate-500 leading-tight">
                Master English for the <br>
                <span class="text-brandOchre italic font-bold">Global Stage</span>
            </h1>

            <p class="text-base text-brandTextGray leading-relaxed max-w-lg">
                Unlock international opportunities with structured, professional English courses designed for ambitious learners. From business mastery to academic excellence.
            </p>

            <div class="pt-4 flex flex-wrap gap-4">
    <a href="<?php echo $heroEnrolled ? 'my_learning.php' : (isset($_SESSION['user_id']) ? 'enroll.php' : '../auth/login.php'); ?>" 
       class="group relative px-7 py-3.5 font-semibold text-white rounded-xl transition-all shadow-md overflow-hidden border-2 border-brandOrange">
        
        <span class="absolute inset-0 bg-brandOrange -translate-x-full transition-transform duration-500 ease-out group-hover:translate-x-0 -z-10"></span>
        
        <span class="relative z-10 transition-colors duration-500 text-brandOrange hover:text-white"><?php echo $heroEnrolled ? 'Learn Now' : 'Enroll Now'; ?></span>
    </a>

    <a href="../users/courses.php" 
       class="group relative px-7 py-3.5 font-semibold text-slate-700 rounded-xl transition-all shadow-sm overflow-hidden border-2 border-gray-300 hover:text-white">
        
        <span class="absolute inset-0 bg-slate-700 -translate-x-full transition-transform duration-500 ease-out group-hover:translate-x-0 -z-10"></span>
        
        <span class="relative z-10 transition-colors duration-500">Browse Courses</span>
    </a>
</div>
        </div>

        <div class="lg:col-span-7 h-[400px] sm:h-[520px] relative w-full rounded-2xl overflow-hidden shadow-xl border border-gray-100">
            
            <div class="absolute inset-0 w-full h-full">
                <div class="slide active absolute inset-0 opacity-100 transition-opacity duration-1000 ease-in-out">
                    <img src="../assets/home.jpg" alt="Workspace Slide 1" class="w-full h-full object-cover">
                </div>

                <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
                    <img src="../assets/group1.png" alt="Learning Slide 2" class="w-full h-full object-cover">
                </div>

                <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
                    <img src="../assets/child.jpg" alt="Learning Slide 2" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="absolute inset-0 bg-black/5 z-10"></div>

            <div class="absolute top-1/4 left-6 z-20 w-52 bg-white/90 backdrop-blur-md p-5 rounded-2xl border border-white/60 shadow-[0_-8px_24px_rgba(15,23,42,0.12),0_12px_24px_rgba(15,23,42,0.18)] transform hover:scale-[1.02] transition-transform duration-300">
                
                <div class="absolute -top-3.5 -right-2 bg-brandOchre text-white rounded-full p-2 shadow-md">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 01.788 0l4 1.714a.999.999 0 01.356.257l2.644-1.13a1 1 0 000-1.84l-7-3zM7 10.3a1 1 0 01.17-.557l1.28.548a3.001 3.001 0 003.1 0l1.28-.548a1 1 0 01.17.557v1.8a1 1 0 01-1 1H8a1 1 0 01-1-1v-1.8z"></path></svg>
                </div>
                
                <h4 class="font-bold text-slate-900 text-base tracking-tight">Improve Your Future</h4>
                <p class="text-xs font-medium text-brandTextGray mt-1">Start Learning Today!</p>
            </div>
        </div>

    </main>

    <section class="w-full bg-[#F8F9FA] py-16 px-6 font-sans">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <h2 class="font-serif font-bold text-[30px] text-brandOchre leading-tight tracking-tight">
                        Explore Categories
                    </h2>
                    <p class="text-sm text-[#566473] mt-2 font-medium">
                        Find the right path for your learning goals
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <?php 
                $iconClasses = [
                    'text-indigo-600',
                    'text-emerald-600', 
                    'text-rose-600',
                    'text-amber-600',
                    'text-violet-600',
                    'text-sky-600',
                    'text-orange-600',
                    'text-cyan-600',
                    'text-fuchsia-600',
                    'text-teal-600'
                ];
                $iconPaths = [
                    'M20.676 17.69a1 1 0 00-.87-.17L7.04 16.5l1.83 8.868a1 1 0 001.87.03L20.676 17.69zM21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    'M4 16l4.586-4.586a2 2 0 012.828 0L16 16M5 18h14a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2zM10 7V5a2 2 0 012-2h4a2 2 0 012 2v2M9 7v5a1 1 0 001 1h4a1 1 0 001-1V7M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'M3 6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V6zm0 14l4-4h10l4 4M7 10a1 1 0 100 2 1 1 0 000-2zM17 10a1 1 0 100 2 1 1 0 000-2z',
                    'M11 3.055A9.001 9.001 0 1020.894 21 9.001 9.001 0 0011.894 3.055zM15.5 7.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
                    'M12 14c3.866 0 7-2.477 7-5s-3.134-5-7-5-7 2.477-7 5 3.134 5 7 5zm-6-5c0 3.31 2.69 6 6 6s6-2.69 6-6-2.69-6-6-6-6 2.69-6 6z',
                    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m6 0a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z',
                    'M13 16h-1a4 4 0 01-4-4 0 0 4 4zm0 0V8m0 8a4 4 0 110-8 4 4 0 010 8z',
                    'M4 4h16v2H4V4zm0 4h16v2H4V8zm0 4h16v2H4v-2z',
                    'M4 3a3 3 0 000 6v10a3 3 0 003 3h10a3 3 0 003-3V9a3 3 0 000-6H4zm0 2h12a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1zm4 3a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H9a1 1 0 01-1-1V8zm0 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H9a1 1 0 01-1-1v-2z',
                    'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11H3m2 0a2 2 0 012 2v6a2 2 0 01-2 2m0-8h4m4 0h4'
                ];
                
                $iconIndex = 0;
                ?>
                <?php foreach ($categories as $cat): ?>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center group transform hover:-translate-y-2 hover:shadow-lg hover:border-gray-200 transition-all duration-300 ease-out cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-[#DCE9FF] flex items-center justify-center <?php echo $iconClasses[$iconIndex % count($iconClasses)]; ?> mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $iconPaths[$iconIndex % count($iconPaths)]; ?>"></path>
                        </svg>
                    </div>

                    <h3 class="font-serif font-bold text-[#020617] text-xl mb-2 transition-colors duration-200 group-hover:text-brandOrange">
                        <?php echo htmlspecialchars($cat['course_name']); ?>
                    </h3>
                    
                    <p class="text-xs text-[#566473] line-clamp-2 leading-relaxed font-medium">
                        <?php echo htmlspecialchars($cat['description']); ?>
                    </p>
                </div>
                <?php $iconIndex++; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════ -->
<!-- ── HOW IT WORK SECTION (Project Colors Applied) ── -->
<!-- ═══════════════════════════════════════════════════════ -->
<section class="w-full bg-[#F8F9FA] py-16 px-6 font-sans">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
            <div>
                <span class="text-sm font-medium text-brandTextGray tracking-wide">Over 1,235+ Course</span>
                <h2 class="font-serif font-bold text-3xl md:text-4xl text-slate-500 tracking-tight mt-2">
                    How It Work? <span class="inline-block w-16 h-1 bg-brandOrange align-middle ml-2 rounded-full"></span>
                </h2>
            </div>
        </div>

        <!-- CHANGED: grid → flex, arrows are now real flex children -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-0">
            
            <!-- Step 1 -->
            <div class="bg-orange-50 rounded-2xl p-10 text-center border border-orange-100 hover:shadow-lg transition-all duration-300 w-full md:w-auto md:flex-1">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-sm text-brandOrange">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="font-serif font-bold text-xl text-slate-800 mb-3">Find Your Course</h3>
                <p class="text-sm text-brandTextGray leading-relaxed">It has survived not only centurie also leap into electronic.</p>
            </div>

            <!-- Arrow 1: real flex child, no absolute positioning -->
            <div class="hidden md:flex items-center justify-center w-14 flex-shrink-0 text-brandOrange">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>

            <!-- Step 2 -->
            <div class="bg-orange-50 rounded-2xl p-10 text-center border border-orange-100 hover:shadow-lg transition-all duration-300 w-full md:w-auto md:flex-1">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-sm text-brandOrange">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="font-serif font-bold text-xl text-slate-800 mb-3">Book A Course</h3>
                <p class="text-sm text-brandTextGray leading-relaxed">It has survived not only centurie also leap into electronic.</p>
            </div>

            <!-- ✅ Arrow 2: real flex child, no absolute positioning -->
            <div class="hidden md:flex items-center justify-center w-14 flex-shrink-0 text-brandOrange">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>

            <!-- Step 3 -->
            <div class="bg-orange-50 rounded-2xl p-10 text-center border border-orange-100 hover:shadow-lg transition-all duration-300 w-full md:w-auto md:flex-1">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-sm text-brandOrange">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h3 class="font-serif font-bold text-xl text-slate-800 mb-3">Get Certificate</h3>
                <p class="text-sm text-brandTextGray leading-relaxed">It has survived not only centurie also leap into electronic.</p>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- ── IMPORTANT FOR ENGLISH LEARNING ── -->
<!-- ═══════════════════════════════════════════════════════ -->
<section class="w-full bg-[#F8F9FA] py-10 px-6 font-sans">
    <div class="max-w-7xl mx-auto">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-sm font-medium text-brandTextGray tracking-wide">Why Learn English?</span>
            <h2 class="font-serif font-bold text-3xl md:text-4xl text-slate-500 tracking-tight mt-2 mb-4">
                Important for English Learning
            </h2>
            <p class="text-sm text-brandTextGray leading-relaxed">
                English is more than a language — it's a bridge to opportunities, connections, and personal growth that shapes your future.
            </p>
        </div>

        <!-- Row 1: Image Left + Text Right -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center mb-10 lg:mb-20">
            
            <div class="relative">
                <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-100">
                    <img src="../assets/English.png" alt="English Learning Classroom" 
                         class="w-full h-80 lg:h-[420px] object-cover">
                </div>
                <!-- Floating stat badge -->
                <div class="absolute -bottom-5 -right-3 lg:-right-6 bg-brandOrange text-white rounded-2xl px-6 py-4 shadow-lg shadow-orange-200">
                    <p class="text-2xl font-bold leading-none">1.5B+</p>
                    <p class="text-[10px] font-medium mt-1 opacity-90 uppercase tracking-wider">Global Speakers</p>
                </div>
                <!-- Floating tag on image -->
                <div class="absolute top-5 left-5 bg-white/90 backdrop-blur-sm text-brandOrange text-[10px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-full border border-orange-200 shadow-sm">
                    <span class="inline-block w-2 h-2 bg-brandOrange rounded-full mr-1.5 animate-pulse"></span>
                    Live Classes
                </div>
            </div>

            <div class="space-y-5">
                <div class="inline-flex items-center gap-2 bg-orange-50 text-brandOrange text-xs font-bold tracking-wider uppercase px-4 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Global Communication
                </div>
                <h3 class="font-serif font-bold text-2xl lg:text-3xl text-slate-500 leading-tight">
                    Connect With the World
                </h3>
                <p class="text-sm text-brandTextGray leading-relaxed">
                    English is the most widely spoken second language in the world. Whether you're traveling, networking, or making friends online, English breaks down barriers and brings people together across 100+ countries.
                </p>
                <p class="text-sm text-brandTextGray leading-relaxed">
                    Our interactive classroom environment helps you practice real conversations — just like the ones you'll have in the real world. From group discussions to presentations, every session builds your confidence.
                </p>
                <div class="flex items-center gap-6 pt-2">
                    <div>
                        <p class="text-xl font-bold text-slate-800">100+</p>
                        <p class="text-[11px] text-brandTextGray font-medium">Countries</p>
                    </div>
                    <div class="w-px h-10 bg-gray-200"></div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">60%</p>
                        <p class="text-[11px] text-brandTextGray font-medium">Web Content</p>
                    </div>
                    <div class="w-px h-10 bg-gray-200"></div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">#1</p>
                        <p class="text-[11px] text-brandTextGray font-medium">Lingua Franca</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ -->

    <section class="w-full bg-[#F8F9FA] py-10 px-6 font-sans">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
            <h2 class="font-serif font-bold text-3xl text-brandOchre tracking-tight">
                Popular Courses
            </h2>
        
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $cardColors = ['orange', 'blue', 'green']; ?>
            <?php foreach ($popularModules as $index => $module): ?>
            <?php $color = $cardColors[$index % count($cardColors)]; ?>
            <div class="group relative bg-white rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500 hover:shadow-2xl">
                
                <div class="absolute inset-0 bg-gradient-to-br from-<?php echo $color; ?>-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

                <div class="relative h-64 w-full overflow-hidden">
                    <?php if (!empty($module['module_image'])): ?>
                        <img src="../uploads/modules/<?php echo htmlspecialchars($module['module_image']); ?>" alt="<?php echo htmlspecialchars($module['module_name']); ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-<?php echo $color; ?>-50 to-amber-50 flex items-center justify-center">
                            <svg class="w-16 h-16 text-<?php echo $color; ?>-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute top-4 left-4 z-20">
                        <span class="bg-white/90 backdrop-blur-sm text-<?php echo $color; ?>-600 text-[10px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-full border border-<?php echo $color; ?>-200">
                            <?php echo htmlspecialchars($module['course_name']); ?>
                        </span>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <div class="bg-white/90 backdrop-blur-sm text-xs font-medium px-3 py-1 rounded-full border border-gray-200 text-gray-600">
                            <?php echo htmlspecialchars($module['level'] ?? 'Beginner'); ?>
                        </div>
                    </div>
                </div>

                <div class="p-7 flex-1 flex flex-col justify-between relative z-20">
                    <div>
                        <h3 class="font-serif font-bold text-[#0F172A] text-md leading-snug mb-4 group-hover:text-[#FF8A00] transition-colors duration-300 line-clamp-2">
                            <?php echo htmlspecialchars($module['module_name']); ?>
                        </h3>

                        <div class="flex items-center justify-between mb-4">
                            <?php if (!empty($module['price']) && $module['price'] > 0): ?>
                                <div class="text-sm font-bold text-[#A87034]"><?php echo number_format($module['price']); ?> MMK</div>
                            <?php else: ?>
                                <div class="text-sm font-bold text-green-600">Free</div>
                            <?php endif; ?>
                            <div class="flex items-center gap-1 text-xs text-brandTextGray">
                                <span class="text-slate-400">📋</span> <?php echo (int)$module['total_lessons']; ?> Lessons
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-medium text-[#566473]">
                            <span class="flex items-center gap-1">
                                <span class="text-slate-400">👤</span> <?php echo htmlspecialchars($module['instructor_name']); ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
    <a href="details.php?module_id=<?php echo $module['module_id']; ?>"
   class="flex-1 text-center text-xs font-medium py-3 rounded-xl transition-all duration-300
          border border-gray-400 text-gray-500
          hover:bg-gray-500 hover:text-white hover:border-gray-500
          hover:shadow-[0_0_15px_rgba(156,163,175,0.6)]">
   View Details
</a>

    <a href="<?php echo (isset($_SESSION['user_id']) && in_array($module['module_id'], $enrolledModuleIds)) ? 'my_learning.php' : (isset($_SESSION['user_id']) ? 'enroll.php' : '../auth/login.php'); ?>"
       class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
              border border-orange-600 text-orange-600
              hover:bg-orange-600 hover:text-white
              hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
       <?php echo (isset($_SESSION['user_id']) && in_array($module['module_id'], $enrolledModuleIds)) ? 'Learn Now' : 'Enroll Now'; ?>
    </a>
</div>
                    </div>
                </div>

                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-<?php echo $color; ?>-400 to-<?php echo $color; ?>-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="w-full bg-[#F8F9FA] py-10 px-6 font-sans">
    <div class="max-w-7xl mx-auto">
<!-- Row 2: Text Left + Image Right (Gradient Fallback) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center mb-16 lg:mb-24">
            
            <div class="space-y-5 order-2 lg:order-1">
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 text-xs font-bold tracking-wider uppercase px-4 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Career Growth
                </div>
                <h3 class="font-serif font-bold text-2xl lg:text-3xl text-slate-500 leading-tight">
                    Unlock Better Opportunities
                </h3>
                <p class="text-sm text-brandTextGray leading-relaxed">
                    In today's competitive job market, English proficiency is often the deciding factor between candidates. Multinational companies across Myanmar and beyond actively seek employees who can communicate in English.
                </p>
                <p class="text-sm text-brandTextGray leading-relaxed">
                    Studies show that bilingual professionals earn up to 20% more than their peers. From client meetings to email writing, English fluency directly impacts your career trajectory and earning potential.
                </p>
                <!-- Highlight quote -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-5 border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-slate-700 italic leading-relaxed">"English is not just a skill — it's an investment that pays for itself throughout your career."</p>
                </div>
            </div>

            <div class="relative order-1 lg:order-2">
                <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-100">
                    <div class="relative order-1 lg:order-2">
        <img src="../assets/career.png" alt="Career Growth Illustration" class="w-full h-auto rounded-3xl shadow-xl border border-gray-100">
    </div>
                </div>
            
                <!-- Floating stat badge -->
                <div class="absolute -bottom-5 -left-3 lg:-left-6 bg-blue-600 text-white rounded-2xl px-6 py-4 shadow-lg shadow-blue-200">
                    <p class="text-2xl font-bold leading-none">+20%</p>
                    <p class="text-[10px] font-medium mt-1 opacity-90 uppercase tracking-wider">Higher Income</p>
                </div>
                
            </div>
        </div>
    </div>
</section>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showNextSlide() {
            slides[currentSlide].classList.remove('active');
            slides[currentSlide].classList.add('opacity-0');

            currentSlide = (currentSlide + 1) % totalSlides;

            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('active');
        }

        // Auto-advance slides every 4.5 seconds
        setInterval(showNextSlide, 4500);
    </script>
</body>
</html>

<?php include_once('../users/about.php');?>
<!--include_once('../users/contact.php'); -->

<?php include_once('../includes/footer.php');?>