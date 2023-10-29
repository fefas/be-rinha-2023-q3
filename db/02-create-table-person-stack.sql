CREATE TABLE person_stack (
    person_id UUID NOT NULL,
    stack VARCHAR(32) NOT NULL,
    UNIQUE (person_id, stack)
);
