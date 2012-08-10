<?php

/**
 * Maintain dynamic allow list in .htaccess
 *
 * By visiting this script with the correct password, your
 * IP address will be added to the allow list in the
 * .htaccess file.
 *
 * TODO: ability to lock the file, so two processes do not write to
 * it at the same time.
 * There is an assumption that the htaccess file will not be too long,
 * and so can be easily processes in memory.
 * TODO: Take a backup of the file before writing.
 * TODO: if htaccess does not contain any markers, then place a pair
 * right at the start, with appropriate deny statements. [DONE]
 * FIXME: a blank line is being added to the end of the file, each time an
 * IP address is added. [HACK: remove *any* blank lines from the end]
 */

// TODO: use a package to get the config data, which can include defaults
// and overrides, and perhaps a config directory.
$config = include('allowme_config.php');

$nl = $config['newline'];

// Read teh current htaccess file into three variables: before, within, 
// and after the section to update.

// So we can detect various line endings when reading the htaccess file.
ini_set('auto_detect_line_endings',true);

$content = array(
    'before' => '',
    'marker_start' => $config['marker_start'] . $nl,
    'within' => '',
    'marker_end' => $config['marker_end'] . $nl,
    'after' => ''
);

$state = 'before';

if (!file_exists($config['htaccess'])) die('htaccess file not found');
if (!is_writable($config['htaccess'])) die('htaccess file not writable');

$fd = fopen($config['htaccess'], 'r');
while(!feof($fd)) {
    // Get the line, removing any end-of-line characters it may have.
    // Use a regex to support multiple line-end characters in any order.
    $line = preg_replace('/[\r\n]*$/', '', fgets($fd));

    if (trim($line) == $config['marker_start']) {
        if ($state != 'before') die('Out of order markers');
        $state = 'within';
        continue;
    }

    if (trim($line) == $config['marker_end']) {
        if ($state != 'within') die('Out of order markers');
        $state = 'after';
        continue;
    }

    $content[$state] .= $line . $nl;
    //echo "line=$line; ";
}
fclose($fd);

// Now we should have the file in three sections.
// If we do not, then the markers were missing.

if ($state == 'within') die('Missing or invalid markers');

if ($state == 'before') {
    // We never found the markers, so add them now, right at the
    // start of the file.

    // Move the before section to the end, with a blank line before it.
    $content['after'] = $nl . $content['before'];
    $content['before'] = implode($nl, $config['header']) . $nl;
}

// Get the user's IP.
$ip = $_SERVER['REMOTE_ADDR'];

if (empty($ip) || !preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/', $ip)) die('IP address not known');

// Look to see if the IP address is already there.
if (preg_match('/ '.$ip.'[^0-9]/', $content['within'])) die('IP already listed');

// Still here? Add this IP.

$content['within'] = 'Allow from ' . $ip . $nl . $content['within'];

// Flatten the new file content.
$flattened = array_reduce(
    $content, 
    function(&$result, $item) {return $result . $item;},
    ''
);

// Remove blank lines from the end. This is a sort of hack.
$flattened = preg_replace('/['.$nl.']+$/', $nl, $flattened);

// Now write the whole file.
file_put_contents($config['htaccess'], $flattened, LOCK_EX);

echo "IP added";

//echo "<pre>"; var_dump($flattened); echo "</pre>";
