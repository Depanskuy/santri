<?php

class Poli {
      public static function all(): array
      public static function findById(int $id): ?array
      public static function findBy[Field](mixed $value): ?array


      public static function create(array $data): int  // return ID baru

      public static function update(int $id, array $data): bool


      public static function delete(int $id): bool


      public static function existsBy[Field](...)
      public static function count(): int
  }

?>