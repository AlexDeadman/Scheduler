<?php

namespace Schedule\Api;

use PDO;
use PDOStatement;

class ApiController {
    private static array $TABLES = array(
        'disciplines' => 'aud_dist_app_discipline',
        'audiences' => 'aud_dist_app_audience',
        'groups' => 'aud_dist_app_group',
        'lecturers' => 'aud_dist_app_lecturer',
        'schedules' => 'aud_dist_app_schedule',
    );

    public function __construct(private PDO $pdo) {}

    public function getAll(string $endpoint): string {
        $table = self::$TABLES[$endpoint];
        $query = $this->pdo->query("SELECT * FROM $table");

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(array('data' => $results));
    }

    public function getById(string $endpoint, int $id): string {
        $table = self::$TABLES[$endpoint];
        $query = $this->pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $query->execute(array($id));

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }

    public function create(string $endpoint, string $body): string {
        $ent = json_decode($body);
        $this->prepare_insert(self::$TABLES[$endpoint], $ent)->execute();

        $ent->id = $this->pdo->lastInsertId();
        return json_encode($ent);
    }

    public function delete(string $endpoint, int $id) {
        $table = self::$TABLES[$endpoint];
        $query = $this->pdo->prepare("DELETE FROM $table WHERE id = ?");
        $query->execute(array($id));
    }

    public function update(string $endpoint, int $id, string $body): string{
        $disc = json_decode($body);
        $this->prepare_update(self::$TABLES[$endpoint], $disc)->execute(array($id));

        $disc->id = $id;
        return json_encode($disc);
    }

    private function generateList(array $data, string $endpoint): string {
        $json = [];
        $json['data'][0]['relationships'][$endpoint]['data'] = $data;
        return json_encode($json);
    }

    private function prepare_update(string $table, mixed $entity): PDOStatement {
        unset($entity->id);

        $sets = [];
        foreach (get_object_vars($entity) as $name => $val) {
            array_push($sets, "$name = '$val'");
        }
        $sets = implode(', ', $sets);

        return $this->pdo->prepare("UPDATE $table SET $sets WHERE id = ?");
    }

    private function prepare_insert(string $table, mixed $entity): PDOStatement {
        unset($entity->id);
        $names = implode(',', array_keys(get_object_vars($entity)));
        $values = implode("','", get_object_vars($entity));

        return $this->pdo->prepare("INSERT INTO $table ($names) VALUES ('$values')");
    }
}

