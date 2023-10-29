<?php

namespace Fefas\BeRinha2023\App\Application;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final readonly class Person
{
    private function __construct(
        public string $id,
        public string $nickname,
        public string $name,
        public DateTimeImmutable $birthday,
        public array $stack,
    ) {
    }

    public static function newFromRequestData(array $data): self
    {
        $nickname = $data['apelido'] ?? null;
        $name = $data['nome'] ?? null;
        $birthday = $data['nascimento'];
        $stack = $data['stack'];

        if (null === $nickname || null === $name) {
            throw new ArgumentCannotBeNull();
        }

        if (!is_string($name)) {
            throw new InvalidArgumentType();
        }

        return new self(
            id: Uuid::uuid6()->toString(),
            nickname: $nickname,
            name: $name,
            birthday: new DateTimeImmutable($birthday),
            stack: $stack,
        );
    }

    public static function reconstituteFromDbRow(array $data): self
    {
        return new self(
            id: $data['id'],
            nickname: $data['nickname'],
            name: $data['name'],
            birthday: new DateTimeImmutable($data['birthday']),
            stack: json_decode($data['stack'], true),
        );
    }

    public function toResponseData(): array
    {
        return [
            'id' => $this->id,
            'apelido' => $this->nickname,
            'nome' => $this->name,
            'nascimento' => $this->birthday->format('Y-m-d'),
            'stack' => $this->stack,
        ];
    }
}
