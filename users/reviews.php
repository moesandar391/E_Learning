<?php
session_start();
require_once '../config/db.php';
include_once('../includes/header.php');

$reviews = [];
$result = $conn->query("
    SELECT r.rating, r.review, u.name AS user_name, m.name AS module_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN modules m ON r.module_id = m.id
    ORDER BY r.created_at DESC
");
if ($result) {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<?php if (!empty($reviews)): ?>
<section class="w-full bg-white dark:bg-gray-800 py-16 px-6 font-sans" id="testimonials">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block text-[10px] font-bold text-brandOrange uppercase tracking-[0.2em] mb-3">Testimonials</span>
            <h2 class="text-3xl lg:text-4xl font-serif font-bold text-slate-500 dark:text-slate-200">What Our Students Say</h2>
            <p class="text-sm text-brandTextGray dark:text-slate-200 mt-3 max-w-lg mx-auto">Real feedback from our learners</p>
        </div>

        <!-- Slider Container -->
        <div class="relative">
            
            <div id="reviewSlider" class="relative">
                <?php foreach ($reviews as $index => $rv): ?>
                <div class="review-slide transition-opacity duration-300 ease-in-out <?php echo $index === 0 ? 'relative opacity-100' : 'absolute inset-0 opacity-0 pointer-events-none'; ?>">
                    <div class="bg-gray-50 rounded-2xl p-8 md:p-10 border border-gray-100 shadow-sm flex flex-col justify-between min-h-[280px]">
                        
                        <!-- Quote Icon -->
                        <div class="mb-4">
                            <svg class="w-8 h-8 text-brandOrange/30" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>

                        <!-- Stars -->
                        <div class="flex items-center gap-1 mb-4">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-5 h-5 <?php echo $i <= $rv['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>

                        <!-- Review Text -->
                        <p class="text-base text-gray-600 leading-relaxed mb-6 flex-grow text-center italic">
                            "<?php echo htmlspecialchars($rv['review']); ?>"
                        </p>

                        <!-- User Info -->
                        <div class="flex items-center justify-center gap-3 pt-4 border-t border-gray-200">
                            <div class="w-10 h-10 rounded-full bg-brandOrange text-white flex items-center justify-center text-sm font-bold shadow-md">
                                <?php echo strtoupper(substr($rv['user_name'], 0, 1)); ?>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($rv['user_name']); ?></p>
                                <p class="text-[11px] text-gray-400"><?php echo htmlspecialchars($rv['module_name']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation -->
            <button id="prevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 md:-translate-x-5 bg-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:bg-brandOrange hover:text-white transition-all duration-300 z-10 hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 md:translate-x-5 bg-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:bg-brandOrange hover:text-white transition-all duration-300 z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Indicators -->
        <div id="dotsContainer" class="flex items-center justify-center gap-2 mt-8"></div>

    </div>
</section>
<?php else: ?>
<div class="min-h-screen bg-gray-50 py-24 px-4">
    <div class="max-w-lg mx-auto text-center">
        <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        <p class="text-lg font-bold text-gray-500">No Reviews Yet</p>
        <p class="text-sm text-gray-400 mt-2">Be the first to share your experience!</p>
        <a href="my_review.php" class="mt-6 inline-block px-6 py-3 bg-brandOrange text-white text-sm font-bold rounded-xl hover:bg-brandOrangeHover transition-colors">Write a Review</a>
    </div>
</div>
<?php endif; ?>

<script>
(function() {
    var slides = document.querySelectorAll('.review-slide');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var dotsContainer = document.getElementById('dotsContainer');
    if (!slides.length) return;

    var current = 0;

    function showSlide(index) {
        slides.forEach(function(s, i) {
            if (i === index) {
                s.classList.remove('absolute', 'inset-0', 'opacity-0', 'pointer-events-none');
                s.classList.add('relative', 'opacity-100');
            } else {
                s.classList.remove('relative', 'opacity-100');
                s.classList.add('absolute', 'inset-0', 'opacity-0', 'pointer-events-none');
            }
        });
        if (dotsContainer) {
            var dots = dotsContainer.querySelectorAll('button');
            dots.forEach(function(d, i) {
                d.classList.toggle('bg-brandOrange', i === index);
                d.classList.toggle('bg-gray-300', i !== index);
            });
        }
        if (prevBtn) prevBtn.classList.toggle('hidden', index === 0);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (current > 0) { current--; showSlide(current); }
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (current < slides.length - 1) { current++; showSlide(current); }
        });
    }

    if (dotsContainer) {
        slides.forEach(function(_, i) {
            var dot = document.createElement('button');
            dot.className = 'w-2.5 h-2.5 rounded-full transition-all duration-300 ' + (i === 0 ? 'bg-brandOrange' : 'bg-gray-300');
            dot.addEventListener('click', function() { current = i; showSlide(i); });
            dotsContainer.appendChild(dot);
        });
    }
})();
</script>

<?php include_once('../includes/footer.php'); ?>