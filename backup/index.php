<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishLearn - Learn English. Shape Your Future.</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="../TailwindCLI/src/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .hero-bg {
            background: radial-gradient(circle at 85% 30%, #e0f2fe 0%, #f1f5f9 45%, #f8fafc 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="antialiased text-slate-800 selection:bg-blue-500 selection:text-white">

    <div class="hero-bg w-full pb-12">
        
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2.5 group cursor-pointer">
                <div class="text-blue-600 text-3xl transition-transform group-hover:scale-105 duration-300">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight leading-none">English<span class="text-blue-600">Learn</span></h1>
                    <span class="text-[10px] text-slate-400 font-medium tracking-wide block mt-0.5">Learn English. Shape Your Future.</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center space-x-10 font-semibold text-slate-600 text-[15px]">
                <a href="#" class="text-blue-600 relative after:absolute after:bottom-[-6px] after:left-0 after:w-full after:h-[2px] after:bg-blue-600">Home</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Courses</a>
                <a href="#" class="hover:text-blue-600 transition-colors">About us</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Contact</a>
            </nav>

            <div>
                <a href="#" class="bg-[#0b1b3d] hover:bg-slate-900 text-white font-bold px-6 py-2.5 rounded-xl text-[14px] shadow-md transition-all duration-300">Get Started</a>
            </div>
        </header>

        <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 lg:mt-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 bg-white px-3.5 py-1.5 rounded-full shadow-sm border border-slate-100">
                    <span class="text-blue-500 text-xs"><i class="fa-solid fa-star"></i></span>
                    <span class="text-xs font-bold text-slate-500 tracking-wide">The Smarter Way to Learn English</span>
                </div>
                
                <h2 class="text-4xl sm:text-5xl lg:text-[56px] font-extrabold text-slate-900 tracking-tight leading-[1.1]">
                    Learn English Confidently <span class="text-blue-600">Online</span>
                </h2>
                
                <p class="text-slate-500 font-medium text-base sm:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Improve your speaking, listening, reading and writing skills with expert-led courses and interactive lessons.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="#" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                        Explore Courses <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#" class="w-full sm:w-auto text-center bg-white border border-slate-200 text-blue-600 font-bold px-7 py-3.5 rounded-xl hover:bg-slate-50 transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                        <i class="fa-regular fa-circle-play text-base"></i> Watch Demo
                    </a>
                </div>

                <div class="pt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left max-w-xl mx-auto lg:mx-0">
                    <div class="flex items-start space-x-3">
                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs">Expert Teachers</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Learn from certified English experts</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs">Interactive Lessons</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Engaging content with real-life practice</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs">Track Progress</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Monitor your improvement and achieve goals</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 relative flex justify-center lg:justify-end mt-6 lg:mt-0">
                <div class="relative w-full max-w-lg lg:max-w-xl">
                    <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=1000&q=80" 
                         alt="Online Student Learning" 
                         class="rounded-[32px] object-cover w-full h-[460px] shadow-xl shadow-slate-200/60" />
                    
                    <div class="absolute left-[-20px] top-1/3 glass-card rounded-2xl p-3 shadow-md border border-white flex items-center space-x-3 max-w-[150px]">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-emerald-600 block">Learn</span>
                            <p class="text-[10px] text-slate-400 font-medium">Anytime, Anywhere</p>
                        </div>
                    </div>

                    <div class="absolute right-[-15px] top-12 bg-white rounded-2xl p-3.5 shadow-lg border border-slate-50 flex items-center space-x-3 min-w-[150px]">
                        <div>
                            <span class="text-[13px] font-bold text-blue-600 block">Speak</span>
                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">with Confidence</p>
                        </div>
                        <div class="text-blue-500 text-lg shrink-0 pl-1">
                            <i class="fa-solid fa-waveform-lines"></i>
                        </div>
                    </div>

                    <div class="absolute right-[-10px] bottom-24 bg-white rounded-2xl p-3.5 shadow-lg border border-slate-50 flex items-center space-x-4 min-w-[140px]">
                        <div>
                            <span class="text-[13px] font-bold text-orange-500 block">Grow</span>
                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Your Future</p>
                        </div>
                        <div class="text-orange-500 text-xl shrink-0">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Popular Courses</h3>
            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
                View All Courses <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-regular fa-comment-dots"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-bold text-slate-900 text-[15px] leading-tight">Spoken English</h5>
                    <p class="text-[12px] text-slate-400 font-medium leading-normal">Improve your speaking skills for daily conversations.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 pt-2 group-hover:translate-x-0.5 transition-transform">
                        Learn More <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-headphones-simple"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-bold text-slate-900 text-[15px] leading-tight">Listening Skills</h5>
                    <p class="text-[12px] text-slate-400 font-medium leading-normal">Enhance your listening comprehension skillsets.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 pt-2 group-hover:translate-x-0.5 transition-transform">
                        Learn More <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-bold text-slate-900 text-[15px] leading-tight">Reading Skills</h5>
                    <p class="text-[12px] text-slate-400 font-medium leading-normal">Read with complete confidence and understand vocabulary.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 pt-2 group-hover:translate-x-0.5 transition-transform">
                        Learn More <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-400 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-bold text-slate-900 text-[15px] leading-tight">Writing Skills</h5>
                    <p class="text-[12px] text-slate-400 font-medium leading-normal">Write clearly, effectively, and professionally in English.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 pt-2 group-hover:translate-x-0.5 transition-transform">
                        Learn More <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

 <!-- <p class="text-sm ml-2 <?php echo ($activeLesson && $activeLesson['id'] == $lesson['id']) ? 'text-white font-bold' : 'text-gray-400 hover:text-white'; ?>">
                                <?php echo htmlspecialchars($displayTitle); ?>
                            </p> -->

</body>
</html>