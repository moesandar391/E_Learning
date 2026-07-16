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

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$enrolledModuleIds = [];
if ($userId) {
    $enrollCheck = $conn->prepare("SELECT module_id FROM enrollments WHERE user_id = ? AND status = 'confirmed'");
    $enrollCheck->bind_param("i", $userId);
    $enrollCheck->execute();
    $enrollResult = $enrollCheck->get_result();
    while ($row = $enrollResult->fetch_assoc()) {
        $enrolledModuleIds[] = $row['module_id'];
    }
}
?>

<section class="w-full bg-[#F8F9FA] py-12 px-6 font-sans">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
            <h2 class="font-serif font-bold text-3xl text-[#0F172A] tracking-tight">
                Popular Courses
            </h2>
            <div>
                <a href="viewAllCourses.php" class="inline-flex items-center gap-2 bg-brandOrange hover:bg-brandOrangeHover text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-200 shadow-sm">
                    <span>Load More Courses</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
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

    <a href="<?php 
    echo (isset($_SESSION['user_id']) && in_array($module['module_id'], $enrolledModuleIds)) 
        ? 'my_learning.php' 
        : (isset($_SESSION['user_id']) 
            ? 'enroll.php?module_id=' . urlencode($module['module_id']) 
            : '../auth/login.php');
?>" 
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
<?php 
include_once('../includes/footer.php');
?>