<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patientpayments extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'reception']);
        $this->load->model('Patient_payment_model');
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

    public function approve($id)
    {
        $payment = $this->Patient_payment_model->getById($id);
        if (!$payment || $payment->status !== 'pending') {
            show_404();
        }

        $this->Patient_payment_model->markStatus($id, 'approved', (int) $this->session->userdata('user_id'));
        $this->session->set_flashdata('success', 'Payment approved. Patient is now available for OPD based on payment type.');
        redirect($payment->payment_type === 'diagnose' ? 'patient-payments/diagnose' : 'patient-payments/registration');
    }

    public function reject($id)
    {
        $payment = $this->Patient_payment_model->getById($id);
        if (!$payment || $payment->status !== 'pending') {
            show_404();
        }

        $this->Patient_payment_model->markStatus($id, 'rejected', (int) $this->session->userdata('user_id'));
        $this->session->set_flashdata('success', 'Payment rejected.');
        redirect($payment->payment_type === 'diagnose' ? 'patient-payments/diagnose' : 'patient-payments/registration');
    }
}
