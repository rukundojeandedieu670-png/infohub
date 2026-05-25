<?php
/**
 * Jobs Controller
 */

class JobsController extends Controller {

    public function index($page = 1) {
        require_once ROOT_PATH . '/app/models/Job.php';

        $jobModel = new Job();
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $jobs = $jobModel->getOpenJobs($limit, $offset);
        $totalJobs = $jobModel->count('status', 'open');
        $totalPages = ceil($totalJobs / $limit);

        // Get search parameters
        $search = $this->sanitize($_GET['search'] ?? '');
        $category = $_GET['category'] ?? null;
        $type = $this->sanitize($_GET['type'] ?? '');
        $allowedTypes = ['full-time', 'part-time', 'contract', 'temporary', 'internship'];
        $type = in_array($type, $allowedTypes) ? $type : null;

        if ($search || $category || $type) {
            $jobs = $jobModel->search($search, $category, $type, $limit, $offset);
        }

        $userApplications = [];
        if ($this->user) {
            $this->db->prepare(
                "SELECT a.*, j.title, j.slug, u.company AS company_name, j.location FROM job_applications a JOIN jobs j ON a.job_id = j.id LEFT JOIN users u ON j.employer_id = u.id WHERE a.applicant_id = ? ORDER BY a.applied_at DESC"
            );
            $applicantId = $this->user['id'];
            $this->db->bind('i', $applicantId);
            $this->db->execute();
            $userApplications = $this->db->resultSet();
        }

        $pageTitle = 'Jobs | InfoHub';
        if ($type) {
            $pageTitle = ucfirst(str_replace('-', ' ', $type)) . ' | InfoHub';
        }

        // Build a human-friendly vacancies message after evaluating search/type
        $vacanciesMessage = 'Explore available vacancies and internships below.';
        if (!empty($type)) {
            $vacanciesMessage = 'Showing ' . ucfirst(str_replace('-', ' ', $type)) . ' vacancies.';
        }
        if (!empty($search)) {
            $vacanciesMessage = 'Search results for "' . htmlspecialchars($search) . '"';
        }

        $this->view('jobs/index', [
            'jobs' => $jobs,
            'userApplications' => $userApplications,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'type' => $type,
            'totalJobs' => $totalJobs,
            'vacanciesMessage' => $vacanciesMessage,
            'page_title' => $pageTitle,
            'flash' => $this->getFlash(),
            'user' => $this->user
        ]);
    }

    public function show($slug) {
        require_once ROOT_PATH . '/app/models/Job.php';

        $jobModel = new Job();
        $job = $jobModel->getBySlug($slug);

        if (!$job) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        // Check if user applied
        $userApplied = false;
        if ($this->user) {
            $this->db->prepare(
                "SELECT id FROM job_applications WHERE job_id = ? AND applicant_id = ?"
            );
            $jobId = $job['id'];
            $userId = $this->user['id'];
            $this->db->bind('i', $jobId);
            $this->db->bind('i', $userId);
            $this->db->execute();
            $userApplied = $this->db->single() !== null;

            Logger::logActivity($this->user['id'], 'view_job', 'jobs', 'Viewed job: ' . $job['title']);
        }

        $this->view('jobs/show', [
            'job' => $job,
            'userApplied' => $userApplied,
            'page_title' => htmlspecialchars($job['title']) . ' | InfoHub',
            'user' => $this->user
        ]);
    }

    public function applyForm($id) {
        $this->requireLogin();

        require_once ROOT_PATH . '/app/models/Job.php';
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $this->view('jobs/apply', [
            'job' => $job,
            'user' => $this->user
        ]);
    }

    public function apply() {
        // Log incoming apply attempt for debugging
        try {
            Logger::logActivity($_SESSION['user_id'] ?? null, 'apply_attempt', 'jobs', json_encode(['method' => $_SERVER['REQUEST_METHOD'], 'job_id' => $_POST['job_id'] ?? null]));
        } catch (Exception $e) {
            error_log('Logger failed: ' . $e->getMessage());
        }

        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/jobs');
        }

        // Get job_id from POST
        $id = intval($_POST['job_id'] ?? 0);

        // Validate CSRF token
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token.';
            $this->redirect(APP_URL . '/jobs');
            return;
        }

        // Validate job exists
        require_once ROOT_PATH . '/app/models/Job.php';
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job) {
            $_SESSION['error'] = 'Job not found';
            $this->redirect(APP_URL . '/jobs');
            return;
        }

        // Check already applied
        require_once ROOT_PATH . '/core/Database.php';
        $db = Database::getInstance();
        $db->prepare(
            "SELECT id FROM job_applications WHERE job_id = ? AND applicant_id = ?"
        );
        $applicantId = $_SESSION['user_id'] ?? null;
        $db->bind('i', $id);
        $db->bind('i', $applicantId);
        $db->execute();

        if ($db->single()) {
            $_SESSION['error'] = 'You have already applied for this job';
            $this->redirect(APP_URL . '/jobs/' . htmlspecialchars($job['slug']));
            return;
        }

        // Handle CV upload
        $cvPath = null;
        if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/uploads/cvs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Validate file type
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($_FILES['cv_file']['type'], $allowed_types)) {
                $_SESSION['error'] = 'Invalid file type. Please upload PDF or Word document.';
                $this->redirect(APP_URL . '/jobs/' . htmlspecialchars($job['slug']));
                return;
            }

            // Validate file size (5MB)
            if ($_FILES['cv_file']['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = 'File size exceeds 5MB limit.';
                $this->redirect(APP_URL . '/jobs/' . htmlspecialchars($job['slug']));
                return;
            }

            $fileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $uploadDir . $fileName)) {
                $cvPath = '/uploads/cvs/' . $fileName;
            }
        }

        // Create application
        $coverLetter = htmlspecialchars($_POST['cover_letter'] ?? '');

        try {
            $db->prepare(
                "INSERT INTO job_applications (job_id, applicant_id, cv_path, cover_letter, status, applied_at) VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $db->bind('i', $id);
            $db->bind('i', $applicantId);
            $db->bind('s', $cvPath);
            $db->bind('s', $coverLetter);
            $status = 'pending';
            $db->bind('s', $status);
            $db->execute();

            Logger::logActivity($applicantId, 'apply_job', 'jobs', json_encode([
                'job_id' => $id,
                'job_title' => $job['title']
            ]));

            $_SESSION['success'] = 'Application submitted successfully! Check your email for updates.';
            $this->redirect(APP_URL . '/jobs/' . htmlspecialchars($job['slug']));

        } catch (Exception $e) {
            Logger::logError('Job Application Error', $e->getMessage(), __FILE__, __LINE__);
            $_SESSION['error'] = 'Failed to submit application. Please try again.';
            $this->redirect(APP_URL . '/jobs/' . htmlspecialchars($job['slug']));
        }
    }
}
