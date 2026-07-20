<?php
require_once '../config/db.php';
include_once('includes/header.php');
require_once 'includes/sidebar.php';
require_once __DIR__ . '/../includes/admin_notification_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    } elseif (($action === 'mark_read' || $action === 'mark_unread') && $id > 0) {
        $isRead = ($action === 'mark_read') ? 1 : 0;
        $stmt = $conn->prepare('UPDATE contacts SET is_read = ? WHERE id = ?');
        $stmt->bind_param('ii', $isRead, $id);
        $stmt->execute();
    }
    echo '<script>location.href="contacts.php"</script>';
    exit;
}

 $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
 $limit = 10;
 $offset = ($page - 1) * $limit;
 $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
 $where = '';
if ($filter === 'unread') $where = 'WHERE is_read = 0';
elseif ($filter === 'read') $where = 'WHERE is_read = 1';

 $countResult = $conn->query("SELECT COUNT(*) as total FROM contacts $where");
 $total = 0;
if ($countResult && $countRow = $countResult->fetch_assoc()) $total = $countRow['total'];
 $totalPages = max(ceil($total / $limit), 1);

 $messages = [];
 $result = $conn->query("SELECT * FROM contacts {$where} ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
if ($result) while ($row = $result->fetch_assoc()) $messages[] = $row;

 $unreadCount = 0;
 $r2 = $conn->query('SELECT COUNT(*) as total FROM contacts WHERE is_read=0');
if ($r2 && $r2r = $r2->fetch_assoc()) $unreadCount = $r2r['total'];

 $subjectLabels = [
    'course-info' => 'Course Info', 'pricing' => 'Pricing', 'enrollment' => 'Enrollment',
    'technical' => 'Technical', 'partnership' => 'Partnership', 'feedback' => 'Feedback', 'other' => 'Other'
];
?>
<div class="flex-1 flex flex-col overflow-hidden">

    <main class="flex-1 overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex gap-3 items-baseline">
                <a href="contacts.php" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo $filter === '' ? 'bg-brandOrange text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">All</a>
                <a href="contacts.php?filter=unread" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo $filter === 'unread' ? 'bg-brandOrange text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">Unread<?php if ($unreadCount > 0): ?><span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-bold text-white bg-red-500 rounded-full"><?php echo $unreadCount; ?></span><?php endif; ?></a>
                <a href="contacts.php?filter=read" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo $filter === 'read' ? 'bg-brandOrange text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">Read</a>
            </div>
        </div>

<?php if (!empty($messages)): ?>
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <table class="w-full table-fixed" id="contactsTable">
                <thead class="bg-orange-100/50">
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-3 py-3 w-10">#</th>
                        <th class="px-3 py-3 w-[15%]">Name</th>
                        <th class="px-3 py-3 w-[18%]">Email</th>
                        <th class="px-3 py-3 w-24">Subject</th>
                        <th class="px-3 py-3">Message</th>
                        <th class="px-3 py-3 w-24">Date</th>
                        <th class="px-3 py-3 w-20 text-center">Status</th>
                        <th class="px-3 py-3 w-28 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
<?php $counter = $offset + 1; foreach ($messages as $msg):
    $name = trim($msg['first_name'] . ' ' . $msg['last_name']);
    $shortMsg = strlen($msg['message']) > 100 ? substr($msg['message'], 0, 100) . '...' : $msg['message'];
    $isMsgRead = $msg['is_read'] == 1;
    $subjectLabel = isset($subjectLabels[$msg['subject']]) ? $subjectLabels[$msg['subject']] : $msg['subject'];
?>
                    <tr class="hover:bg-gray-50 transition-colors contact-row <?php echo $isMsgRead ? '' : 'bg-blue-50/30'; ?>">
                        <td class="px-3 py-3 text-xs text-gray-400"><?= $counter++ ?></td>
                        <td class="px-3 py-3 min-w-0">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brandOrange to-orange-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                    <?php echo strtoupper($msg['first_name'][0]) . (isset($msg['last_name'][0]) ? strtoupper($msg['last_name'][0]) : ''); ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-700 truncate" title="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></p>
                                    <?php if ($msg['phone']): ?><p class="text-[11px] text-gray-400 truncate" title="<?= htmlspecialchars($msg['phone']) ?>"><?= htmlspecialchars($msg['phone']) ?></p><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 min-w-0">
                            <span class="text-sm text-gray-600 truncate block" title="<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></span>
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-medium truncate max-w-full <?php echo $isMsgRead ? 'bg-gray-100 text-gray-500' : 'bg-purple-50 text-purple-700 border border-purple-200' ?>" title="<?= htmlspecialchars($subjectLabel) ?>"><?= htmlspecialchars($subjectLabel) ?></span>
                        </td>
                        <td class="px-3 py-3 min-w-0">
                            <div class="relative bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2" title="<?= htmlspecialchars($msg['message']) ?>"><?= htmlspecialchars($shortMsg) ?></p>
                                <?php if (!$isMsgRead): ?><span class="absolute top-2 right-2 w-2 h-2 bg-brandOrange rounded-full flex-shrink-0"></span><?php endif; ?>
                            </div>
                        </td>
                        <td class="px-3 py-3 min-w-0">
                            <span class="text-xs text-gray-500 block truncate" title="<?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?>"><?= date('M d, Y', strtotime($msg['created_at'])) ?></span>
                            <span class="text-[10px] text-gray-400 block"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <?php if ($isMsgRead): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-green-50 text-green-700 border border-green-200">
                                    <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    Read
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                    Unread
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <div class="flex items-center justify-center gap-0.5">
                                <button onclick="document.getElementById('view-modal-<?php echo $msg['id']; ?>').showModal();" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
<?php if ($isMsgRead): ?>
                                    <input type="hidden" name="action" value="mark_unread">
                                    <button type="submit" class="p-1.5 rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors" title="Mark as Unread">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M9.377 9.377a2.909 2.909 0 010 4.246 2.91 2.91 0 01-4.112 0M13.5 6.75l3.375 2.369m0 0L17.25 12l3.375 3.75"/></svg>
                                    </button>
<?php else: ?>
                                    <input type="hidden" name="action" value="mark_read">
                                    <button type="submit" class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition-colors" title="Mark as Read">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
<?php endif; ?>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete" onclick="return confirm('Delete this contact message?');">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.003A2 2 0 0116.139 21H7.862a2 2 0 01-1.995-1.997L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">Page <?php echo $page; ?> of <?php echo $totalPages; ?> (<?php echo $total; ?> total)</p>
                <div class="flex items-center gap-1">
                    <a href="?p=1<?php echo $filter ? '&filter='.$filter : ''; ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?php echo $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">First</a>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?p=<?php echo $i . ($filter ? '&filter='.$filter : ''); ?>" class="px-3 py-1.5 text-sm rounded-lg border <?php echo $i === $page ? 'bg-brandOrange text-white border-brandOrange' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?> transition"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <a href="?p=<?php echo $totalPages . ($filter ? '&filter='.$filter : ''); ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?php echo $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">Last</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
<?php else: ?>
        <div class="bg-white rounded-xl border border-gray-200 p-16 text-center shadow-sm">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">No contact messages</h3>
            <p class="text-sm text-gray-400">When visitors submit the contact form, their messages will appear here.</p>
        </div>
<?php endif; ?>
    </main>
</div>

<?php foreach ($messages as $msg):
    $subjectLabel = isset($subjectLabels[$msg['subject']]) ? $subjectLabels[$msg['subject']] : $msg['subject'];
    $isMsgRead = $msg['is_read'] == 1;
?>
<dialog id="view-modal-<?php echo $msg['id']; ?>" class="p-0 rounded-3xl shadow-2xl border-0 max-w-lg w-full backdrop:bg-black/40">
    <div class="dialog-content" style="padding:2rem;background:white;border-radius:1.5rem">
        <h2 class="dialog-title" style="font-size:1.25rem;font-weight:700;color:#1f2937;margin-bottom:1.5rem">Message Details</h2>
        <table style="width:100%">
            <tr><td style="padding-bottom:1rem;width:50%"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">First Name</span><p style="margin:0.25rem 0 0;font-size:0.875rem;color:#374151"><?php echo htmlspecialchars($msg['first_name']); ?></p></td>
                <td style="padding-bottom:1rem;width:50%"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Last Name</span><p style="margin:0.25rem 0 0;font-size:0.875rem;color:#374151"><?php echo htmlspecialchars($msg['last_name'] ?: 'N/A'); ?></p></td></tr>
            <tr><td style="padding-bottom:1rem"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</span><p style="margin:0.25rem 0 0"><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color:#FF8A00"><?php echo htmlspecialchars($msg['email']); ?></a></p></td>
                <td style="padding-bottom:1rem"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Phone</span><p style="margin:0.25rem 0 0;font-size:0.875rem;color:#4b5563"><?php echo htmlspecialchars($msg['phone'] ?: '(Not Provided)'); ?></p></td></tr>
            <tr><td colspan="2" style="padding-bottom:1rem"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Subject</span><span class="inline-block ml-2 px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 rounded-lg"><?php echo htmlspecialchars($subjectLabel); ?></span></td></tr>
            <tr><td colspan="2" style="padding-bottom:0.75rem"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Received</span><p style="margin:0.25rem 0 0;font-size:0.875rem;color:#6b7280"><?php echo date('F d, Y \a\t h:i A', strtotime($msg['created_at'])); ?></p></td></tr>
        </table>
        <div style="margin-top:0.5rem"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Message</span>
            <p style="margin:0.5rem 0 0;font-size:0.875rem;color:#1f2937;background:#f8f9fa;padding:1rem;border-radius:0.75rem;line-height:1.5"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
        </div>
        <div style="margin-top:2rem;display:flex;gap:0.75rem">
            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="flex:1;text-align:center;background:#FF8A00;color:white;border-radius:0.75rem;font-size:0.875rem;font-weight:700;padding:0.75rem;text-decoration:none;box-shadow:0 4px 12px rgba(255,138,0,0.3)">Reply via Email</a>
            <button onclick="document.getElementById('view-modal-<?php echo $msg['id']; ?>').close();" style="padding:0.75rem 1.5rem;background:#f3f4f6;border:none;border-radius:0.75rem;font-size:0.875rem;font-weight:500;color:#4b5563;cursor:pointer">Close</button>
        </div>
        <div style="margin-top:0.75rem;display:flex;gap:0.5rem;justify-content:flex-end">
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                <?php if ($isMsgRead): ?>
                <input type="hidden" name="action" value="mark_unread">
                <button type="submit" style="padding:0.5rem 1rem;background:#fef3c7;border:none;border-radius:0.5rem;font-size:0.75rem;color:#92400e;cursor:pointer">Mark Unread</button>
                <?php else: ?>
                <input type="hidden" name="action" value="mark_read">
                <button type="submit" style="padding:0.5rem 1rem;background:#d1fae5;border:none;border-radius:0.5rem;font-size:0.75rem;color:#065f46;cursor:pointer">Mark Read</button>
                <?php endif; ?>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" style="padding:0.5rem 1rem;background:#fee2e2;border:none;border-radius:0.5rem;font-size:0.75rem;color:#991b1b;cursor:pointer" onclick="return confirm('Delete?');">Delete</button>
            </form>
        </div>
    </div>
</dialog>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>