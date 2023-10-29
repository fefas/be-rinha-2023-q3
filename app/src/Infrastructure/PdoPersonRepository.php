<?php

namespace Fefas\BeRinha2023\App\Infrastructure;

use Fefas\BeRinha2023\App\Application\NicknameAlreadyTaken;
use Fefas\BeRinha2023\App\Application\Person;
use Fefas\BeRinha2023\App\Application\PersonRepository;
use PDO;
use PDOException;

final readonly class PdoPersonRepository implements PersonRepository
{
    private PDO $dbConn;

    public function __construct()
    {
        $this->dbConn = new PDO(
            "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
        );
    }

    /** {@inheritdoc} */
    public function save(Person $person): void
    {
        $this->dbConn->exec('BEGIN TRANSACTION');

        try {
            $this->insertPerson($person);
        } catch (PDOException $ex) {
            $this->dbConn->exec('ROLLBACK');
            if (false !== strpos($ex->getMessage(), 'Unique violation:')) {
                throw new NicknameAlreadyTaken();
            }

            throw $ex;
        }

        try {
            $this->insertPersonStack($person);
        } catch (PDOException $ex) {
            $this->dbConn->exec('ROLLBACK');
            throw $ex;
        }

        $this->dbConn->exec('COMMIT');
    }

    public function get(string $id): ?Person
    {
        $st = $this->dbConn->prepare(<<<SQL
            SELECT *
            FROM person
            WHERE id = :id
            SQL);

        $st->execute(['id' => $id]);
        $result = $st->fetchAll();

        return [] === $result ? null : Person::reconstituteFromDbRow($result[0]);
    }

    /** {@inheritdoc } */
    public function find(string $needle): array
    {
        $st = $this->dbConn->prepare(<<<SQL
            SELECT *
            FROM person
            WHERE id IN (
                SELECT id FROM person WHERE nickname LIKE :needle OR name LIKE :needle
                UNION
                SELECT person_id FROM person_stack WHERE stack LIKE :needle
            )
            SQL);

        $st->execute(['needle' => "%$needle%"]);

        return array_map(
            fn (array $r): Person => Person::reconstituteFromDbRow($r),
            $st->fetchAll(),
        );
    }

    private function insertPerson(Person $person): void
    {
        $st = $this->dbConn->prepare(<<<SQL
            INSERT INTO person (id, nickname, name, birthday, stack)
            VALUES (:id, :nickname, :name, :birthday, :stack)
            SQL);

        $st->execute([
            'id' => $person->id,
            'nickname' => $person->nickname,
            'name' => $person->name,
            'birthday' => $person->birthday->format('Y-m-d'),
            'stack' => json_encode($person->stack),
        ]);
    }

    private function insertPersonStack(Person $person): void
    {
        if (null === $person->stack) {
            return;
        }

        $st = $this->dbConn->prepare(<<<SQL
            INSERT INTO person_stack (person_id, stack)
            VALUES (:personId, :stack)
            SQL);

        foreach ($person->stack as $stack) {
            $st->execute([
                'personId' => $person->id,
                'stack' => $stack,
            ]);
        }
    }
}
