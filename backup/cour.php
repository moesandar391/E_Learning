<?php 
include_once('../includes/header.php');
?>

<!-- Light Theme Container with Orange Accent -->
<section class="px-10 py-12 min-h-screen bg-gray-50">
    <h1 class=" text-brandOchre italic font-bold text-4xl mb-2">OUR ENGLISH COURSES</h1>
    <p class="text-gray-600 mb-8">Comprehensive pathways to master the English language.</p>
    
    <!-- Search and Filter Bar -->
    <div class="flex items-center gap-4 mb-10">
        <select class="bg-white border border-gray-200 rounded-lg p-3 text-sm focus:outline-none focus:border-orange-500 text-gray-700 shadow-sm">
            <option>All Levels</option>
            <option>Beginner</option>
            <option>Intermediate</option>
        </select>
        <input type="text" placeholder="Search For Courses" class="bg-white border border-gray-200 rounded-lg p-3 w-full max-w-lg text-sm outline-none focus:border-orange-500 text-gray-700 shadow-sm transition">
    </div>

    <!-- Category Tags -->
    <div class="flex gap-3 mb-12 flex-wrap">
    <button onclick="filterCourses('All')" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">All</button>
    <button onclick="filterCourses('Cambridge')" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">Cambridge</button>
    <button onclick="filterCourses('Grammar')" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">English Grammar</button>
    <button onclick="filterCourses('Writing')" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">Writing Course</button>
    <button onclick="window.location.href='courses.php?filter=General%20English'" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">General English</button>
    <button onclick="filterCourses('English for Kids')" class="px-6 py-2 bg-white border border-gray-200 rounded-full hover:bg-orange-600 hover:text-white transition shadow-sm text-gray-700">English for Kids</button>
</div>

    <!-- Course Grid Container -->
    <div id="course-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- JavaScript will inject cards here -->
    </div>
</section>

<script>
// Course Data
const allCourses = [
    // Writing Courses
    { title: "Great Writing (Foundation)", cat: "Writing", level: "Beginner", ch: 2, les: 10, status: "Ongoing" },
    { title: "Great Writing 1", cat: "Writing", level: "Beginner", ch: 3, les: 18, status: "Ongoing" },
    { title: "Great Writing 2", cat: "Writing", level: "Intermediate", ch: 4, les: 20, status: "New" },
    
    // Grammar Courses
    { title: "Grammar Level 1", cat: "Grammar", level: "Intermediate", ch: 4, les: 24, status: "New" },
    { title: "Grammar Level 2", cat: "Grammar", level: "Intermediate", ch: 5, les: 28, status: "Ongoing" },
    
    // Cambridge Courses
    { title: "Cambridge - Starters", cat: "Cambridge", level: "Beginner", ch: 2, les: 12, status: "Ongoing" },
    { title: "Cambridge - Movers", cat: "Cambridge", level: "Beginner", ch: 3, les: 15, status: "New" },

    // General English
    { title: "General English (Level 1)", cat: "General English", level: "Beginner", ch: 4, les: 20, status: "Ongoing" },
    { title: "General English (Level 2)", cat: "General English", level: "Intermediate", ch: 5, les: 25, status: "Ongoing" },
    
    // English for Kids
    { title: "Kids English: Fun Start", cat: "English for Kids", level: "Beginner", ch: 3, les: 12, status: "New" },
    { title: "Kids English: Storytelling", cat: "English for Kids", level: "Intermediate", ch: 4, les: 16, status: "Ongoing" }

];

// Filtering Function
function filterCourses(category) {
    const grid = document.getElementById('course-grid');
    grid.innerHTML = ''; 
    
    const filtered = (category === 'All') ? allCourses : allCourses.filter(c => c.cat === category);
    
    filtered.forEach(c => {
        grid.innerHTML += `
<div class="cursor-pointer bg-white rounded-3xl p-5 border border-gray-100 
            shadow-[0_4px_20px_rgba(0,0,0,0.05)] 
            hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] 
            hover:-translate-y-2 
            transition-all duration-300 ease-in-out 
            relative overflow-hidden">
    
    <div class="absolute -left-8 top-4 w-32 bg-orange-500 text-white text-xs font-bold text-center rotate-[-45deg] z-10 shadow-md">
        ${c.status}
    </div>
    
    <div class="h-48 bg-gray-50 rounded-2xl mb-6 flex flex-col justify-between p-4 relative">
        <div class="self-end bg-white text-gray-600 text-xs px-3 py-1 rounded-full border border-gray-200 shadow-sm">${c.level}</div>
        <div class="text-orange-500 text-center font-bold text-2xl">Icon Area</div>
    </div>
    
    <span class="bg-orange-50 text-orange-600 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider">ENGLISH</span>
    
    <h3 class="text-gray-900 text-xl font-bold mt-3 mb-2 transition-colors duration-300">${c.title}</h3>
    
    <div class="text-gray-500 text-sm mb-6">${c.ch} Chapters • ${c.les} Lessons</div>
    
    <div class="flex items-center gap-3">
        <div class="bg-gray-50 text-gray-700 text-[10px] px-3 py-3 rounded-full border border-gray-200 flex-1 text-center">178 Enrolled</div>
        <button onclick="window.location.href='enroll.php?course=${encodeURIComponent(c.title)}'" 
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-full transition flex-1">
            Enroll Now
        </button>
    </div>
</div>`;
    });
}

// Initial Page Load
filterCourses('All');
</script>
<?php 
include_once('../includes/footer.php');
?>