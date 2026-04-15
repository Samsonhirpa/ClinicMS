<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('users');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $email = trim($this->input->post('email', true));
                $password = (string) $this->input->post('password', false);
                $user = $this->User_model->getByEmail($email);

                if ($user && $user->status === 'active' && $this->User_model->isValidPassword($user, $password)) {
                    $this->session->set_userdata([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ]);

                    redirect('users');
                }

                $this->session->set_flashdata('error', 'Invalid credentials or inactive account.');
                redirect('login');
            }
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
