<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctorportal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'doctor']);
        $this->load->model('Doctor_case_model');
        $this->load->model('Patient_payment_model');
        $this->load->model('Lab_request_model');
        $this->load->model('Fee_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function dashboard()
    {
        $doctorId = (int) $this->session->userdata('user_id');
        $allCases = $this->Doctor_case_model->getDoctorCases($doctorId);

        $this->render('doctor/dashboard', [
            'counts' => [
                'newPatients' => $this->Doctor_case_model->countByStatus('new'),
                'myPatients' => count($allCases),
                'waitingDiagnose' => $this->Doctor_case_model->countByStatus('waiting_diagnose_payment', $doctorId),
                'waitingLab' => $this->Doctor_case_model->countByStatus('waiting_lab_payment', $doctorId),
            ],
            'analytics' => [
                'Consultation Progress' => min(count($allCases) * 10, 100),
                'Diagnose Payment Clearance' => min(max(100 - ($this->Doctor_case_model->countByStatus('waiting_diagnose_payment', $doctorId) * 20), 0), 100),
                'Lab Readiness' => min(max(100 - ($this->Doctor_case_model->countByStatus('waiting_lab_payment', $doctorId) * 20), 0), 100),
            ],
        ], [
            'title' => 'Doctor Dashboard',
            'activeMenu' => 'doctor_dashboard',
        ]);
    }

    public function newPatients()
    {
        $this->render('doctor/new_patients', [
            'cases' => $this->Doctor_case_model->getNewCases(),
        ], [
            'title' => 'New Patients',
            'activeMenu' => 'doctor_new_patients',
        ]);
    }

    public function claim($caseId)
    {
        $case = $this->Doctor_case_model->getById($caseId);
        if (!$case || $case->status !== 'new') {
            show_404();
        }

        $this->Doctor_case_model->assignToDoctor($caseId, (int) $this->session->userdata('user_id'));
        redirect('doctor/consult/' . (int) $caseId);
    }

    public function allPatients()
    {
        $doctorId = (int) $this->session->userdata('user_id');
        $cases = $this->Doctor_case_model->getDoctorCases($doctorId);

        $paymentMap = [];
        foreach ($cases as $case) {
            $paymentMap[$case->patient_id] = $this->Patient_payment_model->getLatestForPatient($case->patient_id);
        }

        $this->render('doctor/all_patients', [
            'cases' => $cases,
            'paymentMap' => $paymentMap,
        ], [
            'title' => 'All Patients',
            'activeMenu' => 'doctor_all_patients',
        ]);
    }

    public function consult($caseId)
    {
        $doctorId = (int) $this->session->userdata('user_id');
        $case = $this->Doctor_case_model->getCaseWithPatient($caseId);
        if (!$case || (int) $case->doctor_id !== $doctorId) {
            show_404();
        }

        $diagnoseFee = $this->resolveDiagnoseFee();

        $this->render('doctor/consult', [
            'case' => $case,
            'diagnoseFee' => $diagnoseFee,
            'existingLab' => $this->Lab_request_model->findByCaseId($caseId),
        ], [
            'title' => 'Consult Patient',
            'activeMenu' => 'doctor_all_patients',
        ]);
    }

    public function submitConsult($caseId)
    {
        $doctorId = (int) $this->session->userdata('user_id');
        $case = $this->Doctor_case_model->getById($caseId);
        if (!$case || (int) $case->doctor_id !== $doctorId) {
            show_404();
        }

        $this->form_validation->set_rules('consultation_note', 'Consultation Note', 'required|min_length[3]');
        $this->form_validation->set_rules('recommended_tests', 'Recommended Tests', 'trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('doctor/consult/' . (int) $caseId);
        }

        $recommendedTests = trim((string) $this->input->post('recommended_tests', true));
        $diagnoseFee = $this->resolveDiagnoseFee();

        $this->db->trans_start();
        $this->Patient_payment_model->create([
            'patient_id' => (int) $case->patient_id,
            'payment_type' => 'diagnose',
            'amount' => $diagnoseFee['amount'],
            'currency' => $diagnoseFee['currency'],
            'status' => 'pending',
        ]);

        if ($recommendedTests !== '') {
            $this->Lab_request_model->create([
                'doctor_case_id' => (int) $caseId,
                'patient_id' => (int) $case->patient_id,
                'requested_by' => $doctorId,
                'test_name' => $recommendedTests,
                'instructions' => trim((string) $this->input->post('consultation_note', true)),
                'status' => 'pending_payment',
            ]);
        }

        $this->Doctor_case_model->updateConsultation(
            $caseId,
            $doctorId,
            trim((string) $this->input->post('consultation_note', true)),
            $recommendedTests,
            'waiting_diagnose_payment'
        );
        $this->db->trans_complete();

        $this->session->set_flashdata('success', 'Consultation saved. Diagnose fee is now pending at reception.');
        redirect('doctor/all-patients');
    }

    public function labResults()
    {
        $doctorId = (int) $this->session->userdata('user_id');
        $this->render('doctor/lab_results', [
            'cases' => $this->Doctor_case_model->getDoctorCases($doctorId),
        ], [
            'title' => 'Lab Results',
            'activeMenu' => 'doctor_lab_results',
        ]);
    }

    private function resolveDiagnoseFee()
    {
        $fees = $this->Fee_model->getAll('diagnose');
        foreach ($fees as $fee) {
            if ($fee->status === 'active') {
                return ['amount' => (float) $fee->amount, 'currency' => (string) $fee->currency];
            }
        }

        return ['amount' => 350, 'currency' => 'ETB'];
    }
}
