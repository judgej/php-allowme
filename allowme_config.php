<?php

/**
 * TODO: use a more flexible config file package.
 */

return array(
    // Start and end markers.
    // Only "allow from" statements will appear between these markers.
    // The markers must be present once only, and in the correct order.

    'marker_start' => '# ALLOW-LIST-START',
    'marker_end' => '# ALLOW-LIST-END',

    // Password to get yourself added to the list.
    'password' => 'whatever',

    // Location of the htaccess file to update.
    'htaccess' => __DIR__ . '/../.htaccess',

    // Newlines to use when generating the file.
    'newline' => "\n",

    // Header code, if placing markers for the first time.
    'header' => array(
        '# Section added by allowme script ' . date('Y-m-d'),
        'Order Deny,Allow',
        'Deny from all'
    ),

);
