<?php
/**
 * Check user enrollment status for a course/module
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $module_id Module/Course ID
 * @return string|false Returns "Pending", "Confirmed", "Rejected", or false if no enrollment
 */
function checkEnrollmentStatus($conn, $user_id, $module_id) {
    if (!$conn || !$user_id || !$module_id) {
        return false;
    }
    
    $sql = "SELECT status FROM enrollments WHERE user_id = ? AND module_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ii", $user_id, $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['status'];
    }
    
    $stmt->close();
    return false;
}

/**
 * Check if enrollment already exists (for duplicate prevention)
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $module_id Module/Course ID
 * @return array|false Returns enrollment data if exists, false otherwise
 */
function getExistingEnrollment($conn, $user_id, $module_id) {
    if (!$conn || !$user_id || !$module_id) {
        return false;
    }
    
    $sql = "SELECT id, status, created_at FROM enrollments WHERE user_id = ? AND module_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ii", $user_id, $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
    
    $stmt->close();
    return false;
}

/**
 * Generate button HTML based on enrollment status
 * 
 * @param mysqli $conn Database connection
 * @param int|null $user_id User ID (null if not logged in)
 * @param int $module_id Module/Course ID
 * @param string $base_url Base URL for redirects
 * @return string HTML button markup
 */
function getEnrollmentButton($conn, $user_id, $module_id, $base_url = '../users/') {
    if (!$user_id) {
        // Not logged in - show Enroll Now, redirect to login
        $loginUrl = '../auth/login.php?redirect=' . urlencode($base_url . 'enroll.php?module_id=' . $module_id);
        return '<a href="' . $loginUrl . '" 
                   class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
                          border border-orange-600 text-orange-600
                          hover:bg-orange-600 hover:text-white
                          hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
                    Enroll Now
                </a>';
    }
    
    $status = checkEnrollmentStatus($conn, $user_id, $module_id);
    
    // Case-insensitive comparison
    $statusLower = strtolower($status);
    
    if ($status === false || $statusLower === 'rejected') {
        // No enrollment or Rejected - show Enroll Now
        return '<a href="enroll.php?module_id=' . urlencode($module_id) . '" 
                   class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
                          border border-orange-600 text-orange-600
                          hover:bg-orange-600 hover:text-white
                          hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
                    Enroll Now
                </a>';
    }
    
    if ($statusLower === 'pending') {
        // Pending - show Waiting for Confirmation (disabled)
        return '<a href="javascript:void(0)" 
                   class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
                          border border-yellow-500 text-yellow-600 bg-yellow-50 cursor-not-allowed
                          opacity-80">
                    ⏳ Waiting for Confirmation
                </a>';
    }
    
    if ($statusLower === 'confirmed') {
        // Confirmed - show Learn Now
        return '<a href="lesson.php?module_id=' . urlencode($module_id) . '" 
                   class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
                          border border-green-600 text-green-600
                          hover:bg-green-600 hover:text-white
                          hover:shadow-[0_0_20px_rgba(22,163,74,0.6)]">
                    ▶ Learn Now
                </a>';
    }
    
    // Default fallback - show Enroll Now
    return '<a href="enroll.php?module_id=' . urlencode($module_id) . '" 
               class="flex-[2] text-center text-sm font-bold py-3 rounded-xl transition-all duration-300
                      border border-orange-600 text-orange-600
                      hover:bg-orange-600 hover:text-white
                      hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
                Enroll Now
            </a>';
}
?>