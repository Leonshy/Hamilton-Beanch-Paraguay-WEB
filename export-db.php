<?php
/**
 * Exporta datos de SQLite local a SQL compatible con MySQL.
 * Uso: php export-db.php > db-export.sql
 */

$sqlitePath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $sqlitePath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Orden respetando foreign keys
$tables = [
    'site_settings',
    'media',
    'categories',
    'products',
    'product_media',
    'faqs',
    'pages',
    'banners',
    'announcements',
];

function escapeValue($v): string {
    if ($v === null) return 'NULL';
    // Escapar caracteres especiales para MySQL
    $v = str_replace('\\', '\\\\', (string)$v);
    $v = str_replace("'",  "\\'",  $v);
    $v = str_replace("\n", '\\n',  $v);
    $v = str_replace("\r", '\\r',  $v);
    return "'" . $v . "'";
}

$out = [];
$out[] = "SET NAMES utf8mb4;";
$out[] = "SET CHARACTER SET utf8mb4;";
$out[] = "SET FOREIGN_KEY_CHECKS = 0;";
$out[] = "";

foreach ($tables as $table) {
    $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

    $out[] = "-- ──────────────────────────────";
    $out[] = "-- {$table}";
    $out[] = "-- ──────────────────────────────";
    $out[] = "TRUNCATE TABLE `{$table}`;";

    if (!empty($rows)) {
        $cols = '`' . implode('`, `', array_keys($rows[0])) . '`';
        foreach ($rows as $row) {
            $vals = implode(', ', array_map('escapeValue', array_values($row)));
            $out[] = "INSERT INTO `{$table}` ({$cols}) VALUES ({$vals});";
        }
    }
    $out[] = "";
}

$out[] = "SET FOREIGN_KEY_CHECKS = 1;";

echo implode("\n", $out) . "\n";
