<?php
// Profile Sidebar Layout - Shared between all profile management pages
// Provides consistent sidebar navigation for profile sections
?>

<?php
// Profile Sidebar Layout - Shared between all profile management pages
// Provides consistent sidebar navigation for profile sections
?>

<style>
    /* Shared Profile Form Styles */
    .form-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
        padding-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .form-section-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin: 0 0 24px 0;
        font-weight: 400;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #111827;
        font-weight: 600;
        font-size: 0.9375rem;
        letter-spacing: -0.005em;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 11px 13px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s;
        background: white;
        color: #111827;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .form-group input:disabled {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .form-group small {
        color: #6b7280;
        display: block;
        margin-top: 6px;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .form-group small a {
        color: #059669;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .form-group small a:hover {
        color: #047857;
        text-decoration: underline;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        letter-spacing: -0.005em;
    }

    .btn-primary {
        background: #059669;
        color: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-primary:hover {
        background: #047857;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #111827;
        border: 1.5px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px 14px;
        background: #f3f4f6;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .checkbox-group:hover {
        background: #e5e7eb;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #059669;
        flex-shrink: 0;
    }

    .checkbox-group label {
        margin: 0;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        flex: 1;
    }

    .password-help {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 14px 16px;
        margin-top: 12px;
        display: none;
        animation: slideDown 0.2s ease-out;
    }

    .password-help h4 {
        color: #1e40af;
        margin: 0 0 8px 0;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .password-help ul {
        margin: 0;
        padding-left: 20px;
        color: #1e40af;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .password-help li {
        margin-bottom: 4px;
    }

    .security-tip {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 12px 14px;
        border-radius: 6px;
        margin-top: 16px;
        font-size: 0.9rem;
        color: #92400e;
        line-height: 1.5;
    }

    .security-tip strong {
        color: #78350f;
    }

    .info-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        transition: all 0.3s;
    }

    .info-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .info-card-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .info-card-value {
        color: #111827;
        font-size: 1rem;
        font-weight: 500;
    }

    .danger-zone {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 20px;
        margin-top: 32px;
    }

    .danger-zone h3 {
        color: #991b1b;
        margin: 0 0 8px 0;
        font-size: 1.125rem;
    }

    .danger-zone p {
        color: #7f1d1d;
        margin: 0 0 16px 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
        margin-top: 16px;
    }

    .btn-danger:hover {
        background: #b91c1c;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .checkbox-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .checkbox-group label {
            flex: none;
        }
    }

    :root {
        --primary-color: #059669;
        --primary-dark: #047857;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
        --border-light: #f3f4f6;
    }

    .profile-layout {
        display: flex;
        gap: 32px;
        max-width: 1200px;
        margin: 32px auto;
        padding: 0 20px;
    }

    /* Sidebar Navigation */
    .profile-sidebar {
        width: 280px;
        flex-shrink: 0;
    }

    .profile-sidebar h2 {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-secondary);
        margin: 0 0 16px 0;
        padding: 0 16px;
    }

    .sidebar-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .sidebar-nav li {
        border-bottom: 1px solid var(--border-light);
    }

    .sidebar-nav li:last-child {
        border-bottom: none;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .sidebar-nav a:hover {
        background: var(--border-light);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
    }

    .sidebar-nav a.active {
        background: var(--border-light);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
        font-weight: 600;
    }

    .sidebar-icon {
        font-size: 1.25rem;
        width: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-label {
        flex: 1;
    }

    .sidebar-badge {
        background: var(--primary-color);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Main Content Area */
    .profile-content {
        flex: 1;
        min-width: 0;
    }

    .profile-content-header {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-content-header h1 {
        color: var(--text-primary);
        margin: 0 0 8px 0;
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .profile-content-header p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 1.0625rem;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Cards */
    .card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 28px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Alerts */
    .alert {
        margin-bottom: 24px;
        padding: 16px 20px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideDown 0.3s ease-out;
        border-left: 4px solid;
        font-weight: 500;
        font-size: 1rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border-left-color: #dc2626;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left-color: #059669;
    }

    .alert-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        font-size: 1.3rem;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.6;
        transition: opacity 0.3s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-layout {
            flex-direction: column;
            gap: 24px;
            margin: 24px auto;
        }

        .profile-sidebar {
            width: 100%;
        }

        .sidebar-nav {
            display: flex;
            overflow-x: auto;
            border-radius: 8px;
        }

        .sidebar-nav li {
            border-bottom: none;
            border-right: 1px solid var(--border-light);
            flex-shrink: 0;
        }

        .sidebar-nav li:last-child {
            border-right: none;
        }

        .sidebar-nav a {
            padding: 12px 14px;
            font-size: 0.9rem;
            gap: 8px;
            flex-direction: column;
            text-align: center;
        }

        .sidebar-label {
            display: none;
        }

        .sidebar-icon {
            font-size: 1.5rem;
            width: 100%;
        }

        .card {
            padding: 24px;
        }

        .profile-content-header h1 {
            font-size: 1.875rem;
        }
    }

    @media (max-width: 480px) {
        .profile-layout {
            padding: 0 12px;
            margin: 16px auto;
            gap: 16px;
        }

        .profile-content-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
        }

        .profile-content-header h1 {
            font-size: 1.5rem;
            margin-bottom: 4px;
        }

        .profile-content-header p {
            font-size: 0.95rem;
        }

        .sidebar-nav a {
            padding: 11px 12px;
        }

        .card {
            padding: 20px;
        }
    }
</style>

<?php
// Current page URL for active link detection
$current_url = $_SERVER['REQUEST_URI'];
$is_personal = strpos($current_url, '/personal') !== false;
$is_professional = strpos($current_url, '/professional') !== false;
$is_security = strpos($current_url, '/security') !== false;
$is_account = strpos($current_url, '/account') !== false;
?>

<div class="profile-layout">
    <!-- Sidebar Navigation -->
    <aside class="profile-sidebar">
        <h2>Profile Management</h2>
        <ul class="sidebar-nav">
            <li>
                <a href="<?php echo APP_URL; ?>/profile/edit/personal" class="<?php echo $is_personal ? 'active' : ''; ?>">
                    <span class="sidebar-icon">👤</span>
                    <span class="sidebar-label">Personal Info</span>
                </a>
            </li>
            <li>
                <a href="<?php echo APP_URL; ?>/profile/edit/professional" class="<?php echo $is_professional ? 'active' : ''; ?>">
                    <span class="sidebar-icon">💼</span>
                    <span class="sidebar-label">Professional</span>
                </a>
            </li>
            <li>
                <a href="<?php echo APP_URL; ?>/profile/edit/security" class="<?php echo $is_security ? 'active' : ''; ?>">
                    <span class="sidebar-icon">🔒</span>
                    <span class="sidebar-label">Security</span>
                </a>
            </li>
            <li>
                <a href="<?php echo APP_URL; ?>/profile/edit/account" class="<?php echo $is_account ? 'active' : ''; ?>">
                    <span class="sidebar-icon">⚙️</span>
                    <span class="sidebar-label">Account</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="profile-content">
