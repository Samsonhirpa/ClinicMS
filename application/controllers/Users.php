<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    private $roles = ['admin', 'reception', 'doctor', 'lab', 'nurse'];
    private $statuses = ['active', 'inactive'];

    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
        $this->load->model('User_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $filters = [
            'search' => trim((string) $this->input->get('search', true)),
            'role' => trim((string) $this->input->get('role', true)),
            'status' => trim((string) $this->input->get('status', true)),
        ];

        $data['users'] = $this->User_model->getFiltered($filters);
        $data['roles'] = $this->roles;
        $data['statuses'] = $this->statuses;
        $data['filters'] = $filters;
        $data['counts'] = $this->User_model->getCounts();

        $this->load->view('users/index', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[' . implode(',', $this->roles) . ']');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[' . implode(',', $this->statuses) . ']');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users');
        }

        $this->User_model->create([
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'password' => $this->input->post('password', false),
            'role' => $this->input->post('role', true),
            'status' => $this->input->post('status', true),
        ]);

        $this->session->set_flashdata('success', 'User registered successfully.');
        redirect('users');
    }

    public function update($id)
    {
        $user = $this->User_model->getById($id);
        if (!$user) {
            show_404();
        }

        $emailRule = 'required|valid_email';
        if ($this->input->post('email', true) !== $user->email) {
            $emailRule .= '|is_unique[users.email]';
        }

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', $emailRule);
        $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[' . implode(',', $this->roles) . ']');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[' . implode(',', $this->statuses) . ']');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users');
        }

        $this->User_model->update($id, [
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'password' => $this->input->post('password', false),
            'role' => $this->input->post('role', true),
            'status' => $this->input->post('status', true),
        ]);

        $this->session->set_flashdata('success', 'User updated successfully.');
        redirect('users');
    }

    public function delete($id)
    {
        $currentUserId = (int) $this->session->userdata('user_id');
        if ((int) $id === $currentUserId) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('users');
        }

        $user = $this->User_model->getById($id);
        if (!$user) {
            show_404();
        }

        $this->User_model->delete($id);
        $this->session->set_flashdata('success', 'User deleted successfully.');
        redirect('users');
    }
}
