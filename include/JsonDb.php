<?php
class JsonDb {
    private string $db_filename;
    private string $table_name;

    public function __construct($db_filename, $table_name) {
        $this->db_filename = $db_filename;
        $this->table_name = $table_name;
    }

    public function start() {

    }
}
