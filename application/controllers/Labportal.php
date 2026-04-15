<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Labportal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'lab']);
        $this->load->model('Lab_request_model');
        $this->load->model('Doctor_case_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->render('lab/index', [
            'newRequests' => $this->Lab_request_model->getPaidRequests(),
            'allRequests' => $this->Lab_request_model->getAllRequests(),
        ], [
            'title' => 'Laboratory Portal',
            'activeMenu' => 'lab_portal',
        ]);
    }

    public function complete($id)
    {
        $request = $this->db->get_where('lab_requests', ['id' => (int) $id])->row();
        if (!$request || $request->status !== 'paid') {
            show_404();
        }

        $this->form_validation->set_rules('result_text', 'Lab Result', 'required|min_length[3]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('lab');
        }

        $this->Lab_request_model->complete($id, trim((string) $this->input->post('result_text', true)), (int) $this->session->userdata('user_id'));
        $this->Doctor_case_model->updateStatus((int) $request->doctor_case_id, 'completed');

        $this->session->set_flashdata('success', 'Lab result saved successfully.');
        redirect('lab');
    }
}
