Feature: Return person info by its unique identifier

    Background:
        Given the following persons were created:
            | Nickname  | Name     | Birthday   | Stack                     |
            | fefas     | Felipe   | 1989-03-14 | PHP                       |
            | malukenho | Jeferson | 2001-01-01 | PHP Kotlin C++ Javascript |
            | pmchato   | Marcelo  | 1985-01-01 |                           |
            | devjohn   | John Doe | 1998-05-01 | Java Kotlin               |
            | devjane   | Jane Doe | 1991-06-19 | Jave C#                   |

    Scenario: Person found by provided identifier
        When searching persons by "PHP" is requested
        Then the response status code should be 200
        And the response body should have the following persons:
            | Nickname  | Name     | Birthday   | Stack                     |
            | fefas     | Felipe   | 1989-03-14 | PHP                       |
            | malukenho | Jeferson | 2001-01-01 | PHP Kotlin C++ Javascript |

    Scenario: Person found by provided identifier
        When searching persons by "devj" is requested
        Then the response status code should be 200
        And the response body should have the following persons:
            | Nickname | Name     | Birthday   | Stack        |
            | devjohn   | John Doe | 1998-05-01 | Java Kotlin |
            | devjane   | Jane Doe | 1991-06-19 | Jave C#     |

    Scenario: Person found by provided identifier
        When searching persons by "Doe" is requested
        Then the response status code should be 200
        And the response body should have the following persons:
            | Nickname | Name     | Birthday   | Stack        |
            | devjohn   | John Doe | 1998-05-01 | Java Kotlin |
            | devjane   | Jane Doe | 1991-06-19 | Jave C#     |
