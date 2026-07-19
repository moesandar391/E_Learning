<?php 
require_once '../config/db.php';
include_once('../includes/header.php');

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$hasEnrollment = false;
if ($userId) {
    $enrollCheck = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND status = 'confirmed' LIMIT 1");
    $enrollCheck->bind_param("i", $userId);
    $enrollCheck->execute();
    $hasEnrollment = $enrollCheck->get_result()->num_rows > 0;
}
?>
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1 class="font-serif font-bold text-5xl md:text-6xl text-[#0F172A] dark:text-slate-100 mb-6">About Access Edu</h1>
            <p class="text-lg text-[#566473] dark:text-slate-300 max-w-3xl mx-auto">
                We empower global communicators through immersive English language learning. Our mission is to bridge cultures and unlock international opportunities for learners worldwide.
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20">
            <div class="lg:col-span-5 space-y-8">
                <div class="space-y-6">
                    <h2 class="font-serif font-bold text-3xl text-[#0F172A] dark:text-slate-100">Our Story</h2>
                    <p class="text-base text-[#566473] dark:text-slate-300 leading-relaxed">
                        Founded in 2020, Access Edu began as a small tutoring center in Singapore with a vision to make quality English education accessible to everyone. Over the years, we've grown into a global platform serving learners in over 50 countries.
                    </p>
                    <p class="text-base text-[#566473] dark:text-slate-300 leading-relaxed">
                        Our journey started with just 5 students and one passionate tutor. Today, we boast a community of over 50,000+ active learners who have mastered English and transformed their careers through our comprehensive programs.
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-6 pt-4">
                    <div class="space-y-2">
                        <div class="w-12 h-12 bg-[#FFEAD6] rounded-lg flex items-center justify-center text-[#A87034] dark:text-amber-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-[#A87034] dark:text-amber-300">50k+</div>
                        <div class="text-xs font-semibold text-[#566473] dark:text-slate-300">Active Learners</div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="w-12 h-12 bg-[#FFEAD6] rounded-lg flex items-center justify-center text-[#A87034] dark:text-amber-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-[#A87034] dark:text-amber-300">98%</div>
                        <div class="text-xs font-semibold text-[#566473] dark:text-slate-300">Satisfaction Rate</div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="w-12 h-12 bg-[#FFEAD6] rounded-lg flex items-center justify-center text-[#A87034] dark:text-amber-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0-4v2m0 6v2m0-6H4m16 0h-2m-4 0h-2m4 0v2m0 6v2m0-6H14"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-[#A87034] dark:text-amber-300">100+</div>
                        <div class="text-xs font-semibold text-[#566473] dark:text-slate-300">Expert Instructors</div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="w-12 h-12 bg-[#FFEAD6] rounded-lg flex items-center justify-center text-[#A87034] dark:text-amber-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.004 5.004 0 006.323 0M15 6l3-1M15 6l-3 9a5.004 5.004 0 006.323 0M9 12l3 6m0 0l3-6m-6 0V4m12 4v8m-4-4h8"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-[#A87034] dark:text-amber-300">25</div>
                        <div class="text-xs font-semibold text-[#566473] dark:text-slate-300">Years of Excellence</div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-7 relative max-w-2xl mx-auto w-full">
                <div class="absolute -bottom-4 -right-4 w-40 h-40 bg-[#FF8A00] rounded-3xl -z-10"></div>
                
                <div class="relative rounded-[32px] overflow-hidden shadow-2xl border border-gray-200">
                    <img src="../assets/about.png" alt="Access Edu Community" class="w-full h-auto object-cover block" onerror="this.src='https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=1200'">
                    
                    <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-[#1A4B6E]/90 to-transparent mix-blend-multiply pointer-events-none"></div>
                    <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-[140%] h-24 bg-[#1A4B6E] rounded-[100%] pointer-events-none"></div>
                </div>
            </div>
        </div>
        
        <section class="bg-white rounded-[32px] p-12 shadow-sm border border-gray-100">
            <div class="max-w-4xl mx-auto">
                <h2 class="font-serif font-bold text-3xl text-[#0F172A] dark:text-slate-100 mb-8 text-center">Our Mission & Values</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 bg-[#FFEAD6] rounded-full flex items-center justify-center text-[#A87034] dark:text-amber-300 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-[#0F172A] dark:text-slate-100">Excellence</h3>
                        <p class="text-sm text-[#566473] dark:text-slate-300 leading-relaxed">
                            We strive for teaching excellence through continuous improvement and proven methodologies that deliver real results for our learners.
                        </p>
                    </div>
                    
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 bg-[#FFEAD6] rounded-full flex items-center justify-center text-[#A87034] dark:text-amber-300 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-[#0F172A] dark:text-slate-100">Community</h3>
                        <p class="text-sm text-[#566473] dark:text-slate-300 leading-relaxed">
                            We believe in the power of community learning, where students support each other and grow together towards their goals.
                        </p>
                    </div>
                    
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 bg-[#FFEAD6] rounded-full flex items-center justify-center text-[#A87034] dark:text-amber-300 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.004 5.004 0 006.323 0M15 6l3-1M15 6l-3 9a5.004 5.004 0 006.323 0M9 12l3 6m0 0l3-6m-6 0V4m12 4v8m-4-4h8"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-[#0F172A] dark:text-slate-100">Innovation</h3>
                        <p class="text-sm text-[#566473] dark:text-slate-300 leading-relaxed">
                            We innovate continuously, leveraging technology to create personalized learning experiences that adapt to each student's unique needs.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="mt-20">
            <div class="bg-gradient-to-r from-[#1A4B6E] to-[#0F3147] rounded-[32px] p-12 text-white">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="font-serif font-bold text-3xl mb-4">Ready to Start Your English Journey?</h2>
                    <p class="text-base text-white/90 mb-8">
                        Join 50,000+ learners who have mastered English and unlocked new opportunities in their personal and professional lives.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo $hasEnrollment ? 'my_learning.php' : 'enroll.php'; ?>" class="bg-[#FF8A00] hover:bg-[#E07A00] text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-lg transform hover:scale-105">
                            <?php echo $hasEnrollment ? 'Learn Now' : 'Enroll Now'; ?>
                        </a>
                        <a href="contact.php" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl border border-white/20 transition-all duration-200">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script>
        // Scroll reveal animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.querySelectorAll('.font-serif, .space-y-4, .grid, .bg-white, .bg-gradient-to-r').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>
<?php 
include_once('../includes/footer.php');
?>