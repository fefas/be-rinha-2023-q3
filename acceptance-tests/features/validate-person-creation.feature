Feature: Validate provided person data correctly on creation

    Scenario: Nickname cannot be null
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            |          | Felipe   | 1989-03-14 | PHP   |
        Then the response status code should be 422

    Scenario: Name must be string
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | 1        | 1989-03-14 | PHP   |
        Then the response status code should be 400

