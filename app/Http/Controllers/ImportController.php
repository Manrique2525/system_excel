<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportController extends Controller
{
    // Subir y previsualizar
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
    
        $file = $request->file('file');
    
        // Leer todo el contenido de la primera hoja
        $sheets = Excel::toArray(null, $file);
        if (empty($sheets) || empty($sheets[0])) {
            return response()->json(['message' => 'Hoja vacía'], 422);
        }
    
        $rows = $sheets[0];
    
        // Usamos la primera fila como headers y filtramos los vacíos
        $headersRaw = array_shift($rows);
        $headers = [];
        foreach ($headersRaw as $h) {
            $h = trim((string)$h);
            if ($h !== '') {       // Solo agregar si no está vacío
                $headers[] = $h;
            }
        }
    
        // Convertir cada fila en assoc header => value solo con headers válidos
        $data = [];
        foreach ($rows as $r) {
            $assoc = [];
            foreach ($headers as $i => $h) {
                $assoc[$h] = array_key_exists($i, $r) ? $r[$i] : null;
            }
            $data[] = $assoc;
        }
    
        return response()->json([
            'headers' => $headers,
            'rows' => $data
        ]);
    }
    

    // Generar archivo SQL directamente del Excel
    public function generateSql(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'rows' => 'required|array'
        ]);

        $table = $request->input('table');
        $rows = $request->input('rows');

        if (empty($rows)) {
            return response()->json(['message' => 'No hay filas para exportar'], 422);
        }

        // Obtener todas las columnas del Excel (headers)
        $first = $rows[0];
        $columns = array_keys($first);

        if (empty($columns)) {
            return response()->json(['message' => 'No se encontraron columnas en los datos'], 422);
        }

        $sql = "";
        
        // Generar CREATE TABLE statement
        $sql .= "-- Estructura de tabla generada automáticamente\n";
        $sql .= "CREATE TABLE IF NOT EXISTS `{$table}` (\n";
        
        foreach ($columns as $index => $column) {
            // Limpiar nombre de columna y asegurar que sea válido
            $cleanColumn = preg_replace('/[^a-zA-Z0-9_]/', '_', $column);
            $cleanColumn = trim($cleanColumn, '_');
            if (empty($cleanColumn)) {
                $cleanColumn = "column_" . ($index + 1);
            }
            
            // Determinar tipo de datos básico (TEXT para simplicidad)
            $sql .= "  `{$cleanColumn}` TEXT";
            
            if ($index < count($columns) - 1) {
                $sql .= ",";
            }
            $sql .= "\n";
        }
        
        $sql .= ");\n\n";
        
        // Generar INSERT statements
        foreach ($rows as $r) {
            $vals = [];
            $columnsForInsert = [];
            
            foreach ($columns as $index => $c) {
                // Usar el mismo nombre de columna limpio
                $cleanColumn = preg_replace('/[^a-zA-Z0-9_]/', '_', $c);
                $cleanColumn = trim($cleanColumn, '_');
                if (empty($cleanColumn)) {
                    $cleanColumn = "column_" . ($index + 1);
                }
                
                $columnsForInsert[] = $cleanColumn;
                $v = $r[$c] ?? null;
                $vals[] = is_null($v) || $v === '' ? "NULL" : DB::getPdo()->quote((string)$v);
            }
            
            $colsSql = implode(', ', array_map(fn($c) => "`{$c}`", $columnsForInsert));
            $valsSql = implode(', ', $vals);
            $sql .= "INSERT INTO `{$table}` ({$colsSql}) VALUES ({$valsSql});\n";
        }

        $filename = "{$table}_" . date('Ymd_His') . ".sql";

        return response($sql, 200)
            ->header('Content-Type', 'application/sql')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // Guardar en BD (este método sí necesita que la tabla exista)
    public function saveToDb(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'rows' => 'required|array'
        ]);

        $table = $request->input('table');
        $rows = $request->input('rows');

        if (!Schema::hasTable($table)) {
            return response()->json(['message' => "La tabla {$table} no existe"], 422);
        }

        $allowedColumns = Schema::getColumnListing($table);
        $insert = [];

        foreach ($rows as $r) {
            $row = [];
            foreach ($allowedColumns as $col) {
                if (array_key_exists($col, $r)) {
                    $row[$col] = $r[$col];
                }
            }
            if (!empty($row)) $insert[] = $row;
        }

        if (empty($insert)) {
            return response()->json(['message' => 'No hay datos válidos para insertar'], 422);
        }

        foreach (array_chunk($insert, 500) as $chunk) {
            DB::table($table)->insert($chunk);
        }

        return response()->json(['message' => 'Filas insertadas correctamente']);
    }

    // Exportar tabla completa a SQL
    // Exportar tabla completa a SQL con CREATE TABLE
public function exportSql(Request $request)
{
    $table = $request->query('table');
    if (!$table || !Schema::hasTable($table)) {
        return response()->json(['message' => 'Tabla no encontrada'], 422);
    }

    $columns = Schema::getColumnListing($table);

    // Obtener tipos de columnas
    $columnTypes = [];
    foreach ($columns as $col) {
        $type = DB::getSchemaBuilder()->getColumnType($table, $col); // tipo de columna
        // Mapear tipos de Laravel a tipos SQL básicos
        switch ($type) {
            case 'string':
                $columnTypes[$col] = 'VARCHAR(255)';
                break;
            case 'integer':
                $columnTypes[$col] = 'INT';
                break;
            case 'bigint':
                $columnTypes[$col] = 'BIGINT';
                break;
            case 'boolean':
                $columnTypes[$col] = 'TINYINT(1)';
                break;
            case 'datetime':
                $columnTypes[$col] = 'DATETIME';
                break;
            case 'date':
                $columnTypes[$col] = 'DATE';
                break;
            case 'text':
            default:
                $columnTypes[$col] = 'TEXT';
                break;
        }
    }

    // Generar CREATE TABLE
    $sql = "-- Estructura de tabla generada automáticamente\n";
    $sql .= "CREATE TABLE IF NOT EXISTS `{$table}` (\n";
    foreach ($columns as $i => $col) {
        $sql .= "  `{$col}` {$columnTypes[$col]}";
        if ($i < count($columns) - 1) $sql .= ",";
        $sql .= "\n";
    }
    $sql .= ");\n\n";

    // Generar INSERT statements
    $rows = DB::table($table)->get();
    foreach ($rows as $r) {
        $vals = [];
        foreach ($columns as $c) {
            $v = $r->$c;
            $vals[] = is_null($v) ? "NULL" : DB::getPdo()->quote((string)$v);
        }
        $colsSql = implode(', ', array_map(fn($c) => "`{$c}`", $columns));
        $sql .= "INSERT INTO `{$table}` ({$colsSql}) VALUES (" . implode(', ', $vals) . ");\n";
    }

    $filename = "{$table}_" . date('Ymd_His') . ".sql";
    return response($sql, 200)
        ->header('Content-Type', 'application/sql')
        ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
}

}