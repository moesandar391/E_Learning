<?php
/**
 * Flexible level-based learning helpers.
 *
 * Level is stored per-module (modules.level).
 * An optional previous module (modules.recommended_prev_module_id) is a SUGGESTION only:
 * it is never enforced, so users may enroll anywhere they like.
 */

if (!function_exists('getRecommendedPrevModule')) {
    /**
     * Return the recommended (optional) previous module for a given module.
     *
     * @param mysqli $conn
     * @param int    $module_id
     * @return array|null Info about the previous module, or null if none
     */
    function getRecommendedPrevModule($conn, $module_id) {
        if (!$conn || !$module_id) return null;

        $sql = "SELECT p.id AS prev_module_id, p.name AS prev_module_name,
                       p.level AS prev_level, pc.course_name AS prev_course_name,
                       m.id AS module_id, m.name AS module_name,
                       m.level AS module_level
                FROM modules m
                LEFT JOIN modules p ON p.id = m.recommended_prev_module_id AND p.status = 'active'
                LEFT JOIN courses pc ON pc.id = p.course_id
                WHERE m.id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || empty($row['prev_module_id'])) return null;
        return $row;
    }
}

if (!function_exists('getUserModuleProgress')) {
    /**
     * Determine a user's progress status for a module based on completed lessons.
     *
     * @param mysqli     $conn
     * @param int|null   $user_id  null when not logged in
     * @param int        $module_id
     * @return string|null 'completed' | 'in_progress' | 'not_started', or null when not logged in / no lessons
     */
    function getUserModuleProgress($conn, $user_id, $module_id) {
        if (!$conn || !$user_id || !$module_id) return null;

        $sql = "SELECT
                    (SELECT COUNT(*) FROM lessons WHERE module_id = ?) AS total_lessons,
                    (SELECT COUNT(*) FROM lessons l
                        JOIN lesson_progress lp
                          ON lp.lesson_id = l.id AND lp.user_id = ? AND lp.completed = 1
                     WHERE l.module_id = ?) AS completed_lessons";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("iii", $module_id, $user_id, $module_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $total = (int)($row['total_lessons'] ?? 0);
        $done  = (int)($row['completed_lessons'] ?? 0);

        if ($total === 0) return null;
        if ($done >= $total) return 'completed';
        if ($done > 0)      return 'in_progress';
        return 'not_started';
    }
}

if (!function_exists('getModuleLevelBadge')) {
    /**
     * Return Tailwind badge CSS classes for a given level.
     *
     * @param string|null $level
     * @return array [dotClass, badgeClass]
     */
    function getModuleLevelBadge($level) {
        switch ($level) {
            case 'Beginner':          return ['bg-green-500', 'bg-green-50 text-green-700'];
            case 'Elementary':        return ['bg-blue-500', 'bg-blue-50 text-blue-700'];
            case 'Pre-Intermediate':  return ['bg-indigo-500', 'bg-indigo-50 text-indigo-700'];
            case 'Intermediate':      return ['bg-yellow-500', 'bg-yellow-50 text-yellow-700'];
            case 'Advanced':          return ['bg-red-500', 'bg-red-50 text-red-700'];
            default:                  return ['bg-gray-400', 'bg-gray-50 text-gray-500'];
        }
    }
}