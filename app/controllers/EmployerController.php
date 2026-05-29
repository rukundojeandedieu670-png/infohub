<?php
/**
 * Employer Controller
 */

class EmployerController extends Controller {
    public function jobs() {
        $this->requireRole('Employer');

        require_once ROOT_PATH . '/app/models/Job.php';
        require_once ROOT_PATH . '/app/models/Category.php';

        $jobModel = new Job();
        $categoryModel = new Category();
        $jobs = $jobModel->getByEmployer($this->user['id']);
        $categories = $categoryModel->getAll();

        $this->view('employer/jobs', [
            'jobs' => $jobs,
            'categories' => $categories,
            'user' => $this->user,
            'flash' => $this->getFlash(),
            'page_title' => 'Employer Dashboard | InfoHub'
        ]);
    }

    public function createJob() {
        $this->requireRole('Employer');

        require_once ROOT_PATH . '/app/models/Category.php';
        $categoryModel = new Category();

        $this->view('employer/job-form', [
            'categories' => $categoryModel->getAll(),
            'user' => $this->user,
            'page_title' => 'Post Job | InfoHub',
            'formAction' => APP_URL . '/employer/jobs/create'
        ]);
    }

    public function storeJob() {
        $this->requireRole('Employer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/employer/jobs');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        require_once ROOT_PATH . '/app/models/Job.php';
        $jobModel = new Job();

        $data = [
            'title' => $this->sanitize($_POST['title'] ?? ''),
            'description' => $this->sanitize($_POST['description'] ?? ''),
            'category_id' => intval($_POST['category_id'] ?? 0),
            'location' => $this->sanitize($_POST['location'] ?? ''),
            'job_type' => $this->sanitize($_POST['job_type'] ?? ''),
            'experience_level' => $this->sanitize($_POST['experience_level'] ?? ''),
            'deadline' => $this->sanitize($_POST['deadline'] ?? ''),
            'employer_id' => $this->user['id'],
            'seo_meta_title' => $this->sanitize($_POST['seo_meta_title'] ?? ''),
            'seo_meta_description' => $this->sanitize($_POST['seo_meta_description'] ?? '')
        ];

        if (empty($data['title']) || empty($data['description']) || empty($data['category_id']) || empty($data['location']) || empty($data['job_type']) || empty($data['deadline'])) {
            $this->setFlash('error', 'Please fill in all required fields.');
            $this->redirect(APP_URL . '/employer/jobs/create');
        }

        try {
            $jobModel->createJob($data);
            $this->setFlash('success', 'Job posted successfully.');
            $this->redirect(APP_URL . '/employer/jobs');
        } catch (Exception $e) {
            Logger::logError('Employer Job Create Error', $e->getMessage());
            $this->setFlash('error', 'Unable to create job. Please try again.');
            $this->redirect(APP_URL . '/employer/jobs/create');
        }
    }

    public function editJob($id) {
        $this->requireRole('Employer');

        require_once ROOT_PATH . '/app/models/Job.php';
        require_once ROOT_PATH . '/app/models/Category.php';

        $jobModel = new Job();
        $job = $jobModel->findById((int)$id);

        if (!$job || $job['employer_id'] != $this->user['id']) {
            http_response_code(403);
            $this->view('errors/403');
            return;
        }

        $this->view('employer/job-form', [
            'job' => $job,
            'categories' => (new Category())->getAll(),
            'user' => $this->user,
            'page_title' => 'Edit Job | InfoHub',
            'formAction' => APP_URL . '/employer/jobs/' . intval($id) . '/update'
        ]);
    }

    public function updateJob($id) {
        $this->requireRole('Employer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/employer/jobs');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        require_once ROOT_PATH . '/app/models/Job.php';
        $jobModel = new Job();
        $job = $jobModel->findById((int)$id);

        if (!$job || $job['employer_id'] != $this->user['id']) {
            http_response_code(403);
            $this->view('errors/403');
            return;
        }

        $data = [
            'title' => $this->sanitize($_POST['title'] ?? ''),
            'description' => $this->sanitize($_POST['description'] ?? ''),
            'category_id' => intval($_POST['category_id'] ?? 0),
            'location' => $this->sanitize($_POST['location'] ?? ''),
            'job_type' => $this->sanitize($_POST['job_type'] ?? ''),
            'experience_level' => $this->sanitize($_POST['experience_level'] ?? ''),
            'deadline' => $this->sanitize($_POST['deadline'] ?? ''),
            'seo_meta_title' => $this->sanitize($_POST['seo_meta_title'] ?? ''),
            'seo_meta_description' => $this->sanitize($_POST['seo_meta_description'] ?? '')
        ];

        if (empty($data['title']) || empty($data['description']) || empty($data['category_id']) || empty($data['location']) || empty($data['job_type']) || empty($data['deadline'])) {
            $this->setFlash('error', 'Please fill in all required fields.');
            $this->redirect(APP_URL . '/employer/jobs/' . intval($id) . '/edit');
        }

        try {
            $jobModel->update((int)$id, $data);
            $this->setFlash('success', 'Job updated successfully.');
            $this->redirect(APP_URL . '/employer/jobs');
        } catch (Exception $e) {
            Logger::logError('Employer Job Update Error', $e->getMessage());
            $this->setFlash('error', 'Unable to update job. Please try again.');
            $this->redirect(APP_URL . '/employer/jobs/' . intval($id) . '/edit');
        }
    }

    public function applications($id) {
        $this->requireRole('Employer');

        require_once ROOT_PATH . '/app/models/Job.php';

        $jobModel = new Job();
        $job = $jobModel->findById((int)$id);

        if (!$job || $job['employer_id'] != $this->user['id']) {
            http_response_code(403);
            $this->view('errors/403');
            return;
        }

        $this->db->prepare(
            "SELECT a.*, u.first_name, u.last_name, u.email, j.title as job_title " .
            "FROM job_applications a " .
            "JOIN users u ON a.applicant_id = u.id " .
            "JOIN jobs j ON a.job_id = j.id " .
            "WHERE a.job_id = ? " .
            "ORDER BY a.applied_at DESC"
        );
        $this->db->bind('i', $id);
        $this->db->execute();
        $applications = $this->db->resultSet();

        $this->view('employer/applications', [
            'job' => $job,
            'applications' => $applications,
            'user' => $this->user,
            'page_title' => 'Job Applications | InfoHub'
        ]);
    }
}
