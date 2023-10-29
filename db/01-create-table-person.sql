CREATE TABLE person (
    id UUID NOT NULL,
    nickname VARCHAR(32) NOT NULL,
    name VARCHAR(100) NOT NULL,
    birthday DATE NOT NULL,
    stack JSON,
    PRIMARY KEY (id),
    UNIQUE (nickname)
);
