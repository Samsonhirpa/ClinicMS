<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fee_model extends CI_Model
{
    protected $table = 'fees';
    protected $settingsTable = 'app_settings';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTables();
    }

    public function ensureTables()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `fee_type` VARCHAR(40) NOT NULL,
            `amount` DECIMAL(10,2) NOT NULL,
            `currency` VARCHAR(5) NOT NULL DEFAULT 'ETB',
            `status` VARCHAR(20) NOT NULL DEFAULT 'active',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->settingsTable}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `setting_key` VARCHAR(100) NOT NULL,
            `setting_value` VARCHAR(255) NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `setting_key_unique` (`setting_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->seedDefaults();
    }

    public function seedDefaults()
    {
        if ((int) $this->db->count_all($this->table) === 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->insert_batch($this->table, [
                [
                    'name' => 'Registration Fee',
                    'fee_type' => 'registration',
                    'amount' => 200,
                    'currency' => 'ETB',
                    'status' => 'active',
                    'created_at' => $now,
                ],
                [
                    'name' => 'Diagnose Fee',
                    'fee_type' => 'diagnose',
                    'amount' => 350,
                    'currency' => 'ETB',
                    'status' => 'active',
                    'created_at' => $now,
                ],
            ]);
        }

        $this->setSettingIfMissing('default_registration_currency', 'ETB');
        $this->setSettingIfMissing('default_registration_fee', '200');
    }

    public function getAll($feeType = '')
    {
        if ($feeType !== '') {
            $this->db->where('fee_type', $feeType);
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

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => (int) $id]);
    }

    public function upsertSetting($key, $value)
    {
        $payload = [
            'setting_value' => (string) $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $exists = $this->db->get_where($this->settingsTable, ['setting_key' => $key])->row();
        if ($exists) {
            return $this->db->where('setting_key', $key)->update($this->settingsTable, $payload);
        }

        $payload['setting_key'] = $key;
        return $this->db->insert($this->settingsTable, $payload);
    }

    public function getSetting($key, $default = '')
    {
        $row = $this->db->get_where($this->settingsTable, ['setting_key' => $key])->row();
        return $row ? $row->setting_value : $default;
    }

    private function setSettingIfMissing($key, $value)
    {
        if (!$this->db->get_where($this->settingsTable, ['setting_key' => $key])->row()) {
            $this->upsertSetting($key, $value);
        }
    }
}
