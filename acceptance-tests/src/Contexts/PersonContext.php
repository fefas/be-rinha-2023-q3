<?php

namespace Fefas\BeRinha2023\AcceptanceTests\Contexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Fefas\BeRinha2023\AcceptanceTests\HttpClient;
use PDO;
use PHPUnit\Framework\TestCase;

final class PersonContext implements Context
{
    private array $idsPerNickname = [
        'john' => '06b7c11d-f06e-4668-ab7b-4107dbe3c080', // for 404 scenario
    ];

    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    /** @BeforeScenario */
    public function cleanTable(): void
    {
        $dbConn = new PDO(
            "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
        );

        $dbConn->exec('DELETE FROM person');
    }

    /**
     * @Given the following person is created:
     * @Given the following persons were created:
     */
    public function post(TableNode $persons): void
    {
        foreach ($persons as $person) {
            $nickname = empty($person['Nickname']) ? null : $person['Nickname'];
            $name = empty($person['Name']) ? null : $person['Name'];
            $birthday = $person['Birthday'];
            $stack = explode(' ', $person['Stack']);

            $name = is_numeric($name) ? (int) $name : $name;

            $this->httpClient->postPerson([
                'apelido' => $nickname,
                'nome' => $name,
                'nascimento' => $birthday,
                'stack' => $stack,
            ]);

            $location = $this->httpClient->lastResponseLocationHeader();

            if ('' !== $location) {
                $this->idsPerNickname[$nickname] = substr($location, 9);
            }
        }
    }

    /**
     * @When the person :nickname is requested
     */
    public function get(string $nickname): void
    {
        $this->httpClient->getPerson($this->idsPerNickname[$nickname]);
    }

    /**
     * @Then the response status code should be :expected
     */
    public function assertStatusCode(int $expected): void
    {
        TestCase::assertEquals($expected, $this->httpClient->lastResponseStatusCode());
    }

    /**
     * @Then the response body should be:
     */
    public function assertBody(TableNode $expected): void
    {
        $expected = $expected->getHash()[0];
        $expected = [
            'id' => $this->idsPerNickname[$expected['Nickname']],
            'apelido' => $expected['Nickname'],
            'nome' => $expected['Name'],
            'nascimento' => $expected['Birthday'],
            'stack' => explode(' ', $expected['Stack']),
        ];

        TestCase::assertEquals($expected, $this->httpClient->lastResponseBody());
    }
}
