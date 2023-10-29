Feature: Return person info by its unique identifier

    Background:
        Given the following persons were created:
            | Nickname  | Name     | Birthday   | Stack                     |
            | fefas     | Felipe   | 1989-03-14 | PHP                       |
            | malukenho | Jeferson | 2001-01-01 | PHP Kotlin C++ Javascript |

    Scenario: No person found by provided identifier
        When the person "john" is requested
        Then the person should not be found
