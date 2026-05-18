<?php
// Job application form view
$title = "Apply for Job - " . htmlspecialchars($job['title']);
$description = "Submit your application for " . htmlspecialchars($job['title']);
include ROOT_PATH . '/app/views/layouts/main.php';
$employmentType = $job['job_type'] ?? $job['employment_type'] ?? null;
?>

<div class="container mt-5 mb-5 apply-page">
    <!-- Back to Job -->
    <a href="<?php echo APP_URL; ?>/jobs/<?php echo htmlspecialchars($job['slug']); ?>" class="btn btn-ghost btn-sm mb-4">
        ← Back to Job
    </a>

    <div class="grid grid-2" style="gap: 2rem; align-items: flex-start;">
        <!-- Application Form -->
        <div>
            <h1 style="color: #1f2937; margin: 20px 0;">Apply for Position</h1>

            <!-- Error Messages -->
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    ⚠️ <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="<?php echo APP_URL; ?>/jobs/apply" enctype="multipart/form-data" class="card card-body">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">

                <!-- Cover Letter -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                        Cover Letter
                    </label>
                    <textarea 
                        name="cover_letter" 
                        rows="6"
                        placeholder="Tell the employer why you're interested in this position and what makes you a great fit..."
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box; font-family: inherit;"
                    ></textarea>
                    <small style="color: #6b7280; display: block; margin-top: 5px;">Maximum 1000 characters</small>
                </div>

                <!-- CV/Resume Upload -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                        Upload CV/Resume
                    </label>
                    <div class="drop-zone" id="dropZone">
                        <input 
                            type="file" 
                            name="cv_file" 
                            id="cvFile"
                            accept=".pdf,.doc,.docx"
                            required
                            style="display: none;"
                        >
                        <div style="color: #6b7280;">
                            <div style="font-size: 2rem; margin-bottom: 10px;">📄</div>
                            <p style="margin: 0 0 5px 0; font-weight: 600; color: #1f2937;">
                                Drop your CV here or click to browse
                            </p>
                            <p style="margin: 0; font-size: 0.9rem;">
                                PDF, DOC, or DOCX (Max 5MB)
                            </p>
                        </div>
                    </div>
                    <small style="color: #6b7280; display: block; margin-top: 8px;">
                        ✓ PDF, Microsoft Word, or Google Docs formats
                        <br>✓ Maximum file size: 5MB
                    </small>
                </div>

                <!-- Selected File Info -->
                <div id="fileInfo" style="display: none; margin-bottom: 20px; padding: 15px; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px;">
                    <p style="margin: 0; color: #065f46; font-weight: 600;">
                        ✓ File selected: <span id="fileName"></span>
                    </p>
                </div>

                <!-- Additional Info -->
                <div class="form-group" style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; color: #374151; font-weight: 600;">Contact Information</p>
                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 0.9rem;">
                        <strong style="color: #1f2937;">Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                    </p>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                        <strong style="color: #1f2937;">Name:</strong> Will be taken from your profile
                    </p>
                </div>

                <!-- Checkbox -->
                <div style="margin-bottom: 20px; display: flex; gap: 10px; align-items: flex-start;">
                    <input 
                        type="checkbox" 
                        name="confirm" 
                        id="confirm"
                        required
                        style="margin-top: 5px;"
                    >
                    <label for="confirm" style="color: #6b7280; font-size: 0.95rem; cursor: pointer;">
                        I confirm that the information provided is accurate and I authorize the employer to contact me about this position.
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn btn-primary"
                    style="width: 100%; padding: 12px; font-size: 1rem;"
                >
                    📤 Submit Application
                </button>
            </form>
        </div>

        <!-- Job Details Sidebar -->
        <div>
            <div class="card sticky-card">
                <div class="card-body">
                    <h2 class="mb-2">
                        <?php echo htmlspecialchars($job['title']); ?>
                    </h2>
                    <p class="text-muted mb-4">
                        Posted <?php echo date('M j, Y', strtotime($job['created_at'])); ?>
                    </p>

                    <hr>

                    <!-- Job Details -->
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div>
                            <p class="text-muted small uppercase mb-1">
                                EMPLOYMENT TYPE
                            </p>
                            <p class="mb-0 font-semibold">
                                <?php echo htmlspecialchars($employmentType ? ucfirst(str_replace('_', ' ', $employmentType)) : 'Not specified'); ?>
                            </p>
                        </div>

                    <div>
                        <p style="margin: 0 0 5px 0; color: #6b7280; font-weight: 600; font-size: 0.85rem;">
                            LOCATION
                        </p>
                        <p style="margin: 0; color: #1f2937; font-weight: 600;">
                            📍 <?php echo htmlspecialchars($job['location']); ?>
                        </p>
                    </div>

                    <?php if (!empty($job['salary_min']) && !empty($job['salary_max'])): ?>
                        <div>
                            <p style="margin: 0 0 5px 0; color: #6b7280; font-weight: 600; font-size: 0.85rem;">
                                SALARY RANGE
                            </p>
                            <p style="margin: 0; color: #16a34a; font-weight: 700;">
                                RWF <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <p style="margin: 0 0 5px 0; color: #6b7280; font-weight: 600; font-size: 0.85rem;">
                            STATUS
                        </p>
                        <p style="margin: 0;">
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 0.85rem; font-weight: 600; background: #d1fae5; color: #065f46;">
                                Open
                            </span>
                        </p>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                <!-- Requirements Preview -->
                <div>
                    <p style="margin: 0 0 10px 0; color: #6b7280; font-weight: 600; font-size: 0.85rem;">
                        KEY REQUIREMENTS
                    </p>
                    <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 0.9rem;">
                        <?php 
                        $requirements = explode("\n", $job['requirements']);
                        foreach (array_slice($requirements, 0, 5) as $req) {
                            if (trim($req)) {
                                echo '<li style="margin-bottom: 5px;">' . htmlspecialchars(trim($req)) . '</li>';
                            }
                        }
                        if (count($requirements) > 5) {
                            echo '<li style="color: #16a34a; font-weight: 600;">And ' . (count($requirements) - 5) . ' more...</li>';
                        }
                        ?>
                    </ul>
                </div>

                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                <!-- Tips -->
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px;">
                    <p style="margin: 0 0 10px 0; color: #1e40af; font-weight: 600; font-size: 0.9rem;">
                        💡 Application Tips
                    </p>
                    <ul style="margin: 0; padding-left: 20px; color: #1e40af; font-size: 0.85rem;">
                        <li style="margin-bottom: 5px;">Customize your cover letter for this role</li>
                        <li style="margin-bottom: 5px;">Use clear, professional language</li>
                        <li style="margin-bottom: 5px;">Highlight relevant experience</li>
                        <li>Check file size before uploading</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // File upload handling
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('cvFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#16a34a';
        dropZone.style.background = '#f0fdf4';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.background = '#f8fafc';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    // File selection
    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
            // Validate file type
            const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a PDF or Word document');
                fileInput.value = '';
                fileInfo.style.display = 'none';
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit');
                fileInput.value = '';
                fileInfo.style.display = 'none';
                return;
            }

            // Show file info
            fileName.textContent = file.name;
            fileInfo.style.display = 'block';
        }
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
</script>
