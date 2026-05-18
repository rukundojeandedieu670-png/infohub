<?php
/**
 * Business Directory View
 */
ob_start();
?>

<div class="business-container">
    <div class="business-header">
        <h1>Business Directory</h1>
        <p>Discover verified businesses and opportunities in Rwanda</p>
    </div>

    <div class="business-filters">
        <form method="GET" action="<?php echo APP_URL; ?>/business" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Business name or service" 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="filter-group">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="technology" <?php echo isset($_GET['category']) && $_GET['category'] === 'technology' ? 'selected' : ''; ?>>Technology</option>
                    <option value="business" <?php echo isset($_GET['category']) && $_GET['category'] === 'business' ? 'selected' : ''; ?>>Business Services</option>
                    <option value="finance" <?php echo isset($_GET['category']) && $_GET['category'] === 'finance' ? 'selected' : ''; ?>>Finance</option>
                    <option value="education" <?php echo isset($_GET['category']) && $_GET['category'] === 'education' ? 'selected' : ''; ?>>Education</option>
                </select>
            </div>
            <div class="filter-group">
                <select name="location">
                    <option value="">All Locations</option>
                    <option value="kigali" <?php echo isset($_GET['location']) && $_GET['location'] === 'kigali' ? 'selected' : ''; ?>>Kigali</option>
                    <option value="gitarama" <?php echo isset($_GET['location']) && $_GET['location'] === 'gitarama' ? 'selected' : ''; ?>>Gitarama</option>
                    <option value="huye" <?php echo isset($_GET['location']) && $_GET['location'] === 'huye' ? 'selected' : ''; ?>>Huye</option>
                    <option value="musanze" <?php echo isset($_GET['location']) && $_GET['location'] === 'musanze' ? 'selected' : ''; ?>>Musanze</option>
                </select>
            </div>
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <div class="business-list">
        <?php if (!empty($businesses)): ?>
            <?php foreach ($businesses as $business): ?>
                <div class="business-card">
                    <div class="business-image">
                        <?php if (!empty($business['logo'])): ?>
                            <img src="<?php echo htmlspecialchars($business['logo']); ?>" alt="<?php echo htmlspecialchars($business['name']); ?>">
                        <?php else: ?>
                            <div class="no-image">📦</div>
                        <?php endif; ?>
                    </div>

                    <div class="business-info">
                        <div class="business-header">
                            <h2><?php echo htmlspecialchars($business['name']); ?></h2>
                            <?php if ($business['is_featured']): ?>
                                <span class="badge-featured">Featured</span>
                            <?php endif; ?>
                        </div>

                        <div class="business-meta">
                            <span class="category"><?php echo htmlspecialchars($business['category_name'] ?? 'General'); ?></span>
                            <span class="location">📍 <?php echo htmlspecialchars($business['location']); ?></span>
                            <span class="verified">✓ Verified</span>
                        </div>

                        <p class="business-description">
                            <?php echo htmlspecialchars(substr($business['description'], 0, 200) . '...'); ?>
                        </p>

                        <div class="business-contact">
                            <?php if (!empty($business['phone'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($business['phone']); ?>" class="contact-link">
                                    📞 <?php echo htmlspecialchars($business['phone']); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($business['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($business['email']); ?>" class="contact-link">
                                    ✉️ <?php echo htmlspecialchars($business['email']); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="business-footer">
                            <a href="<?php echo APP_URL; ?>/business/<?php echo htmlspecialchars($business['slug']); ?>" class="btn-view">View Details</a>
                            <span class="views">👁️ <?php echo intval($business['views']); ?> views</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <p>No businesses found. Try adjusting your search filters.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.business-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.business-header {
    text-align: center;
    margin-bottom: 40px;
}

.business-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.business-header p {
    font-size: 1.1rem;
    color: #666;
}

.business-filters {
    margin-bottom: 40px;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
}

.filter-form {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.btn-search {
    padding: 10px 30px;
    background: #0066cc;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
}

.btn-search:hover {
    background: #0052a3;
}

.business-list {
    display: grid;
    gap: 25px;
}

.business-card {
    display: flex;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.3s;
}

.business-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.business-image {
    flex-shrink: 0;
    width: 200px;
    height: 200px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.business-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    font-size: 3rem;
    color: #ddd;
}

.business-info {
    flex: 1;
    padding: 25px;
}

.business-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.business-header h2 {
    font-size: 1.4rem;
    margin: 0;
    color: #1a1a1a;
}

.badge-featured {
    background: #ff9800;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.business-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 0.95rem;
    color: #666;
    flex-wrap: wrap;
}

.business-description {
    color: #555;
    line-height: 1.6;
    margin: 15px 0;
}

.business-contact {
    display: flex;
    gap: 20px;
    margin: 15px 0;
    flex-wrap: wrap;
}

.contact-link {
    color: #0066cc;
    text-decoration: none;
    font-size: 0.95rem;
}

.contact-link:hover {
    text-decoration: underline;
}

.business-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn-view {
    display: inline-block;
    padding: 10px 25px;
    background: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-view:hover {
    background: #0052a3;
}

.views {
    color: #999;
    font-size: 0.9rem;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
    }

    .filter-group {
        min-width: 100%;
    }

    .business-card {
        flex-direction: column;
    }

    .business-image {
        width: 100%;
    }

    .business-footer {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
