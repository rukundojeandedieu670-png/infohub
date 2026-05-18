<?php
/**
 * News Index - InfoHub News & Opportunities Platform
 * Production-Ready: Enterprise News Portal with Full DB Integration
 * Features: Multi-category aggregation, trending, search, pagination
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rwanda's news, jobs, scholarships, and business opportunities">
    <meta property="og:title" content="InfoHub News">
    <meta property="og:description" content="Rwanda's trusted digital platform for news and opportunities">
    <title><?php echo htmlspecialchars($page_title ?? 'News & Opportunities | InfoHub'); ?></title>
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
        html, body { height: 100%; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-light);
            color: var(--gray-dark);
        }
        main { min-height: calc(100vh - 400px); }
    </style>
</head>
<body>

    <!-- ===== SECTION 1: TOPBAR ===== -->
    <div style="background: linear-gradient(90deg, #dc2626 0%, #991b1b 100%); color: white; padding: 0.75rem 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-weight: 700;">🔴 BREAKING:</span>
                <span style="font-size: 0.9rem;">
                    <?php 
                    if (!empty($breakingNews) && isset($breakingNews[0])) {
                        echo htmlspecialchars(substr($breakingNews[0]['title'], 0, 60));
                    } else {
                        echo 'Latest news and opportunities from Rwanda';
                    }
                    ?>
                </span>
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: 1.1rem;">
                <a href="https://facebook.com" target="_blank" style="color: white; text-decoration: none;">📘</a>
                <a href="https://twitter.com" target="_blank" style="color: white; text-decoration: none;">𝕏</a>
                <a href="https://linkedin.com" target="_blank" style="color: white; text-decoration: none;">💼</a>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: NAVBAR ===== -->
    <nav style="position: sticky; top: 0; z-index: 1000; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            
            <!-- Logo -->
            <a href="<?php echo APP_URL; ?>" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--primary-green); font-size: 1.25rem; flex-shrink: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="1"></circle>
                    <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m5.08 5.08l4.24 4.24M1 12h6m6 0h6M4.22 19.78l4.24-4.24m5.08-5.08l4.24-4.24"></path>
                </svg>
                InfoHub
            </a>

            <!-- Search Bar -->
            <form method="GET" action="<?php echo APP_URL; ?>/news/search" style="display: flex; gap: 0.5rem; flex: 1; min-width: 250px; max-width: 350px;">
                <input type="text" name="q" placeholder="Search articles..." 
                    style="flex: 1; padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 0.9rem;">
                <button type="submit" style="padding: 0.6rem 1rem; background: var(--primary-green); color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600;">🔍</button>
            </form>

            <!-- Navigation -->
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="<?php echo APP_URL; ?>/news" style="text-decoration: none; color: var(--gray-dark); font-weight: 600; border-bottom: 3px solid var(--primary-green); padding-bottom: 0.5rem; white-space: nowrap;">📰 News</a>
                <a href="<?php echo APP_URL; ?>/jobs" style="text-decoration: none; color: var(--text-secondary); font-weight: 500; white-space: nowrap;">💼 Jobs</a>
                <a href="<?php echo APP_URL; ?>/business" style="text-decoration: none; color: var(--text-secondary); font-weight: 500; white-space: nowrap;">🏢 Business</a>
            </div>

            <!-- Auth -->
            <?php if (isset($user) && $user): ?>
                <div style="display: flex; gap: 1rem; align-items: center; white-space: nowrap;">
                    <span style="color: var(--text-secondary); font-size: 0.9rem;">👤 <?php echo htmlspecialchars(substr($user['first_name'] ?? 'User', 0, 15)); ?></span>
                    <a href="<?php echo APP_URL; ?>/auth/logout" style="padding: 0.4rem 0.8rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--text-secondary); font-size: 0.85rem;">Logout</a>
                </div>
            <?php else: ?>
                <div style="display: flex; gap: 0.5rem; white-space: nowrap;">
                    <a href="<?php echo APP_URL; ?>/auth/login" style="padding: 0.4rem 0.8rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--text-secondary); font-size: 0.85rem;">Login</a>
                    <a href="<?php echo APP_URL; ?>/auth/register" style="padding: 0.4rem 0.8rem; background: var(--primary-green); color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.85rem;">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- ===== SECTION 3: HERO - FEATURED + TRENDING ===== -->
    <section style="background: linear-gradient(135deg, var(--gray-light) 0%, #e2e8f0 100%); padding: 2rem 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                
                <!-- LEFT: Featured Article -->
                <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
                    <?php if (!empty($featuredPosts) && isset($featuredPosts[0])): 
                        $featured = $featuredPosts[0];
                    ?>
                        <div style="height: 300px; overflow: hidden; position: relative;">
                            <?php if (!empty($featured['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($featured['featured_image']); ?>" alt="Featured" 
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--accent-blue), #60a5fa);"></div>
                            <?php endif; ?>
                            <span style="position: absolute; top: 1rem; right: 1rem; background: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.8rem;">★ FEATURED</span>
                        </div>
                        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                            <div style="display: flex; gap: 0.75rem; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">
                                <span><?php echo htmlspecialchars($featured['category_name'] ?? 'News'); ?></span>
                                <span>•</span>
                                <span><?php echo date('M d, Y', strtotime($featured['published_at'] ?? 'now')); ?></span>
                            </div>
                            <h2 style="font-size: 1.4rem; line-height: 1.4; margin-bottom: 1rem; flex: 1;">
                                <?php echo htmlspecialchars($featured['title']); ?>
                            </h2>
                            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
                                <?php echo htmlspecialchars(substr($featured['excerpt'] ?? $featured['content'], 0, 160)); ?>...
                            </p>
                            <a href="<?php echo APP_URL; ?>/news/<?php echo htmlspecialchars($featured['slug']); ?>" 
                                style="align-self: flex-start; background: var(--primary-green); color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; transition: background 0.3s;">
                                Read Full Story →
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; padding: 3rem;">
                            <p style="color: var(--text-secondary);">📰 No featured articles available</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- RIGHT: Trending Now -->
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">🔥 Trending Now</h3>
                    <?php if (!empty($trendingPosts)): ?>
                        <?php foreach (array_slice($trendingPosts, 0, 5) as $idx => $trend): ?>
                            <a href="<?php echo APP_URL; ?>/news/<?php echo htmlspecialchars($trend['slug']); ?>" 
                                style="display: block; background: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid var(--primary-green); text-decoration: none; transition: all 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                <div style="display: flex; gap: 0.75rem;">
                                    <span style="font-size: 1.3rem; font-weight: 700; color: var(--primary-green); min-width: 2rem;"><?php echo $idx + 1; ?></span>
                                    <div style="flex: 1; min-width: 0;">
                                        <strong style="color: var(--gray-dark); display: block; margin-bottom: 0.25rem; line-height: 1.3; word-break: break-word;">
                                            <?php echo htmlspecialchars(substr($trend['title'], 0, 50)); ?>
                                        </strong>
                                        <span style="font-size: 0.75rem; color: #94a3b8;">👁️ <?php echo number_format($trend['views_count'] ?? 0); ?> views</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 4: CATEGORY TABS ===== -->
    <div style="background: white; border-bottom: 2px solid var(--border-color); overflow-x: auto; position: sticky; top: 70px; z-index: 99;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0.75rem 1.5rem; display: flex; gap: 0.75rem; justify-content: flex-start;">
            <a href="<?php echo APP_URL; ?>/news" style="padding: 0.5rem 1rem; background: var(--primary-green); color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600; white-space: nowrap; text-decoration: none; display: inline-block;">📰 All</a>
            <?php if (!empty($categories)): ?>
                <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                    <a href="<?php echo APP_URL; ?>/news/category/<?php echo htmlspecialchars($cat['slug'] ?? strtolower(str_replace(' ', '-', $cat['name']))); ?>" 
                        style="padding: 0.5rem 1rem; background: transparent; color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 0.375rem; cursor: pointer; white-space: nowrap; text-decoration: none; display: inline-block; transition: all 0.3s;">
                        <?php echo htmlspecialchars(substr($cat['name'], 0, 15)); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== SECTION 5: MAIN CONTENT GRID ===== -->
    <main style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem;">
            
            <!-- LEFT: Articles Grid -->
            <div id="articlesGrid">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem; padding: 1.5rem; background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); margin-bottom: 1.5rem; transition: all 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            
                            <!-- Image -->
                            <div style="position: relative; min-height: 150px; overflow: hidden; border-radius: 0.5rem;">
                                <?php if (!empty($post['featured_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                        alt="<?php echo htmlspecialchars($post['title']); ?>"
                                        loading="lazy" 
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--accent-blue), #60a5fa);"></div>
                                <?php endif; ?>
                                <span style="position: absolute; top: 0.5rem; right: 0.5rem; background: var(--primary-green); color: white; padding: 0.35rem 0.75rem; border-radius: 0.25rem; font-size: 0.7rem; font-weight: 600;">
                                    <?php echo htmlspecialchars(substr($post['category_name'] ?? 'News', 0, 12)); ?>
                                </span>
                            </div>

                            <!-- Content -->
                            <div style="display: flex; flex-direction: column;">
                                <div style="display: flex; gap: 1rem; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                                    <span>✍️ <?php echo htmlspecialchars(substr($post['first_name'] ?? 'Staff', 0, 15)); ?></span>
                                    <span>📅 <?php echo date('M d, Y', strtotime($post['published_at'] ?? 'now')); ?></span>
                                </div>

                                <h3 style="font-size: 1.05rem; line-height: 1.4; margin-bottom: 0.75rem; font-weight: 700;">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </h3>

                                <p style="color: #475569; font-size: 0.9rem; line-height: 1.5; margin-bottom: 1rem; flex: 1;">
                                    <?php echo htmlspecialchars(substr($post['excerpt'] ?? $post['content'], 0, 120)); ?>...
                                </p>

                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <a href="<?php echo APP_URL; ?>/news/<?php echo htmlspecialchars($post['slug']); ?>" 
                                        style="color: var(--primary-green); font-weight: 600; text-decoration: none; font-size: 0.9rem;">Read More →</a>
                                    <div style="display: flex; gap: 1.5rem; font-size: 0.8rem; color: #94a3b8;">
                                        <span title="Views">👁️ <?php echo number_format($post['views_count'] ?? 0); ?></span>
                                        <span title="Comments">💬 <?php echo number_format($post['comments_count'] ?? 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: white; border-radius: 0.75rem; border: 1px solid var(--border-color);">
                        <p style="color: var(--text-secondary); font-size: 1rem;">📰 No articles available in this section</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: SIDEBAR -->
            <aside style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Newsletter Widget -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, var(--accent-blue), #60a5fa); color: white; font-weight: 700;">📧 Newsletter</div>
                    <div style="padding: 1.25rem;">
                        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">Get daily news & opportunities</p>
                        <form id="newsletterForm" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <input type="email" placeholder="your@email.com" required 
                                style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 0.9rem;">
                            <button type="submit" style="padding: 0.75rem; background: var(--primary-green); color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; transition: background 0.3s;">Subscribe</button>
                        </form>
                        <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.75rem;">✓ We don't spam</p>
                    </div>
                </div>

                <!-- Hot Jobs Widget -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #ec4899, #f43f5e); color: white; font-weight: 700;">💼 Hot Jobs</div>
                    <div style="padding: 1rem;">
                        <a href="<?php echo APP_URL; ?>/jobs" style="display: block; padding: 0.75rem 0; color: var(--gray-dark); text-decoration: none; border-bottom: 1px solid var(--border-color); font-weight: 500; transition: color 0.3s;">🔥 Senior Developer</a>
                        <a href="<?php echo APP_URL; ?>/jobs" style="display: block; padding: 0.75rem 0; color: var(--gray-dark); text-decoration: none; border-bottom: 1px solid var(--border-color); font-weight: 500;">📊 Data Analyst</a>
                        <a href="<?php echo APP_URL; ?>/jobs" style="display: block; padding: 0.75rem 0; color: var(--primary-green); text-decoration: none; font-weight: 600;">View All Jobs →</a>
                    </div>
                </div>

                <!-- Scholarships Widget -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #f59e0b, #fb923c); color: white; font-weight: 700;">🎓 Scholarships</div>
                    <div style="padding: 1rem;">
                        <div style="padding: 0.75rem; margin-bottom: 0.75rem; border-left: 3px solid #f59e0b; background: #fffbeb; border-radius: 0.375rem;">
                            <strong style="display: block; color: var(--gray-dark); font-size: 0.9rem;">Uganda Scholarship</strong>
                            <span style="color: #64748b; font-size: 0.8rem;">⏰ Closes in 15 days</span>
                        </div>
                        <a href="#" style="color: var(--primary-green); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Browse All →</a>
                    </div>
                </div>

                <!-- Events Widget -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white; font-weight: 700;">📅 Upcoming Events</div>
                    <div style="padding: 1rem;">
                        <div style="padding: 0.75rem; margin-bottom: 0.75rem; border-left: 3px solid #8b5cf6;">
                            <strong style="display: block; color: var(--gray-dark); font-size: 0.9rem;">Tech Conference</strong>
                            <span style="color: #64748b; font-size: 0.8rem;">May 20 • 2:00 PM</span>
                        </div>
                        <a href="#" style="color: var(--primary-green); text-decoration: none; font-weight: 600; font-size: 0.9rem;">See More →</a>
                    </div>
                </div>

                <!-- Verified Businesses -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #10b981, #34d399); color: white; font-weight: 700;">🏢 Top Businesses</div>
                    <div style="padding: 1rem;">
                        <div style="padding: 0.75rem; margin-bottom: 0.75rem; border-left: 3px solid #10b981;">
                            <strong style="display: block; color: var(--gray-dark);">TechHub Rwanda ✓</strong>
                            <span style="color: #64748b; font-size: 0.8rem;">Software • Kigali</span>
                        </div>
                        <a href="<?php echo APP_URL; ?>/business" style="color: var(--primary-green); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Business Directory →</a>
                    </div>
                </div>

                <!-- Social Follow -->
                <div style="background: white; border-radius: 0.75rem; border: 1px solid var(--border-color); overflow: hidden; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #06b6d4, #22d3ee); color: white; font-weight: 700;">🌐 Follow Us</div>
                    <div style="padding: 1.5rem; display: flex; gap: 1rem; justify-content: center; font-size: 1.8rem;">
                        <a href="https://facebook.com" target="_blank" style="text-decoration: none;">📘</a>
                        <a href="https://twitter.com" target="_blank" style="text-decoration: none;">𝕏</a>
                        <a href="https://youtube.com" target="_blank" style="text-decoration: none;">▶️</a>
                        <a href="https://linkedin.com" target="_blank" style="text-decoration: none;">💼</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- ===== SECTION 6: PAGINATION ===== -->
    <?php if ($totalPages > 1): ?>
    <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; text-align: center;">
        <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
            <?php if ($page > 1): ?>
                <a href="<?php echo APP_URL; ?>/news?page=1" style="padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--primary-green); font-weight: 600;">« First</a>
                <a href="<?php echo APP_URL; ?>/news?page=<?php echo $page - 1; ?>" style="padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--primary-green); font-weight: 600;">‹ Previous</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <?php if ($i == $page): ?>
                    <span style="padding: 0.6rem 1rem; background: var(--primary-green); color: white; border-radius: 0.375rem; font-weight: 600;"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/news?page=<?php echo $i; ?>" style="padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--text-secondary);"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?php echo APP_URL; ?>/news?page=<?php echo $page + 1; ?>" style="padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--primary-green); font-weight: 600;">Next ›</a>
                <a href="<?php echo APP_URL; ?>/news?page=<?php echo $totalPages; ?>" style="padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 0.375rem; text-decoration: none; color: var(--primary-green); font-weight: 600;">Last »</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== SECTION 7: FOOTER ===== -->
    <footer style="background: var(--gray-dark); color: #e2e8f0; margin-top: 4rem; padding: 3rem 1.5rem 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4 style="color: white; margin-bottom: 1rem; font-weight: 700;">About InfoHub</h4>
                <p style="line-height: 1.6; font-size: 0.95rem;">Rwanda's unified digital platform for news, jobs, scholarships, and business opportunities.</p>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 1rem; font-weight: 700;">Quick Links</h4>
                <ul style="list-style: none; line-height: 1.8;">
                    <li><a href="<?php echo APP_URL; ?>/news" style="color: var(--primary-green); text-decoration: none;">📰 News</a></li>
                    <li><a href="<?php echo APP_URL; ?>/jobs" style="color: var(--primary-green); text-decoration: none;">💼 Jobs</a></li>
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
            &copy; 2024 InfoHub Rwanda. Building Rwanda's digital future 🇷🇼
        </div>
    </footer>

    <script>
    // Newsletter Subscription
    document.getElementById('newsletterForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        if (!email.includes('@')) {
            alert('❌ Please enter a valid email');
            return;
        }
        
        try {
            const response = await fetch('<?php echo APP_URL; ?>/news/newsletter', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('✅ Subscribed successfully!');
                this.reset();
            } else {
                alert('❌ ' + (data.message || 'Subscription failed'));
            }
        } catch (e) {
            console.error('Error:', e);
            alert('❌ Network error. Please try again.');
        }
    });

    // Lazy Load Images
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            observer.observe(img);
        });
    }
    </script>

    <style>
        /* Responsive Design */
        @media (max-width: 1024px) {
            main { grid-template-columns: 1fr !important; }
            main aside { grid-column: 1 / -1; }
            article { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns: 2fr 1fr"] { grid-template-columns: 1fr !important; }
        }

        @media (max-width: 768px) {
            nav { flex-wrap: wrap; gap: 0.5rem; }
            nav > * { flex-basis: 100%; }
            nav form { order: 3; width: 100%; max-width: 100%; }
            article { padding: 1rem !important; gap: 1rem !important; }
            h2 { font-size: 1.2rem !important; }
            h3 { font-size: 1rem !important; }
        }

        @media (max-width: 480px) {
            nav, form { font-size: 0.85rem; }
            article { grid-template-columns: 1fr !important; }
            article > div { min-height: 120px !important; }
            [style*="padding: 0.75rem"] { padding: 0.5rem !important; }
            [style*="gap: 1.5rem"] { gap: 1rem !important; }
        }
    </style>

</body>
</html>
