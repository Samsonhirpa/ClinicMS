<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->load->model('User_model');
        $this->load->model('Fee_model');
    }

    public function index()
    {
        $counts = $this->User_model->getCounts();
        $users = $this->User_model->getAll();

        $roleMap = [];
        foreach ($users as $user) {
            $role = (string) $user->role;
            $roleMap[$role] = isset($roleMap[$role]) ? $roleMap[$role] + 1 : 1;
        }

        $feeItems = $this->Fee_model->getAll();
        $revenueProjection = 0;
        foreach ($feeItems as $item) {
            $revenueProjection += (float) $item->amount;
        }

        $data = [
            'counts' => $counts,
            'roleMap' => $roleMap,
            'monthlyAnalytics' => [
                'New Registrations' => min($counts['total_users'] + 4, 100),
                'Active Sessions' => min($counts['active_users'] + 10, 100),
                'Revenue Goal' => min((int) round($revenueProjection / 20), 100),
            ],
            'reports' => [
                'Total configured fee items' => count($feeItems),
                'Projected visit revenue (ETB)' => number_format($revenueProjection, 2),
                'Inactive user accounts' => $counts['inactive_users'],
            ],
        ];

        $this->render('dashboard/index', $data, [
            'title' => 'Dashboard Analytics',
            'activeMenu' => 'dashboard',
        ]);
    }
}
