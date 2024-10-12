<?php
require_once PROJECT_ROOT_PATH . "/model/database.php";
class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT user_id, username, user_email, user_gender, user_country, user_city, user_age FROM users ORDER BY user_id ");
    }

    public function login($username, $password)
    {

        return $this->select("SELECT * FROM users WHERE username = '$username' AND pass = '$password'");

    }


    public function addEvent($arrayEvent)
    {
        $sql = "INSERT INTO events (userid, dateEvent, title, descriptionbody, priority ) 
    VALUES (?,?,?,?,?)";

        // Preparar la sentencia
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            die(json_encode(["error" => "Error en la preparación de la consulta: " . $this->connection->error]));
        }

        // Vincular los parámetros
        $stmt->bind_param(
            "sssss",
            $arrayEvent[0],
            $arrayEvent[1],
            $arrayEvent[2],
            $arrayEvent[3],
            $arrayEvent[4]

        );


        // Ejecutar la sentencia
        if ($stmt->execute()) {
            echo json_encode(["success" => "Nuevo registro creado exitosamente"]);
            exit;
        } else {
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
            exit;
        }

    }


    public function addDailyEvent($arrayEvent)
    {
        $sql = "INSERT INTO dailyevents (userid, daysOfWeek, startTime, endTime, title, description, priority ) 
        VALUES (?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            die(json_encode(["error" => "Error en la preparación de la consulta: " . $this->connection->error]));
        }

        // Vincular los parámetros
        $stmt->bind_param(
            "sssssss",
            $arrayEvent[0],
            $arrayEvent[1],
            $arrayEvent[2],
            $arrayEvent[3],
            $arrayEvent[4],
            $arrayEvent[5],
            $arrayEvent[6]

        );


        // Ejecutar la sentencia
        if ($stmt->execute()) {
            echo json_encode(["success" => "Nuevo registro creado exitosamente"]);
            exit;
        } else {
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
            exit;
        }
    }

    public function getEvents($id)
    {
        return $this->select("SELECT eventid, title, dateEvent AS start, priority, descriptionbody AS description  FROM events WHERE userid = '$id' ");

    }

    public function getDailyEvents($id)
    {
        return $this->select("SELECT idevent,userid, daysOfWeek, startTime ,endTime , title,description   , priority  FROM dailyevents WHERE userid = '$id' ");

    }

    public function delEvent($id)
    {
        $sql = "DELETE  FROM events WHERE eventid = '$id'";

        if ($this->connection->execute_query($sql)) {
            echo json_encode(["success" => "Borrado exitosamente"]);
            exit;
        } else {
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $this->connection->error]);
            exit;
        }
    }

    public function delDailyEvent($id)
    {
        $sql = "DELETE  FROM dailyevents WHERE idEvent = '$id'";

        if ($this->connection->execute_query($sql)) {
            echo json_encode(["success" => "Borrado exitosamente"]);
            exit;
        } else {
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $this->connection->error]);
            exit;
        }
    }

    // public function success($evid, $usid)
    // {
    //     $sql = "UPDATE users SET success=success+1 WHERE user_id=$usid";
    //     if ($this->connection->query($sql)) {
    //         echo json_encode(["success" => "Consultas ok"]);
    //     } else {
    //         echo json_encode(["error" => "Error al ejecutar la consulta: " . $this->connection->error]);
    //         exit;
    //     }

    //     $sql = "DELETE  FROM events WHERE eventid = $evid";
    //     if ($this->connection->query($sql)) {
    //         echo json_encode(["success" => "Consultas ok"]);
    //     } else {
    //         echo json_encode(["error" => "Error al ejecutar la consulta: " . $this->connection->error]);
    //         exit;
    //     }

    // }


    public function success($evid, $usid)
    {
        $sql1 = "UPDATE users SET success=success+1 WHERE user_id=?";
        $sql2 = "DELETE  FROM events WHERE eventid = ?";

        $this->connection->begin_transaction();
        try {
            // Preparar y ejecutar la primera consulta
            $stmt1 = $this->connection->prepare($sql1);
            if ($stmt1 === false) {
                throw new Exception("Error preparando la consulta: " . $this->connection->error);
            }
            $stmt1->bind_param('i', $usid);
            if (!$stmt1->execute()) {
                throw new Exception("Error ejecutando la consulta UPDATE: " . $stmt1->error);
            }
            $stmt1->close();

            // Preparar y ejecutar la segunda consulta
            $stmt2 = $this->connection->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception("Error preparando la consulta: " . $this->connection->error);
            }
            $stmt2->bind_param('i', $evid);
            if (!$stmt2->execute()) {
                throw new Exception("Error ejecutando la consulta DELETE: " . $stmt2->error);
            }
            $stmt2->close();

            // Confirmar la transacción
            $this->connection->commit();

            echo json_encode(["success" => "Consultas ok"]);
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->connection->rollback();

            echo json_encode(["error" => "Error al ejecutar la consulta: " . $e->getMessage()]);
        }
        exit;


    }

    public function discard($evid, $usid)
    {
        $sql1 = "UPDATE users SET discard=discard+1 WHERE user_id=?";
        $sql2 = "DELETE  FROM events WHERE eventid = ?";

        $this->connection->begin_transaction();
        try {
            // Preparar y ejecutar la primera consulta
            $stmt1 = $this->connection->prepare($sql1);
            if ($stmt1 === false) {
                throw new Exception("Error preparando la consulta: " . $this->connection->error);
            }
            $stmt1->bind_param('i', $usid);
            if (!$stmt1->execute()) {
                throw new Exception("Error ejecutando la consulta UPDATE: " . $stmt1->error);
            }
            $stmt1->close();

            // Preparar y ejecutar la segunda consulta
            $stmt2 = $this->connection->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception("Error preparando la consulta: " . $this->connection->error);
            }
            $stmt2->bind_param('i', $evid);
            if (!$stmt2->execute()) {
                throw new Exception("Error ejecutando la consulta DELETE: " . $stmt2->error);
            }
            $stmt2->close();

            // Confirmar la transacción
            $this->connection->commit();
            echo json_encode(["success" => "Consultas ok"]);
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->connection->rollback();
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $e->getMessage()]);
        }
        exit;

    }


    public function percent($id)
    {
        $sql = "SELECT user_id,
       success,
       discard,
       (success / (success + discard) * 100) AS success_percentage
FROM users
WHERE user_id = '$id' ";
        $percentage = $this->select($sql);
        return $percentage[0];

    }
}