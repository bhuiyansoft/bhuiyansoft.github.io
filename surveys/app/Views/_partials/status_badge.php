<?php
$map = [
    'draft'     => ['secondary', 'pencil',         'Draft'],
    'published' => ['success',   'broadcast',       'Published'],
    'closed'    => ['danger',    'stop-circle',     'Closed'],
    'archived'  => ['dark',      'archive',         'Archived'],
];
$s = $map[$status] ?? ['secondary', 'question', $status];
?>
<span class="badge bg-<?= $s[0] ?>-soft text-<?= $s[0] ?>">
    <i class="bi bi-<?= $s[1] ?> me-1"></i><?= $s[2] ?>
</span>
