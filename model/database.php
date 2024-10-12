<?php
class Database
{
    protected $connection = null;
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            $this->connection->set_charset("utf8");

            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }
    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function add($data) //BORRAR O ADAPTAR
    {

        $username = $data['username'];
        $user_email = $data['email'];
        $user_gender = $data['gender'];
        $user_country = $data['country'];
        $user_city = $data['city'];
        $user_age = $data['age'];
        $password = $data['passwords']['pass1'];

        $sql = "INSERT INTO users (username, user_email, user_gender, user_country, user_city, user_age,pass) 
            VALUES ('$username', '$user_email', '$user_gender', '$user_country', '$user_city', '$user_age', '$password')";
    
        // Verificar conexión
        if ($this->connection->connect_error) {
            die(json_encode(["error" => "Conexión fallida: " . $this->connection->connect_error]));
        }

        if ($this->connection->query($sql) === TRUE) {
            return json_encode(["success" => "Nuevo registro creado exitosamente"]);
        } else {
            return json_encode(["error" => "Error: " . $sql . "<br>" . $this->connection->error]);
        }

        $this->connection->close();

    }

}
