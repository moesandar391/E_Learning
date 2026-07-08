<?php
require_once '../config/db.php';
include_once('../includes/header.php');

// 1. Get the filter from URL (Default to 'All')
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// 2. Build Query: Select modules and join with courses
$sql = "SELECT m.name AS module_name, c.level, c.id AS course_id, c.course_name
        FROM modules m
        JOIN courses c ON m.course_id = c.id";

// Apply filtering
if ($filter !== 'All' && !empty($filter)) {
    $searchTerm = "%" . $filter . "%";
    $sql .= " WHERE c.course_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$modules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<section class="px-10 py-12 min-h-screen bg-gray-50">
    <h1 class="text-orange-600 italic font-bold text-4xl mb-2">OUR ENGLISH COURSES</h1>
    <p class="text-gray-600 mb-8">Comprehensive pathways to master the English language.</p>
    
    <div class="flex gap-3 mb-12 flex-wrap">
        <?php 
        $buttons = ['All', 'Cambridge', 'Grammar', 'Writing', 'General English', 'English for Kids'];
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
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    
                    <div class="relative h-48 bg-gray-50 flex items-center justify-center">
                        <div class="absolute top-0 left-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rotate-[-45deg] translate-y-3 -translate-x-3">
                            Ongoing
                        </div>
                        <span class="text-orange-400 font-bold">Icon Area</span>
                        <div class="absolute top-4 right-4 bg-white border border-gray-200 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">
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
                        <p class="text-gray-500 text-sm mb-6">Course Modules</p>

                        <div class="flex gap-2">
                            <button class="flex-1 text-xs text-gray-500 bg-gray-50 border border-gray-200 py-3 rounded-xl cursor-default">
                                178 Enrolled
                            </button>
                            <a href="lessons.php?course_id=<?php echo $module['course_id']; ?>" 
                               class="flex-[2] text-center bg-orange-600 text-white text-sm font-bold py-3 rounded-xl hover:bg-orange-700 transition">
                                Enroll Now
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

<?php include_once('../includes/footer.php'); ?>