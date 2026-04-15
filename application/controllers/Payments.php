<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends MY_Controller
{
    private $feeTypes = ['registration', 'diagnose', 'other'];
    private $currencies = ['ETB', 'USD', 'EUR'];
    private $statuses = ['active', 'inactive'];

    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'reception']);
        $this->load->model('Fee_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $type = trim((string) $this->input->get('type', true));
        if ($type !== '' && !in_array($type, $this->feeTypes, true)) {
            $type = '';
        }

        $this->render('payments/index', [
            'fees' => $this->Fee_model->getAll($type),
            'type' => $type,
            'feeTypes' => $this->feeTypes,
            'currencies' => $this->currencies,
            'statuses' => $this->statuses,
        ], [
            'title' => 'Payment & Fees',
            'activeMenu' => 'payments',
        ]);
    }

    public function create()
    {
        $this->validateFee();
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('payments');
        }

        $this->Fee_model->create($this->feePayload());
        $this->session->set_flashdata('success', 'Fee item created.');
        redirect('payments');
    }

    public function update($id)
    {
        if (!$this->Fee_model->getById($id)) {
            show_404();
        }

        $this->validateFee();
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('payments');
        }

        $this->Fee_model->update($id, $this->feePayload());
        $this->session->set_flashdata('success', 'Fee item updated.');
        redirect('payments');
    }

    public function delete($id)
    {
        if (!$this->Fee_model->getById($id)) {
            show_404();
        }

        $this->Fee_model->delete($id);
        $this->session->set_flashdata('success', 'Fee item deleted.');
        redirect('payments');
    }

    public function settings()
    {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('currency', 'Currency', 'required|in_list[' . implode(',', $this->currencies) . ']');
            $this->form_validation->set_rules('registration_fee', 'Registration Fee', 'required|numeric');

            if ($this->form_validation->run()) {
                $this->Fee_model->upsertSetting('default_registration_currency', $this->input->post('currency', true));
                $this->Fee_model->upsertSetting('default_registration_fee', $this->input->post('registration_fee', true));
                $this->session->set_flashdata('success', 'Settings updated successfully.');
                redirect('payments/settings');
            }
            $this->session->set_flashdata('error', validation_errors());
            redirect('payments/settings');
        }

        $this->render('payments/settings', [
            'currencies' => $this->currencies,
            'defaultCurrency' => $this->Fee_model->getSetting('default_registration_currency', 'ETB'),
            'defaultRegistrationFee' => $this->Fee_model->getSetting('default_registration_fee', '200'),
        ], [
            'title' => 'Payment Settings',
            'activeMenu' => 'settings',
        ]);
    }

    private function validateFee()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[2]');
        $this->form_validation->set_rules('fee_type', 'Fee Type', 'required|in_list[' . implode(',', $this->feeTypes) . ']');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('currency', 'Currency', 'required|in_list[' . implode(',', $this->currencies) . ']');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[' . implode(',', $this->statuses) . ']');
    }

    private function feePayload()
    {
        return [
            'name' => $this->input->post('name', true),
            'fee_type' => $this->input->post('fee_type', true),
            'amount' => $this->input->post('amount', true),
            'currency' => $this->input->post('currency', true),
            'status' => $this->input->post('status', true),
        ];
    }
}
