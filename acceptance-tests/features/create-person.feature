Feature: Create person

    Scenario: Persons successfully created
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | Felipe   | 1989-03-14 | PHP   |
        Then the response status code should be 201

    Scenario: Do not accept if nickname is taken
        Given the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | Felipe   | 1989-03-14 | PHP   |
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | Felipe   | 1989-03-14 | PHP   |
        Then the response status code should be 422

    Scenario: Nickname cannot be null
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            |          | Felipe   | 1989-03-14 | PHP   |
        Then the response status code should be 422

    Scenario: Name cannot be null
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    |          | 1989-03-14 | PHP   |
        Then the response status code should be 422

    Scenario: Name must be string
        When the following person is created:
            | Nickname | Name     | Birthday   | Stack |
            | fefas    | 1        | 1989-03-14 | PHP   |
        Then the response status code should be 400

