<?php

namespace Core\Console;

use PDO;
use Throwable;

final class Application
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
    }

    /** @param array<int,string> $argv */
    public function run(array $argv): int
    {
        $command = $argv[1] ?? 'aide';
        $arg = $argv[2] ?? null;

        return match ($command) {
            'aide', 'help', '--help', '-h' => $this->help(),
            'installer', 'install' => $this->install(),
            'installer:global', 'global:install' => $this->installGlobal(),
            'nouveau', 'new' => $this->newProject($arg),
            'servir', 'serve' => $this->serve($arg),
            'creer:module', 'make:module' => $this->makeModule($arg),
            'creer:api', 'make:api' => $this->makeApi($arg),
            'creer:component', 'creer:composant', 'make:component' => $this->makeComponent($arg),
            'lister:modules', 'modules:list' => $this->listModules(),
            default => $this->unknown($command),
        };
    }

    private function help(): int
    {
        $this->line('KIVO Framework');
        $this->line('');
        $this->line('Pour démarrer :');
        $this->line('  php kivo nouveau mon_erp');
        $this->line('  cd mon_erp');
        $this->line('  php kivo installer');
        $this->line('  php kivo servir');
        $this->line('');
        $this->line('Pour installer la commande globale :');
        $this->line('  php kivo installer:global');
        $this->line('');
        $this->line('Commandes disponibles :');
        $this->line('  php kivo aide');
        $this->line('  php kivo installer');
        $this->line('  php kivo installer:global');
        $this->line('  php kivo nouveau mon_erp');
        $this->line('  php kivo servir [port]');
        $this->line('  php kivo creer:module Personnel');
        $this->line('  php kivo creer:api Personnel');
        $this->line('  php kivo creer:composant select-search');
        $this->line('  php kivo lister:modules');
        $this->line('');
        $this->line('Alias anglais : help, install, global:install, new, serve, make:module, make:api, make:component.');
        return 0;
    }

    private function install(): int
    {
        $this->line('Installation KIVO');
        $this->line('-----------------');

        $envPath = $this->basePath . '/.env';
        $examplePath = $this->basePath . '/.env.example';

        if (! is_file($envPath)) {
            if (! is_file($examplePath)) {
                $this->writeEnvExample($examplePath);
            }
            copy($examplePath, $envPath);
            $this->line('✓ Fichier .env créé depuis .env.example');
        } else {
            $this->line('✓ Fichier .env déjà présent');
        }

        $env = $this->parseEnv($envPath);
        $env['APP_KEY'] = $env['APP_KEY'] ?: 'base64:' . base64_encode(random_bytes(32));
        $env['APP_NAME'] = $this->ask('Nom du projet', $env['APP_NAME'] ?: 'Kivo ERP');
        $env['APP_COMPANY'] = $this->ask('Nom de l’entreprise', $env['APP_COMPANY'] ?: 'Mon entreprise');
        $env['APP_TEMPLATE'] = $this->ask('Template (enterprise/transit/rh/vide)', $env['APP_TEMPLATE'] ?: 'enterprise');
        $env['APP_URL'] = $this->ask('URL locale', $env['APP_URL'] ?: 'http://localhost:8080');
        $env['DB_HOST'] = $this->ask('Hôte MySQL', $env['DB_HOST'] ?: '127.0.0.1');
        $env['DB_PORT'] = $this->ask('Port MySQL', $env['DB_PORT'] ?: '3306');
        $env['DB_DATABASE'] = $this->ask('Base de données', $env['DB_DATABASE'] ?: $this->slug($env['APP_NAME']));
        $env['DB_USERNAME'] = $this->ask('Utilisateur MySQL', $env['DB_USERNAME'] ?: 'root');
        $env['DB_PASSWORD'] = $this->askHiddenFallback('Mot de passe MySQL', $env['DB_PASSWORD'] ?? '');

        $createAdmin = strtolower($this->ask('Créer un administrateur ? (oui/non)', 'oui'));
        if (in_array($createAdmin, ['oui', 'o', 'yes', 'y', '1'], true)) {
            $env['ADMIN_NAME'] = $this->ask('Nom admin', $env['ADMIN_NAME'] ?: 'Administrateur');
            $env['ADMIN_EMAIL'] = $this->ask('Email admin', $env['ADMIN_EMAIL'] ?: 'admin@demo.local');
            $env['ADMIN_PASSWORD'] = $this->askHiddenFallback('Mot de passe admin', $env['ADMIN_PASSWORD'] ?: 'admin1234');
        }

        $this->writeEnv($envPath, $env);
        $this->writeKivoJson($env);
        $this->line('✓ Configuration enregistrée (.env + kivo.json)');

        $this->ensureStorage();
        $this->line('✓ Dossiers storage prêts');

        $this->tryDatabaseSetup($env);
        $this->line('');
        $this->line('Installation terminée. Lance maintenant :');
        $this->line('  php kivo servir');
        return 0;
    }

    private function installGlobal(): int
    {
        $isWindows = PHP_OS_FAMILY === 'Windows';
        if ($isWindows) {
            $home = getenv('LOCALAPPDATA') ?: ((getenv('USERPROFILE') ?: $this->basePath) . DIRECTORY_SEPARATOR . 'AppData' . DIRECTORY_SEPARATOR . 'Local');
            $dir = rtrim($home, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'Kivo';
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $target = $dir . DIRECTORY_SEPARATOR . 'kivo.bat';
            $root = $this->basePath . DIRECTORY_SEPARATOR . 'kivo';
            $this->writeRaw($target, "@echo off\r\nphp \"{$root}\" %*\r\n");
            $this->line('✓ Lanceur global créé : ' . $target);

            $path = getenv('PATH') ?: '';
            if (! str_contains(strtolower($path), strtolower($dir))) {
                $command = 'setx PATH "' . $path . PATH_SEPARATOR . $dir . '"';
                $this->line('Ajout au PATH utilisateur...');
                @system($command, $code);
                if (($code ?? 1) === 0) {
                    $this->line('✓ PATH utilisateur mis à jour. Ferme puis rouvre PowerShell.');
                } else {
                    $this->line('⚠ Ajout automatique au PATH non confirmé. Ajoute manuellement ce dossier : ' . $dir);
                }
            } else {
                $this->line('✓ Le dossier KIVO est déjà dans le PATH.');
            }

            $this->line('Après réouverture de PowerShell : kivo aide');
            return 0;
        }

        $home = getenv('HOME') ?: $this->basePath;
        $dir = rtrim($home, DIRECTORY_SEPARATOR) . '/.local/bin';
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $target = $dir . '/kivo';
        $root = $this->basePath . '/kivo';
        $this->writeRaw($target, "#!/usr/bin/env sh\nphp \"{$root}\" \"$@\"\n");
        @chmod($target, 0755);
        $this->line('✓ Lanceur global créé : ' . $target);
        $this->line('Assure-toi que ~/.local/bin est dans le PATH, puis lance : kivo aide');
        return 0;
    }

    private function newProject(?string $name): int
    {
        if (! $name) {
            $this->error('Nom requis. Exemple : php kivo nouveau transitpro');
            return 1;
        }
        $target = getcwd() . DIRECTORY_SEPARATOR . $name;
        if (is_dir($target)) {
            $this->error("Le dossier existe déjà : {$target}");
            return 1;
        }
        $this->copyDirectory($this->basePath, $target, ['.git', 'vendor', 'storage/cache', 'storage/logs', '.env', 'kivo.json', $name]);
        $this->line("✓ Projet créé : {$target}");
        $this->line("Suite : cd {$name} puis php kivo installer");
        return 0;
    }

    private function serve(?string $port): int
    {
        $port = $port ?: '8080';
        $public = $this->basePath . DIRECTORY_SEPARATOR . 'public';
        if (! is_dir($public)) {
            $this->error('Dossier public introuvable.');
            return 1;
        }
        $this->line("Serveur KIVO : http://localhost:{$port}");
        $this->line('Arrêt : Ctrl+C');
        passthru(PHP_BINARY . ' -S localhost:' . escapeshellarg($port) . ' -t ' . escapeshellarg($public));
        return 0;
    }

    private function makeModule(?string $name): int
    {
        if (! $name) {
            $this->error('Nom requis. Exemple : php kivo creer:module Personnel');
            return 1;
        }
        $studly = $this->studly($name);
        $lower = strtolower($studly);
        $table = $this->tableName($studly);

        $this->ensureDir('/app/Controllers');
        $this->ensureDir('/app/Repositories');
        $this->ensureDir('/app/Http/Resources');
        $this->ensureDir("/resources/views/{$lower}");
        $this->ensureDir('/database/migrations');

        $this->write("/app/Controllers/{$studly}Controller.php", $this->controllerStub($studly, $lower));
        $this->write("/app/Controllers/{$studly}ApiController.php", $this->apiControllerStub($studly));
        $this->write("/app/Repositories/{$studly}Repository.php", $this->repositoryStub($studly, $table));
        $this->write("/app/Http/Resources/{$studly}Resource.php", $this->resourceStub($studly));
        $this->write("/resources/views/{$lower}/index.ublade.php", $this->viewStub($studly));
        $this->write('/database/migrations/' . date('Y_m_d_His') . "_create_{$table}_table.sql", $this->migrationStub($table));

        $this->line("✓ Module {$studly} généré");
        $this->line("Route API à ajouter : \$router->apiResource('{$table}', App\\Controllers\\{$studly}ApiController::class);");
        return 0;
    }

    private function makeApi(?string $name): int
    {
        if (! $name) {
            $this->error('Nom requis. Exemple : php kivo creer:api Personnel');
            return 1;
        }
        $studly = $this->studly($name);
        $table = $this->tableName($studly);
        $this->ensureDir('/app/Controllers');
        $this->ensureDir('/app/Repositories');
        $this->ensureDir('/app/Http/Resources');
        $this->write("/app/Controllers/{$studly}ApiController.php", $this->apiControllerStub($studly));
        $this->write("/app/Repositories/{$studly}Repository.php", $this->repositoryStub($studly, $table));
        $this->write("/app/Http/Resources/{$studly}Resource.php", $this->resourceStub($studly));
        $this->line("✓ API {$studly} générée");
        return 0;
    }

    private function makeComponent(?string $name): int
    {
        if (! $name) {
            $this->error('Nom requis. Exemple : php kivo creer:composant select-search');
            return 1;
        }
        $file = str_replace('-', '_', strtolower($name));
        $this->ensureDir('/resources/views/components');
        $this->write("/resources/views/components/{$file}.ublade.php", "<div class=\"kivo-card\" data-component=\"{$name}\">\n  <strong>{{ \$title ?? '{$name}' }}</strong>\n</div>\n");
        $this->line("✓ Composant créé : resources/views/components/{$file}.ublade.php");
        return 0;
    }

    private function listModules(): int
    {
        $native = ['rh', 'utilisateurs', 'permissions', 'tickets', 'crm', 'finance', 'documents', 'logistique', 'transit', 'site'];
        foreach ($native as $module) {
            $this->line('- ' . $module);
        }
        return 0;
    }

    private function tryDatabaseSetup(array $env): void
    {
        if (($env['DB_DRIVER'] ?? 'mysql') !== 'mysql') {
            $this->line('ℹ Migrations ignorées : driver non MySQL.');
            return;
        }

        try {
            $pdo = new PDO("mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};charset=utf8mb4", $env['DB_USERNAME'], $env['DB_PASSWORD']);
            $db = preg_replace('/[^a-zA-Z0-9_]/', '', $env['DB_DATABASE']);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$db}`");
            $this->line('✓ Base de données prête : ' . $db);
            $this->runSqlMigrations($pdo);
            $this->seedAdmin($pdo, $env);
        } catch (Throwable $e) {
            $this->line('⚠ Connexion/création BDD impossible : ' . $e->getMessage());
            $this->line('Crée la base dans phpMyAdmin/cPanel puis relance : php kivo installer');
        }
    }

    private function seedAdmin(PDO $pdo, array $env): void
    {
        $email = trim((string) ($env['ADMIN_EMAIL'] ?? ''));
        $password = (string) ($env['ADMIN_PASSWORD'] ?? '');
        if ($email === '' || $password === '') {
            $this->line('ℹ Aucun admin créé : email ou mot de passe absent.');
            return;
        }
        $stmt = $pdo->prepare('INSERT IGNORE INTO users (`name`, `email`, `password`) VALUES (?, ?, ?)');
        $stmt->execute([
            $env['ADMIN_NAME'] ?: 'Administrateur',
            $email,
            password_hash($password, PASSWORD_DEFAULT),
        ]);
        $this->line('✓ Compte admin prêt : ' . $email);
    }

    private function runSqlMigrations(PDO $pdo): void
    {
        $dir = $this->basePath . '/database/migrations';
        if (! is_dir($dir)) {
            return;
        }
        foreach (glob($dir . '/*.sql') ?: [] as $file) {
            $sql = trim((string) file_get_contents($file));
            if ($sql === '') {
                continue;
            }
            $pdo->exec($sql);
            $this->line('✓ Migration : ' . basename($file));
        }
    }

    private function parseEnv(string $path): array
    {
        $defaults = [
            'APP_NAME' => 'Kivo ERP', 'APP_COMPANY' => 'Mon entreprise', 'APP_TEMPLATE' => 'enterprise',
            'APP_ENV' => 'local', 'APP_DEBUG' => 'true', 'APP_KEY' => '', 'APP_URL' => '',
            'DB_DRIVER' => 'mysql', 'DB_HOST' => '127.0.0.1', 'DB_PORT' => '3306', 'DB_DATABASE' => 'kivo', 'DB_USERNAME' => 'root', 'DB_PASSWORD' => '',
            'ADMIN_NAME' => 'Administrateur', 'ADMIN_EMAIL' => 'admin@demo.local', 'ADMIN_PASSWORD' => '',
        ];
        if (! is_file($path)) {
            return $defaults;
        }
        foreach (file($path, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $defaults[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }
        return $defaults;
    }

    private function writeEnvExample(string $path): void
    {
        $this->writeRaw($path, "APP_NAME=Kivo ERP\nAPP_COMPANY=Mon entreprise\nAPP_TEMPLATE=enterprise\nAPP_ENV=local\nAPP_DEBUG=true\nAPP_KEY=\nAPP_URL=http://localhost:8080\n\nDB_DRIVER=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=kivo\nDB_USERNAME=root\nDB_PASSWORD=\n\nADMIN_NAME=Administrateur\nADMIN_EMAIL=admin@demo.local\nADMIN_PASSWORD=\n");
    }

    private function writeEnv(string $path, array $env): void
    {
        $content = "APP_NAME=\"{$env['APP_NAME']}\"\nAPP_COMPANY=\"{$env['APP_COMPANY']}\"\nAPP_TEMPLATE={$env['APP_TEMPLATE']}\nAPP_ENV={$env['APP_ENV']}\nAPP_DEBUG={$env['APP_DEBUG']}\nAPP_KEY={$env['APP_KEY']}\nAPP_URL={$env['APP_URL']}\n\nDB_DRIVER={$env['DB_DRIVER']}\nDB_HOST={$env['DB_HOST']}\nDB_PORT={$env['DB_PORT']}\nDB_DATABASE={$env['DB_DATABASE']}\nDB_USERNAME={$env['DB_USERNAME']}\nDB_PASSWORD={$env['DB_PASSWORD']}\n\nADMIN_NAME=\"{$env['ADMIN_NAME']}\"\nADMIN_EMAIL={$env['ADMIN_EMAIL']}\nADMIN_PASSWORD={$env['ADMIN_PASSWORD']}\n";
        $this->writeRaw($path, $content);
    }

    private function writeKivoJson(array $env): void
    {
        $path = $this->basePath . '/kivo.json';
        if (is_file($path)) {
            return;
        }
        $data = [
            'name' => $env['APP_NAME'],
            'company' => $env['APP_COMPANY'],
            'template' => $env['APP_TEMPLATE'],
            'modules' => ['utilisateurs', 'permissions', 'rh', 'site'],
            'api_mobile' => true,
        ];
        $this->writeRaw($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }

    private function ask(string $label, string $default = ''): string
    {
        $suffix = $default !== '' ? " [{$default}]" : '';
        echo "{$label}{$suffix} : ";
        $value = trim((string) fgets(STDIN));
        return $value !== '' ? $value : $default;
    }

    private function askHiddenFallback(string $label, string $default = ''): string
    {
        return $this->ask($label, $default);
    }

    private function ensureStorage(): void
    {
        $this->ensureDir('/storage/cache/views');
        $this->ensureDir('/storage/logs');
        $this->ensureDir('/storage/uploads');
    }

    private function ensureDir(string $relative): void
    {
        $dir = $this->basePath . $relative;
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    private function write(string $relative, string $content): void
    {
        $path = $this->basePath . $relative;
        if (is_file($path)) {
            $this->line('• Existe déjà : ' . ltrim($relative, '/'));
            return;
        }
        $this->writeRaw($path, $content);
    }

    private function writeRaw(string $path, string $content): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, $content);
    }

    private function studly(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }

    private function slug(string $value): string
    {
        $value = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $value) ?? 'kivo', '_'));
        return $value !== '' ? $value : 'kivo';
    }

    private function tableName(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->studly($name))) . 's';
    }

    private function controllerStub(string $studly, string $lower): string
    {
        return "<?php\n\nnamespace App\\Controllers;\n\nuse Core\\Http\\Controller;\n\nfinal class {$studly}Controller extends Controller\n{\n    public function index(): string\n    {\n        return \$this->view('{$lower}.index', ['title' => '{$studly}']);\n    }\n}\n";
    }

    private function apiControllerStub(string $studly): string
    {
        return "<?php\n\nnamespace App\\Controllers;\n\nuse Core\\Api\\ApiController;\nuse App\\Repositories\\{$studly}Repository;\nuse App\\Http\\Resources\\{$studly}Resource;\n\nfinal class {$studly}ApiController extends ApiController\n{\n    protected string \$repository = {$studly}Repository::class;\n    protected string \$resource = {$studly}Resource::class;\n}\n";
    }

    private function repositoryStub(string $studly, string $table): string
    {
        return "<?php\n\nnamespace App\\Repositories;\n\nuse Core\\Database\\Repository;\n\nfinal class {$studly}Repository extends Repository\n{\n    protected string \$table = '{$table}';\n}\n";
    }

    private function resourceStub(string $studly): string
    {
        return "<?php\n\nnamespace App\\Http\\Resources;\n\nfinal class {$studly}Resource\n{\n    public static function make(array \$row): array\n    {\n        return \$row;\n    }\n}\n";
    }

    private function viewStub(string $studly): string
    {
        return "@extends('layouts.app')\n@section('content')\n<x-page-header title=\"{$studly}\" subtitle=\"Module généré par KIVO Framework.\" />\n<section class=\"kivo-card\">\n  <p>Module {$studly} prêt à personnaliser.</p>\n</section>\n@endsection\n";
    }

    private function migrationStub(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS `{$table}` (\n  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n  `code` VARCHAR(50) NULL,\n  `libelle` VARCHAR(190) NOT NULL,\n  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,\n  PRIMARY KEY (`id`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n";
    }

    private function copyDirectory(string $source, string $target, array $ignore): void
    {
        mkdir($target, 0777, true);
        $items = scandir($source) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $relative = ltrim(str_replace($this->basePath, '', $source . DIRECTORY_SEPARATOR . $item), DIRECTORY_SEPARATOR);
            foreach ($ignore as $ignored) {
                if (str_starts_with($relative, $ignored)) {
                    continue 2;
                }
            }
            $src = $source . DIRECTORY_SEPARATOR . $item;
            $dst = $target . DIRECTORY_SEPARATOR . $item;
            is_dir($src) ? $this->copyDirectory($src, $dst, $ignore) : copy($src, $dst);
        }
    }

    private function unknown(string $command): int
    {
        $this->error('Commande inconnue : ' . $command);
        return $this->help() ?: 1;
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }

    private function error(string $message): void
    {
        fwrite(STDERR, $message . PHP_EOL);
    }
}
