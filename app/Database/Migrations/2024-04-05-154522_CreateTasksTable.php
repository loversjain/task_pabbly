<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'due_date' => [
                    'type' => 'DATE',
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['1', '2', '3'],
                    'default' => '1',
                    'comment' => 'pending->1,completed->2,blocked->3',
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'created_by' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_by' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ])
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');
        $this->forge->addForeignKey('created_by', 'users', 'id');
        $this->forge->addForeignKey('updated_by', 'users', 'id');
    }

    public function down()
    {
        $this->dbforge->drop_table('tasks');
    }
}
