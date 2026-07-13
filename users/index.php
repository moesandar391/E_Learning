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
                 LIMIT 3";
$popularResult = $conn->query($popularQuery);
$popularModules = $popularResult->fetch_all(MYSQLI_ASSOC);
?>
    <main class="w-full max-w-7xl mx-auto px-6 py-8 md:py-16 flex-1 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
        
        <div class="lg:col-span-5 space-y-6">
            <div class="inline-block bg-brandOrange text-white text-xs font-bold tracking-wider uppercase px-4 py-2 rounded-lg shadow-sm">
                EMPOWERING GLOBAL COMMUNICATORS
            </div>

            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-slate-900 leading-tight">
                Master English for the <br>
                <span class="text-brandOchre italic font-bold">Global Stage</span>
            </h1>

            <p class="text-base text-brandTextGray leading-relaxed max-w-lg">
                Unlock international opportunities with structured, professional English courses designed for ambitious learners. From business mastery to academic excellence.
            </p>

            <div class="pt-4 flex flex-wrap gap-4">
    <a href="<?php echo isset($_SESSION['user_id']) ? '../users/enroll.php' : '../auth/login.php'; ?>" 
       class="group relative px-7 py-3.5 font-semibold text-white rounded-xl transition-all shadow-md overflow-hidden border-2 border-brandOrange">
        
        <span class="absolute inset-0 bg-brandOrange -translate-x-full transition-transform duration-500 ease-out group-hover:translate-x-0 -z-10"></span>
        
        <span class="relative z-10 transition-colors duration-500 text-brandOrange hover:text-white">Enroll Now</span>
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

    <section class="w-full bg-[#F8F9FA] py-16 px-6 font-sans">
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
                        <h3 class="font-serif font-bold text-[#0F172A] text-xl leading-snug mb-4 group-hover:text-[#FF8A00] transition-colors duration-300 line-clamp-2">
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

    <a href="<?php echo isset($_SESSION['user_id']) ? 'enroll.php?module_id=' . $module['module_id'] : '../auth/login.php'; ?>"
       class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
              border border-orange-600 text-orange-600
              hover:bg-orange-600 hover:text-white
              hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
       Enroll Now
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

<section class="w-full bg-white py-12 px-6 font-sans">
    <div class="max-w-6xl mx-auto">
        
        <div class="w-full bg-[#4A607A] rounded-[32px] px-8 py-10 md:px-12 md:py-12 shadow-md flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            
            <div class="space-y-3 max-w-xl">
                <h2 class="font-serif italic font-bold text-3xl md:text-4xl text-white tracking-tight">
                    Start your journey today.
                </h2>
                <p class="text-sm md:text-base text-slate-200/90 font-medium leading-relaxed">
                    Join 50,000+ learners mastering English. New courses added every month.
                </p>
            </div>

            <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <div class="relative min-w-[260px] sm:w-80">
                    <input type="email" name="cta_email" placeholder="Enter your email" 
                        class="w-full bg-[#5C728D] text-white placeholder-slate-300/80 text-sm border-0 rounded-xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all duration-200">
                </div>
                
                <button type="submit" 
                    class="bg-[#FF8A00] hover:bg-[#E07A00] text-white text-sm font-bold px-6 py-3.5 rounded-xl whitespace-nowrap shadow-[0_4px_10px_rgba(0,0,0,0.15)] transition-all duration-200 active:scale-[0.98]">
                    Get Free Lesson
                </button>
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

// include_once('../users/about.php');
// include_once('../users/contact.php');


<?php include_once('../includes/footer.php');?>