<?php
session_start();
require_once '../config/db.php';
// Include your site header (containing navigation, styles, and session_start)
include_once('../includes/header.php');

// Simple security: check if a course is provided
$courseTitle = htmlspecialchars($_GET['course'] ?? 'General English');
?>

<div class="max-w-7xl mx-auto p-6 bg-gray-50 text-white text-left">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <main class="lg:col-span-8 space-y-6">
            <h1 class="text-4xl font-bold"><?php echo $courseTitle; ?></h1>
            
            <!-- <div class="aspect-video bg-black rounded-xl border border-gray-700 flex items-center justify-center overflow-hidden shadow-2xl">
                <p class="text-gray-400">Video Player Container</p>
            </div> -->
            <div class="aspect-video bg-gray-50 rounded-xl border border-gray-700 overflow-hidden shadow-2xl">
                <iframe class="w-full h-full" src="../assets/E_Conversation.mp4" allowfullscreen></iframe>
            </div>
            
            <h2 class="text-2xl font-semibold">Lesson 1: 100_Common_English_Conversation</h2>
            <p class="text-gray-400 leading-relaxed">
                Welcome to the first lesson. Follow along with the instructor to set up your English speaking skill is best.
        </main>

        <aside class="lg:col-span-4">
            <div class="bg-[#111827] p-6 rounded-xl border border-gray-700">
                <h3 class="font-bold text-xl mb-4 border-b border-gray-700 pb-2">Course Outline</h3>
                
                <input type="text" placeholder="Search For Lessons" class="w-full bg-[#0b1120] border border-gray-700 p-2 rounded-lg mb-4 text-sm">

                <div class="space-y-4">
                    <div class="cursor-pointer group">
                        <p class="font-semibold text-orange-500 group-hover:text-white transition">Chapter-1 Introduction & Data Types</p>
                        <p class="text-xs text-gray-500 mt-1">7 Lessons</p>
                    </div>
                    <div class="cursor-pointer group">
                        <p class="font-semibold text-orange-500 group-hover:text-white transition">Chapter-2 Conditions & Loopings</p>
                        <p class="text-xs text-gray-500 mt-1">4 Lessons</p>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</div>

<?php 
// Include your site footer
include_once('../includes/footer.php'); 
?>