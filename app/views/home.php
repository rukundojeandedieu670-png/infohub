<?php
// Extract variables
extract($__data ?? []);

// Render layout
ob_start();
?>

<section class="hero">
    <div class="container">
        <h1>Welcome to InfoHub</h1>
        <p>Rwanda's comprehensive digital information platform connecting you with news, jobs, and business opportunities</p>
        <div class="hero-buttons">
            <a href="<?php echo APP_URL; ?>/news" class="btn btn-primary btn-lg">Read Latest News</a>
            <a href="<?php echo APP_URL; ?>/jobs" class="btn btn-secondary btn-lg">Browse Jobs</a>
        </div>
    </div>
</section>

<div class="container mt-5 mb-5">
    <h2 style="margin-bottom: 2rem;">Latest News</h2>
    
    <div class="grid grid-3">
        <?php if (isset($featured_posts) && !empty($featured_posts)): ?>
            <?php foreach ($featured_posts as $post): ?>
                <div class="card featured-item">
                    <?php if ($post['featured_image']): ?>
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <?php else: ?>
                        <div style="width: 100%; height: 250px; background: linear-gradient(135deg, #16a34a, #2563eb); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                            📰
                        </div>
                    <?php endif; ?>
                    
                    <div class="featured-content">
                        <?php if (isset($post['category_name'])): ?>
                            <span class="featured-category"><?php echo htmlspecialchars($post['category_name']); ?></span>
                        <?php endif; ?>
                        <h3 class="featured-title">
                            <a href="<?php echo APP_URL; ?>/news/<?php echo htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p class="featured-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 100)); ?>...</p>
                        <small style="color: var(--text-secondary);">
                            By <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?> • 
                            <?php echo date('M d, Y', strtotime($post['published_at'])); ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No news available yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5 mb-5">
    <h2 style="margin-bottom: 2rem;">Featured Jobs</h2>
    
    <div class="grid grid-2">
        <?php if (isset($featured_jobs) && !empty($featured_jobs)): ?>
            <?php foreach ($featured_jobs as $job): ?>
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin-bottom: 0.25rem;">
                                    <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($job['slug']); ?>">
                                        <?php echo htmlspecialchars($job['title']); ?>
                                    </a>
                                </h3>
                            </div>
                            <?php if ($job['featured']): ?>
                                <span style="background: linear-gradient(135deg, #16a34a, #2563eb); color: white; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">⭐ Featured</span>
                            <?php endif; ?>
                        </div>
                        
                        <p style="margin: 1rem 0; color: var(--text-secondary); font-size: 0.9rem;">
                            📍 <?php echo htmlspecialchars($job['location']); ?> • 
                            <span style="background: var(--bg-gray); padding: 0.25rem 0.5rem; border-radius: 4px;">
                                <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                            </span>
                        </p>
                        
                        <?php if ($job['salary_min'] || $job['salary_max']): ?>
                            <p style="color: var(--primary); font-weight: 600; margin: 0.5rem 0;">
                                💰 RWF <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?>
                            </p>
                        <?php endif; ?>
                        
                        <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($job['slug']); ?>" class="btn btn-primary btn-sm" style="margin-top: 1rem; width: 100%;">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No featured jobs available yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5 mb-5">
    <h2 style="margin-bottom: 2rem;">Featured Businesses</h2>
    
    <div class="grid grid-3">
        <?php if (isset($featured_businesses) && !empty($featured_businesses)): ?>
            <?php foreach ($featured_businesses as $business): ?>
                <div class="card">
                    <div style="height: 150px; background: linear-gradient(135deg, #16a34a, #2563eb); display: flex; align-items: center; justify-content: center;">
                        <?php if ($business['logo']): ?>
                            <img src="<?php echo htmlspecialchars($business['logo']); ?>" alt="<?php echo htmlspecialchars($business['name']); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <div style="font-size: 3rem;">🏢</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h3 style="margin-bottom: 0.5rem;">
                            <a href="<?php echo APP_URL; ?>/business/<?php echo htmlspecialchars($business['slug']); ?>">
                                <?php echo htmlspecialchars($business['name']); ?>
                            </a>
                        </h3>
                        
                        <p style="font-size: 0.9rem; margin-bottom: 0.5rem;">
                            📍 <?php echo htmlspecialchars($business['location'] ?? 'Location not specified'); ?>
                        </p>
                        
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <span style="background: var(--bg-gray); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem;">
                                <?php echo htmlspecialchars($business['category_name'] ?? 'General'); ?>
                            </span>
                            <span style="background: #ecfdf5; color: var(--success); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">
                                ✓ Verified
                            </span>
                        </div>
                        
                        <a href="<?php echo APP_URL; ?>/business/<?php echo htmlspecialchars($business['slug']); ?>" class="btn btn-primary btn-sm" style="width: 100%;">
                            View Profile
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No featured businesses available yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include layout
include __DIR__ . '/layouts/main.php';
?>
