<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'reception']);
        $this->load->model('Patient_model');
        $this->load->model('Patient_payment_model');
        $this->load->model('Fee_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $search = trim((string) $this->input->get('search', true));

        $this->render('patients/index', [
            'patients' => $this->Patient_model->getAll($search),
            'search' => $search,
            'registrationFee' => $this->resolveFee('registration'),
        ], [
            'title' => 'Manage Patients',
            'activeMenu' => 'patients_manage',
        ]);
    }

    public function create()
    {
        $this->form_validation->set_rules('name', 'Patient Name', 'required|min_length[2]');
        $this->form_validation->set_rules('age', 'Age', 'integer');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female,other]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('patients');
        }

        $patientData = [
            'patient_code' => $this->Patient_model->nextPatientCode(),
            'name' => $this->input->post('name', true),
            'age' => $this->input->post('age', true),
            'gender' => $this->input->post('gender', true),
            'phone' => $this->input->post('phone', true),
            'address' => $this->input->post('address', true),
            'created_by' => (int) $this->session->userdata('user_id'),
        ];

        $this->db->trans_start();
        $this->Patient_model->create($patientData);
        $patientId = (int) $this->db->insert_id();

        $registrationFee = $this->resolveFee('registration');
        $this->Patient_payment_model->create([
            'patient_id' => $patientId,
            'payment_type' => 'registration',
            'amount' => $registrationFee['amount'],
            'currency' => $registrationFee['currency'],
            'status' => 'pending',
        ]);
        $this->db->trans_complete();

        $this->session->set_flashdata('success', 'Patient registered. Registration payment is now pending approval.');
        redirect('patients');
    }

    public function update($id)
    {
        $patient = $this->Patient_model->getById($id);
        if (!$patient) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Patient Name', 'required|min_length[2]');
        $this->form_validation->set_rules('age', 'Age', 'integer');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female,other]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('patients');
        }

        $this->Patient_model->update($id, [
            'name' => $this->input->post('name', true),
            'age' => $this->input->post('age', true),
            'gender' => $this->input->post('gender', true),
            'phone' => $this->input->post('phone', true),
            'address' => $this->input->post('address', true),
        ]);

        $this->session->set_flashdata('success', 'Patient details updated.');
        redirect('patients');
    }

    private function resolveFee($type)
    {
        $fees = $this->Fee_model->getAll($type);
        foreach ($fees as $fee) {
            if ($fee->status === 'active') {
                return ['amount' => (float) $fee->amount, 'currency' => (string) $fee->currency];
            }
        }

        return [
            'amount' => (float) $this->Fee_model->getSetting('default_registration_fee', 200),
            'currency' => (string) $this->Fee_model->getSetting('default_registration_currency', 'ETB'),
        ];
    }
}
