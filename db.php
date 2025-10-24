<?php
function db(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    $pdo = new PDO(
      'mysql:host=127.0.0.1;dbname=jobs_db;charset=utf8mb4',
      'root','',[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]
    );
  }
  return $pdo;
}
