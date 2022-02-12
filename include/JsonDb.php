<?php
class JsonDb {
    private string $db_filename;
    private string $db_name;
    private array $data;

    public function __construct() {
        //$this->db_filename = $db_filename;
    }

    public function create($login, $pass, $email, $name) {
        $data = file_get_contents('db.json');
        $data = json_decode($data, true);
        $add_arr = array(
            'login' => $login,
            'password' => $pass,
            'email' => $email,
            'name' => $name
        );
        $data[] = $add_arr;
        $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('db.json', $data);
    }

    public function read() {
        $data = file_get_contents($this->db_filename);
        $data = json_decode($data);
        $index = 1;
        if (!empty($data)) {
            foreach($data as $row){
                echo $row->login;
                echo $row->password;
                echo $row->email;
                echo $row->name;
                $index++;
            }
        }
    }

    public function update($edit_id, $login, $pass, $email, $name) {
        $data = file_get_contents($this->db_filename);
        $data_array = json_decode($data, true);

        $update_arr = array(
            'login' => $login,
            'password' => $pass,
            'email' => $email,
            'name' => $name
        );

        $data_array[$edit_id] = $update_arr;

        $data = json_encode($data_array, JSON_PRETTY_PRINT);
        file_put_contents($this->db_filename, $data);
    }

    public function delete($delete_id) {
        $data = file_get_contents($this->db_filename);
        $data = json_decode($data, true);

        unset($data[$delete_id]);

        //encode back to json
        $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->db_filename, $data);
    }
}
