<?php
$manualPath = 'assets/pdfs/manuales/';
$diplomaPath = 'assets/pdfs/diplomas/';

// Verificar si la carpeta tiene permisos de lectura
if (is_readable($manualPath)) {
    echo "La carpeta de manuales tiene permisos de lectura.<br>";
} else {
    echo "La carpeta de manuales NO tiene permisos de lectura.<br>";
}

if (is_readable($diplomaPath)) {
    echo "La carpeta de diplomas tiene permisos de lectura.<br>";
} else {
    echo "La carpeta de diplomas NO tiene permisos de lectura.<br>";
}
?>
