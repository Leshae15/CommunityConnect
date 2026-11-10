<?php
//Davanea Morsby- Datatbase connection function
require __DIR__ . '/common.php';

requireAdmin();

$per_page = 10;


$icons = [
    'hand-heart' => '<path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16" /><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" /><path d="m2 15 6 6" /><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z" />',
    'user-plus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><line x1="19" x2="19" y1="8" y2="14" /><line x1="22" x2="16" y1="11" y2="11" />',
    'sliders-horizontal' => '<line x1="21" x2="14" y1="4" y2="4" /><line x1="10" x2="3" y1="4" y2="4" /><line x1="21" x2="12" y1="12" y2="12" /><line x1="8" x2="3" y1="12" y2="12" /><line x1="21" x2="16" y1="20" y2="20" /><line x1="12" x2="3" y1="20" y2="20" /><line x1="14" x2="14" y1="2" y2="6" /><line x1="8" x2="8" y1="10" y2="14" /><line x1="16" x2="16" y1="18" y2="22" />',
    'clipboard-check' => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1" /><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" /><path d="m9 14 2 2 4-4" />',
    'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
    'log-out' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" x2="9" y1="12" y2="12" />',
    'power' => '<path d="M12 2v10" /><path d="M18.4 6.6a9 9 0 1 1-12.77.04" />',
    'search' => '<circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />',
    'map-pin' => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" /><circle cx="12" cy="10" r="3" />',
    'calendar-clock' => '<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5" /><path d="M16 2v4" /><path d="M8 2v4" /><path d="M3 10h5" /><path d="M17.5 17.5 16 16.3V14" /><circle cx="16" cy="16" r="6" />',
    'badge-check' => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" /><path d="m9 12 2 2 4-4" />',
    'rotate-ccw' => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" /><path d="M3 3v5h5" />',
    'arrow-up-down' => '<path d="m21 16-4 4-4-4" /><path d="M17 20V4" /><path d="m3 8 4-4 4 4" /><path d="M7 4v16" />',
    'eye' => '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" />',
    'mail' => '<rect width="20" height="16" x="2" y="4" rx="2" /><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />',
    'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />',
    'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />',
    'clock' => '<circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />',
    'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /><path d="m9 12 2 2 4-4" />',
    'check' => '<path d="M20 6 9 17l-5-5" />',
];

function rec_icon($name)
{
    global $icons;
    return '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $icons[$name] . '</svg>';
}

function rec_get($key, $default = '')
{
    return (isset($_GET[$key]) && is_string($_GET[$key])) ? trim($_GET[$key]) : $default;
}

function rec_show($value)
{
    return $value === '' ? 'Not specified' : $value;
}

function rec_unique(array $values)
{
    $values = array_values(array_unique($values));
    sort($values);
    return $values;
}

function rec_date($value)
{
    $time = strtotime($value);
    return $time ? date('M d, Y', $time) : rec_show($value);
}

function rec_initials($name)
{
    $parts = preg_split('/\s+/', trim($name));
    $first = substr($parts[0], 0, 1);
    $last = count($parts) > 1 ? substr($parts[count($parts) - 1], 0, 1) : '';
    return strtoupper($first . $last);
}

function rec_contact_tone($method)
{
    if ($method === 'WhatsApp') {
        return 'green';
    }
    return $method === 'Telephone' ? 'orange' : 'tan';
}

function rec_url(array $filters, array $changes = [])
{
    $params = [];
    foreach (array_merge($filters, $changes) as $key => $value) {
        if ($value !== '' && $value !== 'all') {
            $params[$key] = $value;
        }
    }
    return 'records.php' . ($params ? '?' . http_build_query($params) : '');
}

function rec_select($name, $label, $all_label, array $options, $current)
{
    echo '<select name="' . e($name) . '" aria-label="' . e($label) . '" onchange="this.form.submit()">';
    echo '<option value="all">' . e($all_label) . '</option>';
    foreach ($options as $option) {
        $selected = $option === $current ? ' selected' : '';
        echo '<option value="' . e($option) . '"' . $selected . '>' . e($option) . '</option>';
    }
    echo '</select>';
}

// Read the saved registrations

$records = [];
$load_error = '';
$connection = dbConnect();

