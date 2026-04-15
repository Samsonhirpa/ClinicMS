<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function getByEmail($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }

    public function getAll()
    {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function getFiltered(array $filters = [])
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start()
                ->like('name', $search)
                ->or_like('email', $search)
                ->group_end();
        }

        if (!empty($filters['role'])) {
            $this->db->where('role', $filters['role']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }

        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function getCounts()
    {
        $totalUsers = (int) $this->db->count_all($this->table);
        $activeUsers = (int) $this->db->where('status', 'active')->count_all_results($this->table);
        $admins = (int) $this->db->where('role', 'admin')->count_all_results($this->table);

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'admins' => $admins,
            'inactive_users' => max($totalUsers - $activeUsers, 0),
        ];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function create(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => (int) $id]);
    }
}
