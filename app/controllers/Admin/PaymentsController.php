<?php
/**
 * Admin Payments Controller
 */

class PaymentsController extends Controller {
    public function index($page = 1) {
        $this->requireAdmin();

        require_once ROOT_PATH . '/app/models/Payment.php';
        $paymentModel = new Payment();

        $limit = 40;
        $offset = ($page - 1) * $limit;

        $payments = $paymentModel->getAllWithUser($limit, $offset);
        $totalPayments = $paymentModel->count();
        $statusSummary = $paymentModel->getStatusSummary();
        $donationSummary = $paymentModel->getDonationSummary();

        $totalPages = max(1, ceil($totalPayments / $limit));

        Logger::logAdminAction($this->user['id'], 'view_payments', 'payments', 0);

        $this->view('admin/payments/index', [
            'page_title' => 'Payments | Admin Dashboard',
            'payments' => $payments,
            'page' => $page,
            'totalPages' => $totalPages,
            'paymentCount' => $totalPayments,
            'statusSummary' => $statusSummary,
            'donationSummary' => $donationSummary,
            'user' => $this->user
        ]);
    }
}