if ($connection === false){
    $load_error = 'Error connecting to the database.';
} else{
    //Oldest first, so the most recent are last in the list.
    $result = mysqli_query($connection, 'SELECT * FROM registrations ORDER BY saved_at ASC');

    if ($result ===false){
        $load_error = 'Error reading saved registrations from the database.';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $record = [];

            foreach ($columns as $column) {
                $record[$column] = trim ((string) ($row[$column] ?? ''));
            }
            $record['skill_list'] = $record['skills'] === '' ? [] : preg_split('/\s*[;,]\s*/', $record['skills']);
            $records[] = $record;
        }
        mysqli_free_result($result);
    }
    mysqli_close($connection);
}

//Summary numbers and filter choices

$parish_choices = [];
$availability_choices = [];
$experience_choices = [];
$activity_choices = [];
$skill_choices = [];
$upcoming = 0;
$today = date('Y-m-d');

foreach ($records as $record) {
    $parish_choices[] = rec_show($record['parish']);
    $availability_choices[] = rec_show($record['availability']);
    $experience_choices[] = rec_show($record['volunteer_experience']);
    $activity_choices[] = rec_show($record['activity']);
    $skill_choices = array_merge($skill_choices, $record['skill_list']);
    if ($record['participation_date'] >= $today) {
        $upcoming++;
    }
}

$parish_choices = rec_unique($parish_choices);
$availability_choices = rec_unique($availability_choices);
$experience_choices = rec_unique($experience_choices);
$activity_choices = rec_unique($activity_choices);
$skill_choices = rec_unique($skill_choices);

//Apply the search, dropdowns and skill chips

$filters = [
    'q' => rec_get('q'),
    'parish' => rec_get('parish', 'all'),
    'availability' => rec_get('availability', 'all'),
    'experience' => rec_get('experience', 'all'),
    'activity' => rec_get('activity', 'all'),
    'skill' => rec_get('skill', 'all'),
    'sort' => rec_get('sort') === 'oldest' ? 'oldest' : '',
];

$results = [];

foreach ($records as $record) {
    if ($filters['parish'] !== 'all' && rec_show($record['parish']) !== $filters['parish']) {
        continue;
    }
    if ($filters['availability'] !== 'all' && rec_show($record['availability']) !== $filters['availability']) {
        continue;
    }
    if ($filters['experience'] !== 'all' && rec_show($record['volunteer_experience']) !== $filters['experience']) {
        continue;
    }
    if ($filters['activity'] !== 'all' && rec_show($record['activity']) !== $filters['activity']) {
        continue;
    }
    if ($filters['skill'] !== 'all' && !in_array($filters['skill'], $record['skill_list'], true)) {
        continue;
    }
    if ($filters['q'] !== '') {
        $searchable = implode(' ', [
            $record['full_name'], $record['email'], $record['telephone'],
            $record['parish'], $record['activity'], $record['skills']
        ]);
        if (stripos($searchable, $filters['q']) === false) {
            continue;
        }
    }
    $results[] = $record;
}

// Newest registrations are last in the list, so Recent shows those first.
if ($filters['sort'] !== 'oldest') {
    $results = array_reverse($results);
}

//Page of results and selected participant 

$total_pages = max(1, (int) ceil(count($results) / $per_page));
$page = min($total_pages, max(1, (int) rec_get('page', '1')));
$page_rows = array_slice($results, ($page - 1) * $per_page, $per_page);
$page_filters = array_merge($filters, ['page' => $page > 1 ? $page : '']);

$selected = $page_rows ? $page_rows[0] : null;
$selected_id = rec_get('id');

