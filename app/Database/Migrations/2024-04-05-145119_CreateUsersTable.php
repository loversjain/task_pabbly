<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['1', '2'],
                'default' => '2',
                'comment' => 'Admin->1, User->2'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['1', '2'],
                'default' => '1',
                'comment' => 'Activate->1, Block->2'
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
