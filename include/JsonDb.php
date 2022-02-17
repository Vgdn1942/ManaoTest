<?php
/*
jsonDb = new JsonDB('path/to/file.json', create = false); - подключение к таблице-файлу, если create = true, создаёт файл если его не существовало, иначе выбрасывает исключение
new mysqli('table')

jsonDb->insert('array[]') - Добавляет массив в таблицу, возвращает 'true' в случае успеха
(INSERT INTO `table` 'array[]')

jsonDb->select('key', 'value') - Выбирает все записи из таблицы где 'key' соответствует 'value'
(SELECT * FROM `table` WHERE `key` = 'value')

jsonDb->selectRow('key', 'value', 'row') - Возвращает строку 'row' из таблицы где 'key' соответствует 'value'
// аналог
$res = $conn->query("SELECT * FROM `table` WHERE `key` = 'value'");
$row = $res->fetch_array();
return $row['row']

jsonDb->selectAll() - возвращает все записи из таблицы
(SELECT * FROM `table`)

jsonDb->update('key', 'value', array[]) - Заменяет строку соответствующую 'key'->'value' на данные из массива 'array'
(UPDATE `table` SET 'array[]' WHERE `key` = 'value')

jsonDb->updateAll('array[]') - Заменяет все данные в файле массивом 'array[]'

jsonDb->delete('key', 'value') - Удаляет все строки, соответствующие 'key'->'value', возвращает количество удаленных строк
(DELETE FROM `downloads` WHERE `key` = 'values')

jsonDb->deleteAll() - Удаляет все данные из таблицы-файла, возвращает 'true' в случае успеха
(TRUNCATE 'table')
*/

class JsonDb {

    protected $jsonFile;
    protected $fileHandle;
    protected $fileData = array();

    /**
     * @throws Exception
     */
    public function __construct($_jsonFile, $create = true) {
        if (!file_exists($_jsonFile)) {
            if ($create === true) {
                self::createTable($_jsonFile);
            } else {
                throw new Exception("JsonTable Error: Table not found: " . $_jsonFile);
            }
        }
        $this->jsonFile = $_jsonFile;
        $this->fileData = json_decode(file_get_contents($this->jsonFile), true);
        $this->lockFile();
    }

    /**
     * @throws Exception
     */
    public function __destruct() {
        $this->save();
        fclose($this->fileHandle);
    }

    /**
     * @throws Exception
     */
    protected function lockFile() {
        $handle = fopen($this->jsonFile, "c");
        if (flock($handle, LOCK_EX)) {
            $this->fileHandle = $handle;
        } else {
            throw new Exception("JsonTable Error: Can't set file-lock");
        }
    }

    /**
     * @throws Exception
     */
    protected function save(): bool {
        if (ftruncate($this->fileHandle, 0) && fwrite($this->fileHandle, json_encode($this->fileData))) {
            return true;
        } else {
            throw new Exception("JsonTable Error: Can't write data to: " . $this->jsonFile);
        }
    }

    public function selectAll(): array {
        return $this->fileData;
    }

    public function select($key, $val = 0): array {
        $result = array();
        if (is_array($key)) {
            $result = $this->select($key[1], $key[2]);
        } else {
            $data = $this->fileData;
            foreach ($data as $_val) {
                if (isset($_val[$key])) {
                    if ($_val[$key] == $val) {
                        foreach ($_val as $key) { // проверка на вложенный массив
                            if (is_array($key)) {
                                return $key;
                            } else {
                                return $_val;
                            }
                        }
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function selectRow($key, $val, $row): string {
        $result = '';
        $data = $this->fileData;
        foreach ($data as $_val) {
            if (isset($_val[$key])) {
                if ($_val[$key] == $val) {
                    $result = $_val[$row];
                }
            }
        }
        return $result;
    }

    public function updateAll($data = array()) {
        if (isset($data[0]) && substr_compare($data[0], $this->jsonFile, 0)) {
            $data = $data[1];
        }
        return $this->fileData = empty($data) ? array() : $data;
    }

    public function update($key, $val = 0, $newData = array()): bool {
        $result = false;
        if (is_array($key)) {
            $result = $this->update($key[1], $key[2], $key[3]);
        } else {
            $data = $this->fileData;
            foreach ($data as $_key => $_val) {
                if (isset($_val[$key])) {
                    if ($_val[$key] == $val) {
                        $data[$_key] = $newData;
                        $result = true;
                        break;
                    }
                }
            }
            if ($result) $this->fileData = $data;
        }
        return $result;
    }

    public function insert($data = array()): bool {
        if (isset($data[0]) && substr_compare($data[0], $this->jsonFile, 0)) {
            $data = $data[1];
        }
        $this->fileData[] = $data;
        return true;
    }

    public function deleteAll(): bool {
        $this->fileData = array();
        return true;
    }

    public function delete($key, $val = 0): int {
        $result = 0;
        if (is_array($key)) {
            $result = $this->delete($key[1], $key[2]);
        } else {
            $data = $this->fileData;
            foreach ($data as $_key => $_val) {
                if (isset($_val[$key])) {
                    if ($_val[$key] == $val) {
                        unset($data[$_key]);
                        $result++;
                    }
                }
            }
            if ($result) {
                sort($data);
                $this->fileData = $data;
            }
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function createTable($tablePath): void {
        if (is_array($tablePath)) {
            $tablePath = $tablePath[0];
        }
        if (file_exists($tablePath)) {
            throw new Exception("Table already exists: " . $tablePath);
        }
        if (!fclose(fopen($tablePath, 'a'))) {
            throw new Exception("New table couldn't be created: " . $tablePath);
        }
    }
}