foreach ($page_rows as $record) {
    if ($record['id'] === $selected_id) {
        $selected = $record;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Registration Report | CommunityConnect</title>
    <link rel="stylesheet" href="records.css">
</head>
<body class= "admin-report">
<div class="app">

    <aside class="sidebar">
        <div class="brand">
            <div>
                <p class="brand-name">CommunityConnect</p>
                <p class="brand-sub">Community Service and Event Registration Portal</p>
            </div>
        </div>

        <a class="btn btn-dark btn-block" href="registration.php"><?= rec_icon('user-plus') ?> Register Participant</a>

        <nav class="side-nav" aria-label="Main">
            <a class="side-link" href="preferences.php"><?= rec_icon('sliders-horizontal') ?><span>Preferences</span></a>
            <a class="side-link" href="confirmation.php"><?= rec_icon('clipboard-check') ?><span>My Confirmation</span></a>
            <a class="side-link is-active" href="records.php" aria-current="page">
                <?= rec_icon('users') ?><span>View Records</span>
                <span class="side-badge"><?= count($records) ?></span>
            </a>
        </nav>

        <div class="side-bottom">
            <div class="side-user">
                <span class="avatar avatar-sm" aria-hidden="true">AD</span>
                <div>
                    <p class="side-user-name">Administrator</p>
                    <p class="side-user-role">Signed in</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-title">
                <h1>Administrator Registration Report</h1>
                <p>Saved participant information</p>
            </div>

            <label class="search">
                <?= rec_icon('search') ?>
                <span class="sr-only">Search participants</span>
                <input type="search" name="q" form="roster-filters" value="<?= e($filters['q']) ?>"
                       placeholder="Search by name, email, activity, parish, or skills...">
            </label>

            <div class="topbar-actions">
                <a class="btn btn-outline" href="admin_logout.php"><?= rec_icon('log-out') ?> Admin Logout</a>
                <a class="btn btn-light" href="finish.php"><?= rec_icon('power') ?> End Session</a>
            </div>
        </header>

        <main class="content">
            <?php if ($load_error !== ''): ?>
                <p class="load-error" role="alert"><?= e($load_error) ?></p>
            <?php endif; ?>

            <section class="stats" aria-label="Summary">
                <article class="card stat">
                    <div>
                        <h2 class="eyebrow">Total Registrations</h2>
                        <p class="stat-value"><?= count($records) ?></p>
                        <p class="stat-note">saved participants</p>
                    </div>
                    <span class="stat-icon tile-green"><?= rec_icon('users') ?></span>
                </article>
                <article class="card stat">
                    <div>
                        <h2 class="eyebrow">Parishes Represented</h2>
                        <p class="stat-value"><?= count($parish_choices) ?></p>
                        <p class="stat-note">from saved records</p>
                    </div>
                    <span class="stat-icon tile-tan"><?= rec_icon('map-pin') ?></span>
                </article>
                <article class="card stat">
                    <div>
                        <h2 class="eyebrow">Upcoming Participation</h2>
                        <p class="stat-value"><?= (int) $upcoming ?></p>
                        <p class="stat-note">dated today or later</p>
                    </div>
                    <span class="stat-icon tile-orange"><?= rec_icon('calendar-clock') ?></span>
                </article>
                <article class="card stat">
                    <div>
                        <h2 class="eyebrow">Skills Logged</h2>
                        <p class="stat-value"><?= count($skill_choices) ?></p>
                        <p class="stat-note">different skills listed</p>
                    </div>
                    <span class="stat-icon tile-tan"><?= rec_icon('badge-check') ?></span>
                </article>
            </section>

            <section class="card filters" aria-label="Filters">
                <form id="roster-filters" class="filter-row" method="get" action="records.php">
                    <input type="hidden" name="skill" value="<?= e($filters['skill']) ?>">
                    <input type="hidden" name="sort" value="<?= e($filters['sort']) ?>">

                    <?php rec_select('parish', 'Parish', 'All Parishes (' . count($parish_choices) . ' Total)', $parish_choices, $filters['parish']); ?>
                    <?php rec_select('availability', 'Availability', 'Availability: All', $availability_choices, $filters['availability']); ?>
                    <?php rec_select('experience', 'Experience', 'Experience: Any', $experience_choices, $filters['experience']); ?>
                    <?php rec_select('activity', 'Activity', 'All Activities', $activity_choices, $filters['activity']); ?>

                    <noscript><button type="submit" class="btn btn-outline-dark">Apply</button></noscript>

                    <div class="filter-summary">
                        <p>Showing <strong><?= count($results) ?> of <?= count($records) ?></strong> Registrations</p>
                        <a href="records.php" class="reset-link"><?= rec_icon('rotate-ccw') ?> Reset Filters</a>
                    </div>
                </form>

                <div class="skill-row">
                    <span class="skill-label">Filter by Skill:</span>
                    <a class="chip<?= $filters['skill'] === 'all' ? ' is-active' : '' ?>" href="<?= e(rec_url($filters, ['skill' => 'all'])) ?>">All Skills</a>
                    <?php foreach ($skill_choices as $skill): ?>
                        <a class="chip<?= $filters['skill'] === $skill ? ' is-active' : '' ?>" href="<?= e(rec_url($filters, ['skill' => $skill])) ?>"><?= e($skill) ?></a>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="roster-layout">

                <section class="card roster">
                    <header class="roster-head">
                        <div>
                            <h2 id="roster-title">Saved Participant Registrations</h2>
                            <p>You are signed in as the administrator. Saved participant information is displayed below.</p>
                        </div>
                        <div class="roster-tools">
                            <a class="btn btn-outline-dark btn-sm" href="<?= e(rec_url($filters, ['sort' => $filters['sort'] === 'oldest' ? '' : 'oldest'])) ?>">
                                <?= rec_icon('arrow-up-down') ?> Sort: <?= $filters['sort'] === 'oldest' ? 'Oldest' : 'Recent' ?>
                            </a>
                        </div>
                    </header>

                    <div class="table-wrap" tabindex="0" role="region" aria-labelledby="roster-title">
                        <table class="roster-table">
                            <colgroup>
                                <col class="col-check"><col class="col-name"><col class="col-parish"><col class="col-pref">
                                <col class="col-avail"><col class="col-ministry"><col class="col-skills"><col class="col-actions">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col"><span class="sr-only">Selected</span></th>
                                    <th scope="col">Participant Name &amp; Contact</th>
                                    <th scope="col">Parish &amp; Age</th>
                                    <th scope="col">Pref / Exp</th>
                                    <th scope="col">Availability</th>
                                    <th scope="col">Activity &amp; Date</th>
                                    <th scope="col">Skills</th>
                                    <th scope="col" class="cell-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($page_rows as $v):
                                    $is_selected = $selected && $selected['id'] === $v['id'];
                                    $row_url = rec_url($page_filters, ['id' => $v['id']]);
                                ?>
                                    <tr class="<?= $is_selected ? 'is-selected' : '' ?>">
                                        <td class="cell-check">
                                            <span class="check-box"><?= $is_selected ? rec_icon('check') : '' ?></span>
                                        </td>
                                        <td class="cell-name">
                                            <div class="person-wrap">
                                                <span class="avatar" aria-hidden="true"><?= e(rec_initials($v['full_name'])) ?></span>
                                                <div class="person">
                                                    <p class="person-name">
                                                        <a class="row-link" href="<?= e($row_url) ?>"<?= $is_selected ? ' aria-current="true"' : '' ?>><?= e(rec_show($v['full_name'])) ?></a>
                                                    </p>
                                                    <p class="person-contact">
                                                        <span class="person-email"><?= e($v['email']) ?></span>
                                                        <span class="person-sep" aria-hidden="true">•</span>
                                                        <span><?= e($v['telephone']) ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Parish &amp; Age">
                                            <p class="strong"><?= e(rec_show($v['parish'])) ?></p>
                                            <p class="muted"><?= e($v['age']) ?> yrs</p>
                                        </td>
                                        <td data-label="Pref / Exp">
                                            <span class="pill pill-<?= e(rec_contact_tone($v['contact_method'])) ?>"><?= e(rec_show($v['contact_method'])) ?></span>
                                            <p class="muted"><?= e(rec_show($v['volunteer_experience'])) ?></p>
                                        </td>
                                        <td data-label="Availability">
                                            <span class="pill pill-round pill-green"><i class="pill-dot"></i><?= e(rec_show($v['availability'])) ?></span>
                                        </td>
                                        <td data-label="Activity &amp; Date">
                                            <p class="strong strong-brown"><?= e(rec_show($v['activity'])) ?></p>
                                            <p class="muted"><?= e(rec_date($v['participation_date'])) ?></p>
                                        </td>
                                        <td data-label="Skills">
                                            <div class="skill-stack">
                                                <?php foreach ($v['skill_list'] as $skill): ?>
                                                    <span class="tag"><?= e($skill) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="cell-actions">
                                            <div class="row-actions">
                                                <a href="<?= e($row_url) ?>" aria-label="View <?= e($v['full_name']) ?>"><?= rec_icon('eye') ?></a>
                                                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $v['telephone'])) ?>" aria-label="Call <?= e($v['full_name']) ?>"><?= rec_icon('phone') ?></a>
                                                <a href="mailto:<?= e($v['email']) ?>" aria-label="Email <?= e($v['full_name']) ?>"><?= rec_icon('mail') ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (!$page_rows): ?>
                                    <tr>
                                        <td colspan="8" class="empty">No registrations match these filters. <a href="records.php">Reset filters</a></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <footer class="roster-foot">
                        <p>Showing page <?= (int) $page ?> of <?= (int) $total_pages ?> <?= $total_pages === 1 ? 'page' : 'pages' ?></p>
                        <nav class="pager" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                                <a class="page-btn" href="<?= e(rec_url($filters, ['page' => $page - 1])) ?>">Previous</a>
                            <?php endif; ?>
                            <?php for ($number = 1; $number <= $total_pages; $number++): ?>
                                <a class="page-btn<?= $number === $page ? ' is-current' : '' ?>"<?= $number === $page ? ' aria-current="page"' : '' ?> href="<?= e(rec_url($filters, ['page' => $number])) ?>"><?= $number ?></a>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages): ?>
                                <a class="page-btn" href="<?= e(rec_url($filters, ['page' => $page + 1])) ?>">Next</a>
                            <?php endif; ?>
                        </nav>
                    </footer>
                    <p class="roster-hint">Older registrations may show “Not specified” for fields that were added later.</p>
                </section>

                <aside class="card detail" aria-label="Participant details">
                    <?php if (!$selected): ?>
                        <p class="empty">Select a participant to see their details.</p>
                    <?php else: ?>
                        <header class="detail-head">
                            <span class="avatar avatar-lg" aria-hidden="true"><?= e(rec_initials($selected['full_name'])) ?></span>
                            <div class="detail-id">
                                <h2><?= e(rec_show($selected['full_name'])) ?></h2>
                                <p class="muted">Participant • Age <?= e(rec_show($selected['age'])) ?></p>
                                <span class="pill pill-round pill-green"><i class="pill-dot"></i>Registered</span>
                            </div>
                        </header>

                        <h3 class="eyebrow">Direct Communications</h3>
                        <dl class="comm">
                            <div>
                                <dt><?= rec_icon('mail') ?> Email</dt>
                                <dd><a href="mailto:<?= e($selected['email']) ?>"><?= e(rec_show($selected['email'])) ?></a></dd>
                            </div>
                            <div>
                                <dt><?= rec_icon('phone') ?> Telephone</dt>
                                <dd class="strong"><?= e(rec_show($selected['telephone'])) ?></dd>
                            </div>
                            <div>
                                <dt><?= rec_icon('message-circle') ?> Preferred Contact</dt>
                                <dd><span class="pill pill-round pill-green"><?= e(rec_show($selected['contact_method'])) ?></span></dd>
                            </div>
                        </dl>

                        <h3 class="eyebrow">Parish &amp; Volunteering History</h3>
                        <div class="history">
                            <div class="history-box">
                                <p class="history-label">Parish</p>
                                <p class="history-value"><?= e(rec_show($selected['parish'])) ?></p>
                            </div>
                            <div class="history-box">
                                <p class="history-label">Volunteering Experience</p>
                                <p class="history-value"><?= e(rec_show($selected['volunteer_experience'])) ?></p>
                            </div>
                        </div>
                        <p class="recent"><span>Participation Date</span><strong><?= e(rec_date($selected['participation_date'])) ?></strong></p>

                        <h3 class="eyebrow">Selected Activity &amp; Availability</h3>
                        <div class="assignment">
                            <div class="assignment-head">
                                <h4><?= e(rec_show($selected['activity'])) ?></h4>
                                <span class="tag-dark">Participant</span>
                            </div>
                            <p class="history-label">General Availability</p>
                            <div class="windows">
                                <span class="window"><?= rec_icon('clock') ?><?= e(rec_show($selected['availability'])) ?></span>
                            </div>
                        </div>

                        <div class="eyebrow-row">
                            <h3 class="eyebrow">Interests &amp; Skills</h3>
                            <span class="count"><?= count($selected['skill_list']) ?> Listed</span>
                        </div>
                        <div class="credentials">
                            <?php foreach ($selected['skill_list'] as $skill): ?>
                                <span class="pill pill-round pill-tan"><?= rec_icon('badge-check') ?><?= e($skill) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <div class="compliance">
                            <div class="compliance-head">
                                <h4><?= rec_icon('shield-check') ?> Registration Record</h4>
                                <span class="tag-green">Saved</span>
                            </div>
                            <p>
                                <?= $selected['saved_at'] !== ''
                                    ? 'Saved to the database on ' . e(rec_date($selected['saved_at'])) . '.'
                                    : 'This older registration does not have a saved date.' ?>
                            </p>
                        </div>

                        <div class="detail-actions">
                            <a class="btn btn-dark btn-block" href="mailto:<?= e($selected['email']) ?>"><?= rec_icon('mail') ?> Email Participant</a>
                            <a class="btn-square" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $selected['telephone'])) ?>" aria-label="Call <?= e($selected['full_name']) ?>"><?= rec_icon('phone') ?></a>
                        </div>
                    <?php endif; ?>
                </aside>

            </div>
        </main>
    </div>
</div>
</body>
</html>