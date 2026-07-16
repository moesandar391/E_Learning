<?php
require_once '../config/db.php';
include_once('../includes/header.php');

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';

// Base query
$sql = "SELECT m.id AS module_id, m.name AS module_name, m.image AS module_image, m.price,
               c.level, c.id AS course_id, c.course_name, c.instructor_name, COUNT(l.id) AS total_lessons
        FROM modules m
        JOIN courses c ON m.course_id = c.id
        LEFT JOIN lessons l ON m.id = l.module_id
        WHERE 1=1"; // '1=1' makes it easy to append dynamic AND conditions

$params = [];
$types = "";

if ($filter !== 'All' && !empty($filter)) {
    $sql .= " AND c.course_name LIKE ?";
    $params[] = "%" . $filter . "%";
    $types .= "s";
}

if (!empty($search)) {
    $sql .= " AND (m.name LIKE ? OR c.course_name LIKE ?)";
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
    $types .= "ss";
}

if (!empty($level)) {
    $sql .= " AND c.level = ?";
    $params[] = $level;
    $types .= "s";
}

$sql .= " GROUP BY m.id";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$modules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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

<section class="px-10 py-12 min-h-screen bg-gray-50">
    <h1 class="text-brandOchre text-center italic font-bold text-4xl mb-2">OUR ENGLISH COURSES</h1>
    <p class="text-gray-600 text-center mb-8">Comprehensive pathways to master the English language.</p>
    
    <!-- Search and Filter Bar -->
    <form action="courses.php" method="GET" class="flex items-center justify-center gap-4 mb-10">
    <select name="level" onchange="this.form.submit()" class="bg-white border border-orange-200 rounded-lg p-3 text-sm focus:outline-none focus:border-orange-500 text-gray-700 shadow-sm">
        <option value="">All Levels</option>
        <option value="Beginner" <?php echo (isset($_GET['level']) && $_GET['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
        <option value="Intermediate" <?php echo (isset($_GET['level']) && $_GET['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
    </select>
    
    <input type="text" name="search" placeholder="Search For Courses" 
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
           class="bg-white border border-orange-200 rounded-lg p-3 w-full max-w-lg text-sm outline-none focus:border-orange-500 text-gray-700 shadow-sm transition">
    
    <button type="submit" class="hidden">Search</button>
</form>

    <div class="flex gap-3 mb-12 flex-wrap items-center justify-center">
        <?php 
        $buttons = ['All', 'Cambridge', 'English Grammar', 'Writing Course', 'General English', 'English for Kids'];
        foreach ($buttons as $btn): 
            $activeClass = ($filter == $btn) ? 'bg-orange-600 text-white' : 'bg-white text-gray-700';
        ?>
            <a href="?filter=<?php echo urlencode($btn); ?>" 
               class="px-6 py-2 border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm <?php echo $activeClass; ?>">
               <?php echo $btn; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (count($modules) > 0): ?>
            <?php foreach ($modules as $module): ?>
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                    
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        <div class="absolute top-0 left-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rotate-[-45deg] translate-y-3 -translate-x-3 z-10">
                            Ongoing
                        </div>
                        <?php if (!empty($module['module_image'])): ?>
                            <img src="../uploads/modules/<?php echo htmlspecialchars($module['module_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($module['module_name']); ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-orange-50 to-amber-50">
                                <svg class="w-16 h-16 text-orange-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-orange-300 text-xs font-medium">No Image</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm border border-gray-200 text-gray-600 text-xs font-medium px-3 py-1 rounded-full z-10">
                            <?php echo htmlspecialchars($module['level'] ?? 'Beginner'); ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <span class="text-orange-500 text-[10px] font-bold uppercase tracking-wider">
                            <?php echo htmlspecialchars($module['course_name']); ?>
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 mt-1 mb-2">
                            <?php echo htmlspecialchars($module['module_name']); ?>
                        </h3>
                        <p class="text-gray-500 text-sm mb-1">
                            <?php echo htmlspecialchars($module['instructor_name']); ?>
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-400 text-xs">
                                <?php echo (int)$module['total_lessons']; ?> Lessons
                            </p>
                            <?php if (!empty($module['price']) && $module['price'] > 0): ?>
                                <p class="text-orange-600 font-bold text-sm">
                                    <?php echo number_format($module['price']); ?> MMK
                                </p>
                            <?php endif; ?>
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
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 col-span-full text-center py-10">No modules found for this category.</p>
        <?php endif; ?>
    </div>
</section>
<?php 
include_once('../includes/footer.php'); ?>