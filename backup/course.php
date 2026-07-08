<?php
require_once '../config/db.php';
include_once('../includes/header.php');

// 1. Get filter from URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

// 2. Prepare SQL
if ($filter) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_name = ?");
    $stmt->bind_param("s", $filter);
} else {
    $stmt = $conn->prepare("SELECT * FROM courses");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="max-w-7xl mx-auto p-6 bg-[#0b1120] text-white">
    <h1 class="text-3xl font-bold mb-8">Available Courses</h1>

    <div class="flex gap-4 mb-8">
        <button onclick="window.location.href='courses.php'" class="px-6 py-2 bg-gray-800 rounded-full hover:bg-orange-600 transition">All</button>
        <button onclick="window.location.href='courses.php?filter=General%20English'" class="px-6 py-2 bg-gray-800 rounded-full hover:bg-orange-600 transition">General English</button>
        <button onclick="window.location.href='courses.php?filter=Python%20From%20Scratch'" class="px-6 py-2 bg-gray-800 rounded-full hover:bg-orange-600 transition">Python From Scratch</button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while ($course = $result->fetch_assoc()): ?>
            <a href="lessons.php?course_id=<?php echo $course['id']; ?>" class="block bg-[#111827] p-6 rounded-xl border border-gray-700 hover:border-orange-500 transition">
                <h2 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($course['course_name']); ?></h2>
                <p class="text-sm text-gray-400 mb-4">Instructor: <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                <div class="flex justify-between items-center">
                    <span class="text-xs bg-gray-800 px-2 py-1 rounded"><?php echo $course['level']; ?></span>
                    <span class="text-green-400 font-bold"><?php echo ($course['price'] == 0) ? 'Free' : $course['price'] . ' MMK'; ?></span>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>