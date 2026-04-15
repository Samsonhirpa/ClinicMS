<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patientpayments extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'reception']);
        $this->load->model('Patient_payment_model');
        $this->load->model('Doctor_case_model');
        $this->load->model('Lab_request_model');
        $this->load->model('Fee_model');
    }

    public function registration()
    {
        $this->render('patient_payments/index', [
            'payments' => $this->Patient_payment_model->getPendingByType('registration'),
            'paymentType' => 'registration',
        ], [
            'title' => 'Registration Fee Payments',
            'activeMenu' => 'registration_fee',
        ]);
    }

    public function diagnose()
    {
        $this->render('patient_payments/index', [
            'payments' => $this->Patient_payment_model->getPendingByType('diagnose'),
            'paymentType' => 'diagnose',
        ], [
            'title' => 'Diagnose Fee Payments',
            'activeMenu' => 'diagnose_fee',
        ]);
    }

    public function lab()
    {
        $this->render('patient_payments/index', [
            'payments' => $this->Patient_payment_model->getPendingByType('lab'),
            'paymentType' => 'lab',
        ], [
            'title' => 'Lab Fee Payments',
            'activeMenu' => 'lab_fee',
        ]);
    }

    public function approve($id)
    {
        $payment = $this->Patient_payment_model->getById($id);
        if (!$payment || $payment->status !== 'pending') {
            show_404();
        }

        $this->db->trans_start();
        $this->Patient_payment_model->markStatus($id, 'approved', (int) $this->session->userdata('user_id'));

        if ($payment->payment_type === 'registration') {
            $this->Doctor_case_model->createForPatient((int) $payment->patient_id);
        }

        if ($payment->payment_type === 'diagnose') {
            $case = $this->db->get_where('doctor_cases', ['patient_id' => (int) $payment->patient_id])->row();
            if ($case) {
                $labRequest = $this->Lab_request_model->findByCaseId((int) $case->id);
                if ($labRequest && $labRequest->status === 'pending_payment') {
                    $labFee = $this->resolveLabFee();
                    $this->Patient_payment_model->create([
                        'patient_id' => (int) $payment->patient_id,
                        'payment_type' => 'lab',
                        'amount' => $labFee['amount'],
                        'currency' => $labFee['currency'],
                        'status' => 'pending',
                    ]);
                    $this->Doctor_case_model->updateStatus((int) $case->id, 'waiting_lab_payment');
                } else {
                    $this->Doctor_case_model->updateStatus((int) $case->id, 'completed');
                }
            }
        }

        if ($payment->payment_type === 'lab') {
            $case = $this->db->get_where('doctor_cases', ['patient_id' => (int) $payment->patient_id])->row();
            if ($case) {
                $labRequest = $this->Lab_request_model->findByCaseId((int) $case->id);
                if ($labRequest) {
                    $this->Lab_request_model->setStatus((int) $labRequest->id, 'paid');
                }
                $this->Doctor_case_model->updateStatus((int) $case->id, 'ready_for_lab');
            }
        }

        $this->db->trans_complete();

        $this->session->set_flashdata('success', 'Payment approved and workflow updated.');
        redirect($this->routeForType($payment->payment_type));
    }

    public function reject($id)
    {
        $payment = $this->Patient_payment_model->getById($id);
        if (!$payment || $payment->status !== 'pending') {
            show_404();
        }

        $this->Patient_payment_model->markStatus($id, 'rejected', (int) $this->session->userdata('user_id'));
        $this->session->set_flashdata('success', 'Payment rejected.');
        redirect($this->routeForType($payment->payment_type));
    }

    private function routeForType($type)
    {
        if ($type === 'diagnose') {
            return 'patient-payments/diagnose';
        }

        if ($type === 'lab') {
            return 'patient-payments/lab';
        }

        return 'patient-payments/registration';
    }

    private function resolveLabFee()
    {
        $fees = $this->Fee_model->getAll('other');
        foreach ($fees as $fee) {
            if ($fee->status === 'active') {
                return ['amount' => (float) $fee->amount, 'currency' => (string) $fee->currency];
            }
        }

        return ['amount' => 500, 'currency' => 'ETB'];
    }
}
