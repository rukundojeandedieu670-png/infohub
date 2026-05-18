<?php
/**
 * SEO Controller
 */

class SeoController extends Controller {

    public function sitemap() {
        header('Content-Type: application/xml');
        
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Job.php';
        require_once ROOT_PATH . '/app/models/Business.php';

        $postModel = new Post();
        $jobModel = new Job();
        $businessModel = new Business();

        $posts = $postModel->db->prepare("SELECT id, slug, updated_at FROM posts WHERE status = 'published'");
        $posts->db->execute();
        $posts = $posts->resultSet();

        $jobs = $jobModel->db->prepare("SELECT id, slug, updated_at FROM jobs WHERE status = 'open'");
        $jobs->db->execute();
        $jobs = $jobs->resultSet();

        $businesses = $businessModel->db->prepare("SELECT id, slug, updated_at FROM businesses WHERE verification_status = 'verified'");
        $businesses->db->execute();
        $businesses = $businesses->resultSet();

        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Home
        echo '<url>' . PHP_EOL;
        echo '  <loc>' . APP_URL . '</loc>' . PHP_EOL;
        echo '  <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
        echo '  <changefreq>weekly</changefreq>' . PHP_EOL;
        echo '  <priority>1.0</priority>' . PHP_EOL;
        echo '</url>' . PHP_EOL;

        // Posts
        foreach ($posts as $post) {
            echo '<url>' . PHP_EOL;
            echo '  <loc>' . APP_URL . '/news/' . htmlspecialchars($post['slug']) . '</loc>' . PHP_EOL;
            echo '  <lastmod>' . date('Y-m-d', strtotime($post['updated_at'])) . '</lastmod>' . PHP_EOL;
            echo '  <changefreq>monthly</changefreq>' . PHP_EOL;
            echo '  <priority>0.8</priority>' . PHP_EOL;
            echo '</url>' . PHP_EOL;
        }

        // Jobs
        foreach ($jobs as $job) {
            echo '<url>' . PHP_EOL;
            echo '  <loc>' . APP_URL . '/jobs/' . htmlspecialchars($job['slug']) . '</loc>' . PHP_EOL;
            echo '  <lastmod>' . date('Y-m-d', strtotime($job['updated_at'])) . '</lastmod>' . PHP_EOL;
            echo '  <changefreq>weekly</changefreq>' . PHP_EOL;
            echo '  <priority>0.8</priority>' . PHP_EOL;
            echo '</url>' . PHP_EOL;
        }

        // Businesses
        foreach ($businesses as $business) {
            echo '<url>' . PHP_EOL;
            echo '  <loc>' . APP_URL . '/business/' . htmlspecialchars($business['slug']) . '</loc>' . PHP_EOL;
            echo '  <lastmod>' . date('Y-m-d', strtotime($business['updated_at'])) . '</lastmod>' . PHP_EOL;
            echo '  <changefreq>monthly</changefreq>' . PHP_EOL;
            echo '  <priority>0.7</priority>' . PHP_EOL;
            echo '</url>' . PHP_EOL;
        }

        echo '</urlset>' . PHP_EOL;
        exit;
    }

    public function robots() {
        header('Content-Type: text/plain');
        
        echo "User-agent: *" . PHP_EOL;
        echo "Allow: /" . PHP_EOL;
        echo "Disallow: /admin" . PHP_EOL;
        echo "Disallow: /auth" . PHP_EOL;
        echo "Disallow: /profile" . PHP_EOL;
        echo "Crawl-delay: 1" . PHP_EOL;
        echo "" . PHP_EOL;
        echo "Sitemap: " . APP_URL . "/sitemap.xml" . PHP_EOL;
        exit;
    }
}
