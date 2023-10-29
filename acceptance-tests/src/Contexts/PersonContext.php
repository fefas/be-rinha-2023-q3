<?php

namespace Fefas\BeRinha2023\AcceptanceTests\Contexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Fefas\BeRinha2023\AcceptanceTests\HttpClient;
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

    /**
     * @Given the following person is created:
     * @Given the following persons were created:
     */
    public function post(TableNode $persons): void
    {
        foreach ($persons as $person) {
            $nickname = empty($person['Nickname']) ? null : $person['Nickname'];
            $name = $person['Name'];
            $birthday = $person['Birthday'];
            $stack = explode(' ', $person['Stack']);

            $id = $this->httpClient->postPerson([
                'apelido' => $nickname,
                'nome' => $name,
                'nascimento' => $birthday,
                'stack' => $stack,
            ]);

            $this->idsPerNickname[$nickname] = $id;
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
}
