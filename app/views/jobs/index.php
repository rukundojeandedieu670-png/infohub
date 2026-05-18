<?php
/**
 * Jobs List View
 */
ob_start();
?>

<div class="jobs-container">
    <!-- Beautiful Header Section -->
    <section class="jobs-header-section">
        <div class="jobs-header">
            <h1>Welcome to Rwanda Job Opportunities Portal</h1>
            <div class="hero-search-wrapper">
                <form method="GET" action="<?php echo APP_URL; ?>/jobs" class="hero-search-form">
                    <input type="text" name="search" placeholder="Search job titles, positions..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="hero-search-input">
                    <button type="submit" class="hero-search-btn">Search</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Encouragement Banner -->
    <section class="jobs-encouragement-banner">
        <p>We actively encourage all qualified candidates to apply for open job positions</p>
    </section>

    <!-- Two Column Layout -->
    <div class="mifotra-layout">
        <!-- Main Content Column -->
        <div class="jobs-main-column">
            <!-- Jobs List Header -->
            <div class="jobs-section-header">
                <h2>New Job Advertisements</h2>
                <span class="jobs-count">(<?php echo count($jobs); ?>)</span>
            </div>
            <!-- Jobs List -->
            <div class="jobs-list">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <?php if ($job['is_featured']): ?>
                    <span class="job-featured-badge">FEATURED</span>
                    <?php endif; ?>

                    <!-- Job Card Header -->
                    <div class="job-card-header">
                        <h3>
                            <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($job['slug']); ?>" class="job-title-link">
                                <?php echo htmlspecialchars($job['title']); ?>
                            </a>
                        </h3>
                    </div>

                    <!-- Job Card Metadata -->
                    <div class="job-card-meta">
                        <span class="meta-item">
                            <span class="icon">🏢</span>
                            <span><?php echo htmlspecialchars($job['category_name'] ?? 'General'); ?></span>
                        </span>
                        <span class="meta-category">
                            <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                        </span>
                        <span class="meta-item">
                            <span class="icon">📍</span>
                            <span><?php echo htmlspecialchars($job['location'] ?? 'Rwanda'); ?></span>
                        </span>
                    </div>

                    <!-- Job Card Description -->
                    <div class="job-card-description">
                        <?php 
                            $description = htmlspecialchars($job['description'] ?? '');
                            echo strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                        ?>
                    </div>

                    <!-- Salary Info -->
                    <?php if ($job['salary_min'] && $job['salary_max']): ?>
                    <div class="job-card-salary">
                        <strong>Salary Range</strong>
                        <div class="salary-amount">RWF <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?>/month</div>
                    </div>
                    <?php endif; ?>

                    <!-- Job Card Footer -->
                    <div class="job-card-footer">
                        <div class="deadline-info">
                            <span class="deadline-label">Deadline:</span>
                            <span class="deadline-date"><?php echo date('M d, Y', strtotime($job['deadline'])); ?></span>
                        </div>
                        <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($job['slug']); ?>" class="btn btn-primary btn-small">
                            View & Apply
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>

        <!-- Sidebar Column -->
        <aside class="jobs-sidebar">
            <!-- Announcements Section -->
            <div class="announcements-section">
                <div class="announcements-header">ANNOUNCEMENTS</div>
                <div class="announcements-list">
                    <div class="announcement-item">
                        <h4><a href="#">New Vacant Positions Available</a></h4>
                        <p>We are actively recruiting talented professionals for key roles across the country. Apply now!</p>
                        <a href="#" class="announcement-link">For more details <span>click here</span></a>
                    </div>
                </div>
            </div>
        </aside>
    </div>
        <?php else: ?>
            <div class="no-results-container">
                <div class="no-results">
                    <p>😔 No jobs found matching your criteria</p>
                    <p>Try adjusting your search filters or browse all job categories</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
