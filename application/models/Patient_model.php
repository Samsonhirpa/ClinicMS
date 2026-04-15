<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model
{
    protected $table = 'patients';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    public function ensureTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `patient_code` VARCHAR(50) NOT NULL,
            `name` VARCHAR(150) NOT NULL,
            `age` INT UNSIGNED NULL,
            `gender` VARCHAR(20) NULL,
            `phone` VARCHAR(30) NULL,
            `address` VARCHAR(255) NULL,
            `status` VARCHAR(20) NOT NULL DEFAULT 'active',
            `created_by` INT UNSIGNED NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_patient_code` (`patient_code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function getAll($search = '')
    {
        if ($search !== '') {
            $this->db->group_start()
                ->like('patient_code', $search)
                ->or_like('name', $search)
                ->or_like('phone', $search)
                ->group_end();
        }

        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function create(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function countAll()
    {
        return (int) $this->db->count_all($this->table);
    }

    public function nextPatientCode()
    {
        $next = $this->countAll() + 1;
        return 'PT-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
