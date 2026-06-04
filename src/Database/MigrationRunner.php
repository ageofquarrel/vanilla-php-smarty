<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class MigrationRunner
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $migrationsPath,
    ) {
    }

    public function migrate(): int
    {
        $this->ensureMigrationsTable();

        $files = $this->migrationFiles();
        $executed = $this->executedMigrations();
        $batch = $this->nextBatch();
        $applied = 0;

        foreach ($files as $file) {
            $name = basename($file);

            if (in_array($name, $executed, true)) {
                continue;
            }

            $migration = $this->loadMigration($file);
            $migration->up($this->pdo);
            $this->recordMigration($name, $batch);
            $applied++;
        }

        return $applied;
    }

    public function rollback(int $steps = 1): void
    {
        $this->ensureMigrationsTable();

        $stmt = $this->pdo->query(
            'SELECT migration FROM migrations ORDER BY id DESC LIMIT ' . (int) $steps
        );
        $names = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($names as $name) {
            $file = $this->migrationsPath . '/' . $name;
            $migration = $this->loadMigration($file);
            $migration->down($this->pdo);

            $delete = $this->pdo->prepare('DELETE FROM migrations WHERE migration = ?');
            $delete->execute([$name]);
        }
    }

    private function ensureMigrationsTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT UNSIGNED NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    /** @return list<string> */
    private function migrationFiles(): array
    {
        $files = glob($this->migrationsPath . '/*.php') ?: [];
        sort($files);

        return $files;
    }

    /** @return list<string> */
    private function executedMigrations(): array
    {
        if (!$this->tableExists('migrations')) {
            return [];
        }

        $stmt = $this->pdo->query('SELECT migration FROM migrations ORDER BY id');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function nextBatch(): int
    {
        $stmt = $this->pdo->query('SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations');

        return (int) $stmt->fetchColumn();
    }

    private function recordMigration(string $name, int $batch): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO migrations (migration, batch) VALUES (?, ?)');
        $stmt->execute([$name, $batch]);
    }

    private function loadMigration(string $file): Migration
    {
        $migration = require $file;

        if (!$migration instanceof Migration) {
            throw new \RuntimeException("Migration must implement Migration interface: {$file}");
        }

        return $migration;
    }

    private function tableExists(string $table): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?'
        );
        $stmt->execute([$table]);

        return (bool) $stmt->fetchColumn();
    }
}
