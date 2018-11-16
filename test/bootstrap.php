<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Byte\\Streams\\', __DIR__);

return $loader;
