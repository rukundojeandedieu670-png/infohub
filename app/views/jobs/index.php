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

    <style>
        .jobs-filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 0;
            margin: 0 0 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .jobs-filter-label {
            font-weight: 700;
            color: #111827;
            min-width: 100px;
        }
        .jobs-filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .filter-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 999px;
            color: #374151;
            background: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .filter-pill:hover,
        .filter-pill.active {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
        }
        .job-card-meta {
            display: grid;
            grid-template-columns: repeat(4, auto);
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }
        .jobs-filter-note {
            padding: 1rem 0;
            color: #4b5563;
            font-size: 0.95rem;
        }
        .jobs-dashboard {
            display: grid;
            grid-template-columns: minmax(0, 1.8fr) minmax(280px, 0.9fr);
            gap: 1.5rem;
            align-items: flex-start;
        }
        .jobs-sidebar {
            position: sticky;
            top: 1rem;
        }
        .vacancy-summary {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            margin-bottom: 1.25rem;
        }
        .vacancy-summary h3 {
            margin: 0;
            font-size: 1.05rem;
            color: #111827;
        }
        .vacancy-summary span {
            color: #6b7280;
            font-size: 0.95rem;
        }
        .application-summary {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #ffffff;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .application-summary h2 {
            margin: 0 0 0.75rem 0;
            font-size: 1.15rem;
        }
        .application-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f9fafb;
        }
        .application-card-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .application-card-meta,
        .application-card-status {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            color: #4b5563;
            font-size: 0.95rem;
        }
        .application-card-status strong {
            color: #111827;
            font-weight: 600;
        }
        .application-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .application-status-badge.pending { background: #fef3c7; color: #92400e; }
        .application-status-badge.reviewed { background: #d1fae5; color: #166534; }
        .application-status-badge.shortlisted { background: #dbeafe; color: #1e3a8a; }
        .application-status-badge.rejected { background: #fee2e2; color: #991b1b; }
        .application-status-badge.accepted { background: #d1fae5; color: #14532d; }
        .job-card-company {
            display: block;
            margin-bottom: 0.75rem;
            color: #111827;
            font-weight: 600;
        }
        .job-card-meta {
            display: grid;
            grid-template-columns: repeat(4, auto);
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }
        .meta-badge.internship-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            color: #1d4ed8;
            background: #e0e7ff;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
    <section class="jobs-filter-bar">
        <div class="jobs-filter-label">Filter by type:</div>
        <div class="jobs-filter-buttons">
            <?php
            $types = [
                '' => 'All',
                'full-time' => 'Full Time',
                'part-time' => 'Part Time',
                'contract' => 'Contract',
                'temporary' => 'Temporary',
                'internship' => 'Internship'
            ];
            foreach ($types as $filterValue => $label):
                $active = ($type === $filterValue) ? 'active' : '';
                $url = APP_URL . '/jobs' . ($filterValue ? '?type=' . urlencode($filterValue) : '');
            ?>
                <a href="<?php echo $url; ?>" class="filter-pill <?php echo $active; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="vacancy-summary">
        <div>
            <h3><?php echo $type ? ucfirst(str_replace('-', ' ', $type)) . ' Vacancies' : 'Available Job Vacancies'; ?></h3>
            <span><?php echo $vacanciesMessage; ?></span>
        </div>
        <div>
            <span><strong><?php echo count($jobs); ?></strong> matching vacancies</span>
            <span><strong><?php echo $totalJobs; ?></strong> open positions total</span>
        </div>
    </div>

    <div class="jobs-dashboard">
        <div class="jobs-main-column">
            <!-- Jobs List Header -->
            <div class="jobs-section-header">
                <h2><?php echo $type ? ucfirst(str_replace('-', ' ', $type)) . ' Opportunities' : 'New Job Advertisements'; ?></h2>
                <span class="jobs-count">(<?php echo count($jobs); ?>)</span>
            </div>
            <!-- Jobs List -->
            <?php if (!empty($jobs)): ?>
            <div class="jobs-list">
                <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <?php if ($job['is_featured']): ?>
                    <span class="job-featured-badge">FEATURED</span>
                    <?php endif; ?>

                    <!-- Job Card Header -->
                    <div class="job-card-header">
                        <span class="job-card-company"><?php echo htmlspecialchars($job['company_name'] ?? 'Employer'); ?></span>
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
                        <?php if ($job['job_type'] === 'internship'): ?>
                            <span class="meta-badge internship-badge">INTERNSHIP</span>
                        <?php endif; ?>
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
            <?php else: ?>
                <div class="no-results-container">
                    <div class="no-results">
                        <p>😔 No jobs found matching your criteria</p>
                        <p>Try adjusting your search filters or browse all job categories</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Column -->
        <aside class="jobs-sidebar">
            <?php if ($user): ?>
            <div class="application-summary">
                <h2>Your Applications</h2>
                <p style="margin:0 0 1rem;color:#6b7280;">Track the vacancies you have already applied to and follow application status.</p>
                <?php if (!empty($userApplications)): ?>
                    <?php foreach ($userApplications as $application): ?>
                        <div class="application-card">
                            <div class="application-card-title">
                                <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($application['slug']); ?>">
                                    <?php echo htmlspecialchars($application['title']); ?>
                                </a>
                            </div>
                            <div class="application-card-meta">
                                <span><?php echo htmlspecialchars($application['company_name'] ?? 'Employer'); ?></span>
                                <span>•</span>
                                <span><?php echo htmlspecialchars($application['location'] ?? 'Location'); ?></span>
                            </div>
                            <div class="application-card-status">
                                <span class="application-status-badge <?php echo strtolower($application['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($application['status'])); ?>
                                </span>
                                <span>Applied <?php echo date('M d, Y', strtotime($application['applied_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="application-card">
                        <p style="margin:0;color:#4b5563;">No applications yet. Apply to a job and it will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="application-summary">
                <h2>Application Tracker</h2>
                <p style="margin:0;color:#4b5563;">Login to save your applications and track job vacancies from one place.</p>
                <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-primary btn-small" style="display:inline-block;margin-top:1rem;">Login to manage applications</a>
            </div>
            <?php endif; ?>

            <div class="announcements-section">
                <div class="announcements-header">Vacancy Tips</div>
                <div class="announcements-list">
                    <div class="announcement-item">
                        <h4>Search by type</h4>
                        <p>Use the filter pills above to quickly jump to internships, full-time, part-time, contract, or temporary vacancies.</p>
                    </div>
                    <div class="announcement-item">
                        <h4>Stay active</h4>
                        <p>Review application deadlines, follow up early, and keep your profile updated for the best match.</p>
                    </div>
                </div>
            </div>
            </aside>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
