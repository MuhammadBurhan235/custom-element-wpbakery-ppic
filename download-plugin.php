<?php
require_once __DIR__ . '/preview-bootstrap.php';

if ( ! class_exists( 'ZipArchive' ) ) {
    http_response_code( 500 );
    header( 'Content-Type: text/plain; charset=UTF-8' );
    echo 'ZipArchive extension is not available.';
    exit;
}

$plugin_root = realpath( __DIR__ );
$plugin_slug = basename( $plugin_root );
$zip_filename = $plugin_slug . '.zip';
$temp_zip = tempnam( sys_get_temp_dir(), 'ppic-plugin-zip-' );

if ( false === $temp_zip ) {
    http_response_code( 500 );
    header( 'Content-Type: text/plain; charset=UTF-8' );
    echo 'Failed to allocate a temporary ZIP file.';
    exit;
}

$zip = new ZipArchive();
$open_result = $zip->open( $temp_zip, ZipArchive::OVERWRITE | ZipArchive::CREATE );

if ( true !== $open_result ) {
    @unlink( $temp_zip );
    http_response_code( 500 );
    header( 'Content-Type: text/plain; charset=UTF-8' );
    echo 'Failed to create the plugin ZIP archive.';
    exit;
}

$excluded_files = array(
    'preview-all-elements.php',
    'preview-bootstrap.php',
    'preview-ppic-hero.php',
    'download-plugin.php',
    'start-preview.cmd',
);

$excluded_directories = array(
    '.git',
);

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator( $plugin_root, FilesystemIterator::SKIP_DOTS ),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ( $iterator as $item ) {
    $absolute_path = $item->getPathname();
    $relative_path = str_replace( $plugin_root . DIRECTORY_SEPARATOR, '', $absolute_path );
    $relative_path = str_replace( DIRECTORY_SEPARATOR, '/', $relative_path );

    if ( '' === $relative_path ) {
        continue;
    }

    $path_segments = explode( '/', $relative_path );
    if ( array_intersect( $excluded_directories, $path_segments ) ) {
        continue;
    }

    if ( in_array( basename( $relative_path ), $excluded_files, true ) ) {
        continue;
    }

    $archive_path = $plugin_slug . '/' . $relative_path;

    if ( $item->isDir() ) {
        $zip->addEmptyDir( $archive_path );
        continue;
    }

    $zip->addFile( $absolute_path, $archive_path );
}

$zip->close();

header( 'Content-Type: application/zip' );
header( 'Content-Disposition: attachment; filename="' . $zip_filename . '"' );
header( 'Content-Length: ' . filesize( $temp_zip ) );
header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
header( 'Pragma: no-cache' );

readfile( $temp_zip );
@unlink( $temp_zip );
exit;