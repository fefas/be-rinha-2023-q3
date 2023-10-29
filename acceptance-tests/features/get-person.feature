Feature: Return person info by its unique identifier

    Background:
        Given the following persons were created:
            | Nickname  | Name     | Birthday   | Stack                     |
            | fefas     | Felipe   | 1989-03-14 | PHP                       |
            | malukenho | Jeferson | 2001-01-01 | PHP Kotlin C++ Javascript |

    Scenario: No person found by provided identifier
        When the person "john" is requested
        Then the response status code should be 404

    Scenario: Person found by provided identifier
        When the person "fefas" is requested
        Then the response status code should be 200
        And the response body should be:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | Felipe   | 1989-03-14 | PHP   |
