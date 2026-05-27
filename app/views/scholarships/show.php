<?php
/**
 * Scholarship Detail Page
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Scholarship details and application information.'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title ?? 'Scholarship | InfoHub'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description ?? 'Scholarship details and application information.'); ?>">
    <link rel="icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg">
    <title><?php echo htmlspecialchars($page_title ?? 'Scholarship | InfoHub'); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/news-modern.css">
    <style>
        :root {
            --primary-green: #16a34a;
            --accent-blue: #2563eb;
            --gray-light: #f8fafc;
            --gray-dark: #0f172a;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-light);
            color: var(--gray-dark);
            padding-top: 72px;
        }
        .topbar { display: flex !important; position: fixed; top: 0; left: 0; right: 0; width: 100%; height: 72px; z-index: 1100; background: linear-gradient(90deg, #dc2626 0%, #991b1b 100%); }
        .topbar-content { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.5rem; width: 100%; min-height: 72px; }
        .breaking-news { flex: 1 1 0%; min-width: 0; display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; }
        .breaking-badge { flex-shrink: 0; color: white; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }
        .breaking-ticker { flex: 1 1 0%; min-width: 0; overflow: hidden; }
        .ticker-content { white-space: nowrap; display: inline-block; color: white; }
        .topbar-links a { color: white; text-decoration: none; }
        nav { position: sticky; top: 72px; z-index: 1000; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        main { max-width: 1000px; margin: 0 auto; padding: 1.5rem; }
        .meta-pill { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.85rem; border-radius: 999px; background: #ecfdf5; color: #166534; font-size: 0.85rem; font-weight: 700; }
        .details-box { background: white; border: 1px solid var(--border-color); border-radius: 0.85rem; overflow: hidden; box-shadow: 0 1px 5px rgba(15,23,42,0.06); }
        .details-header { padding: 1.5rem; border-bottom: 1px solid #e2e8f0; }
        .details-body { padding: 1.5rem; line-height: 1.75; color: #475569; }
        .actions a { background: var(--primary-green); color: white; padding: 0.9rem 1.25rem; border-radius: 0.65rem; text-decoration: none; font-weight: 700; }
        .view-actions a { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.65rem; color: var(--gray-dark); text-decoration: none; }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-content">
            <div class="breaking-news">
                <span class="breaking-badge">BREAKING</span>
                <?php
                $tickerItems = [];
                if (!empty($breakingNews)) {
                    foreach ($breakingNews as $item) {
                        $tickerItems[] = htmlspecialchars($item['title']);
                    }
                }
                if (empty($tickerItems)) {
                    $tickerItems[] = 'Latest scholarship insights from InfoHub and Rwanda.';
                }
                $tickerText = implode('  •  ', $tickerItems);
                ?>
                <div class="breaking-ticker">
                    <div class="ticker-content">
                        <?php echo $tickerText; ?>
                        <span style="padding: 0 1.5rem;">•</span>
                        <?php echo $tickerText; ?>
                    </div>
                </div>
            </div>
            <div class="topbar-links">
                <a href="https://facebook.com" target="_blank">📘</a>
                <a href="https://twitter.com" target="_blank">𝕏</a>
                <a href="https://linkedin.com" target="_blank">💼</a>
            </div>
        </div>
    </div>

    <nav>
        <div style="max-width: 1200px; margin: 0 auto; padding: 0.85rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <a href="<?php echo APP_URL; ?>" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--primary-green); font-size: 1.25rem; flex-shrink: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="1"></circle>
                    <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m5.08 5.08l4.24 4.24M1 12h6m6 0h6M4.22 19.78l4.24-4.24m5.08-5.08l4.24-4.24"></path>
                </svg>
                InfoHub
            </a>
            <form method="GET" action="<?php echo APP_URL; ?>/news/search" style="display: flex; gap: 0.5rem; flex: 1; min-width: 250px; max-width: 360px;">
                <input type="text" name="q" placeholder="Search articles..." style="flex: 1; padding: 0.7rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 0.9rem;">
                <button type="submit" style="padding: 0.7rem 1rem; background: var(--primary-green); color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600;">🔍</button>
            </form>
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="<?php echo APP_URL; ?>/news" style="text-decoration: none; color: var(--text-secondary); font-weight: 500; white-space: nowrap;">📰 News</a>
                <a href="<?php echo APP_URL; ?>/jobs" style="text-decoration: none; color: var(--text-secondary); font-weight: 500; white-space: nowrap;">💼 Jobs</a>
                <a href="<?php echo APP_URL; ?>/scholarships" style="text-decoration: none; color: var(--primary-green); font-weight: 700; white-space: nowrap;">🎓 Scholarships</a>
                <a href="<?php echo APP_URL; ?>/business" style="text-decoration: none; color: var(--text-secondary); font-weight: 500; white-space: nowrap;">🏢 Business</a>
            </div>
        </div>
    </nav>

    <main>
        <div class="details-box">
            <div class="details-header">
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: space-between; align-items: flex-start;">
                    <div style="max-width: 72%;">
                        <p style="color: #94a3b8; margin-bottom: 0.5rem;">🎓 Scholarship Opportunity</p>
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; line-height: 1.05;"><?php echo htmlspecialchars($scholarship['title']); ?></h1>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; color: #52606d; font-size: 0.95rem;">
                            <span class="meta-pill"><?php echo htmlspecialchars($scholarship['organization'] ?: 'InfoHub'); ?></span>
                            <span class="meta-pill"><?php echo htmlspecialchars($scholarship['level'] ? ucfirst($scholarship['level']) : 'Any'); ?></span>
                            <span class="meta-pill"><?php echo htmlspecialchars($scholarship['field_of_study'] ?: 'Any field'); ?></span>
                            <span class="meta-pill">Deadline: <?php echo date('M d, Y', strtotime($scholarship['application_deadline'])); ?></span>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; text-align: right;">
                        <span style="color: #94a3b8;">👁️ Views</span>
                        <strong style="font-size: 1.75rem; color: var(--primary-green);"><?php echo number_format($scholarship['views_count'] ?? 0); ?></strong>
                        <span style="font-size: 0.9rem; color: #64748b;">Posted by <?php echo htmlspecialchars(trim($scholarship['posted_by_name']) ?: 'InfoHub'); ?></span>
                    </div>
                </div>
            </div>
            <div class="details-body">
                <h2 style="font-size: 1.15rem; margin-bottom: 0.75rem;">About this scholarship</h2>
                <p><?php echo nl2br(htmlspecialchars($scholarship['description'])); ?></p>
                <div style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
                        <p style="font-size: 0.9rem; color: #94a3b8;">Funding</p>
                        <p style="font-weight: 700; font-size: 1rem;"><?php echo htmlspecialchars($scholarship['currency'] ?? 'RWF'); ?> <?php echo number_format($scholarship['amount'] ?? 0); ?></p>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
                        <p style="font-size: 0.9rem; color: #94a3b8;">Eligibility</p>
                        <p style="font-weight: 700; font-size: 1rem;"><?php echo htmlspecialchars($scholarship['eligibility_criteria'] ?: 'Open to qualified candidates'); ?></p>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
                        <p style="font-size: 0.9rem; color: #94a3b8;">Apply</p>
                        <a href="<?php echo htmlspecialchars($scholarship['application_url'] ?: APP_URL . '/scholarships'); ?>" target="_blank" class="actions"><?php echo !empty($scholarship['application_url']) ? 'Apply now' : 'Browse scholarships'; ?></a>
                    </div>
                </div>
                <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?php echo APP_URL; ?>/scholarships" class="view-actions">← Back to scholarships</a>
                    <a href="<?php echo APP_URL; ?>/news" class="view-actions">View latest news</a>
                </div>
            </div>
        </div>
    </main>

    <footer style="position: relative; z-index: 1; background: var(--gray-dark); color: #e2e8f0; margin-top: 4rem; padding: 3rem 1.5rem 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4 style="color: white; margin-bottom: 1rem; font-weight: 700;">About InfoHub</h4>
                <p style="line-height: 1.6; font-size: 0.95rem;">Rwanda's unified digital platform for news, jobs, scholarships, and business opportunities.</p>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 1rem; font-weight: 700;">Quick Links</h4>
                <ul style="list-style: none; line-height: 1.8; padding: 0; margin: 0;">
                    <li><a href="<?php echo APP_URL; ?>/news" style="color: var(--primary-green); text-decoration: none;">📰 News</a></li>
                    <li><a href="<?php echo APP_URL; ?>/jobs" style="color: var(--primary-green); text-decoration: none;">💼 Jobs</a></li>
                    <li><a href="<?php echo APP_URL; ?>/scholarships" style="color: var(--primary-green); text-decoration: none;">🎓 Scholarships</a></li>
                    <li><a href="<?php echo APP_URL; ?>/business" style="color: var(--primary-green); text-decoration: none;">🏢 Business</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 1rem; font-weight: 700;">Support</h4>
                <p style="margin-bottom: 0.5rem;"><a href="mailto:info@infohub.rw" style="color: var(--primary-green); text-decoration: none;">✉️ info@infohub.rw</a></p>
                <p><a href="#" style="color: var(--primary-green); text-decoration: none;">📞 +250 788 123 456</a></p>
            </div>
        </div>
        <div style="border-top: 1px solid #334155; padding-top: 1.5rem; text-align: center; color: #94a3b8; font-size: 0.9rem;">
            &copy; <?php echo date('Y'); ?> InfoHub Rwanda. Building Rwanda's digital future 🇷🇼
        </div>
    </footer>
</body>
</html>
