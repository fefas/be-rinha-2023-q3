<?php

namespace Fefas\BeRinha2023\App\Infrastructure;

use Fefas\BeRinha2023\App\Application\NicknameAlreadyTaken;
use Fefas\BeRinha2023\App\Application\Person;
use Fefas\BeRinha2023\App\Application\PersonRepository;
use PDO;
use PDOException;
use function PHPUnit\Framework\stringContains;

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
    public function save(Person $createPerson): void
    {
        $st = $this->dbConn->prepare(<<<SQL
            INSERT INTO person (id, nickname, name, birthday, stack)
            VALUES (:id, :nickname, :name, :birthday, :stack)
            SQL);

        try {
            $st->execute([
                'id' => $createPerson->id,
                'nickname' => $createPerson->nickname,
                'name' => $createPerson->name,
                'birthday' => $createPerson->birthday->format('Y-m-d'),
                'stack' => json_encode($createPerson->stack),
            ]);
        } catch (PDOException $ex) {
            if (false !== strpos($ex->getMessage(), 'Unique violation:')) {
                throw new NicknameAlreadyTaken();
            }
        }
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
}
