<?php

/**
 * This script uploads build artifacts to the server for further processing.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
function main() {
    exec('git rev-parse HEAD', $output, $exitCode);
    if (0 !== $exitCode) {
        echo 'Could not determine the sha of the HEAD revision.'.PHP_EOL;

        exit(1);
    }

    // Gather Artifacts.
    $artifacts = new ArtifactList();
    $artifacts->add('php-code-coverage', __DIR__.'/clover', function($content) {
        return str_replace('<file name="'.__DIR__, '<file name="', $content);
    });

    // Upload Files.
    $uploader = new Uploader('http://jmsyst.com/travis-build-artifact', trim(end($output)));
    try {
        foreach ($artifacts as $type => $content) {
            $uploader->upload($content, $type);
        }
    } catch (\Exception $ex) {
        echo $ex->getMessage().PHP_EOL;

        exit(1);
    }

    echo 'Build artifacts were uploaded.'.PHP_EOL;
    exit(0);
}

class Uploader
{
    private $endpoint;
    private $sha;

    public function __construct($endpoint, $sha)
    {
        $this->endpoint = $endpoint;
        $this->sha = $sha;
    }

    public function upload($content, $type)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode(array('sha' => $this->sha, 'type' => $type, 'content' => base64_encode($content))),
                'timeout' => 60,
                'ignore_errors' => true,
                'follow_location' => false,
            ),
        ));

        $response = json_decode($rawResponse = file_get_contents($this->endpoint, false, $context), true);
        if (false === $response || ! isset($response['status'])) {
            throw new RuntimeException(sprintf('Could not upload "%s". An unknown error occurred. Response: %s', $type, $rawResponse));
        }

        if ('ok' !== $response['status']) {
            throw new RuntimeException(sprintf('Could not upload "%s": %s', $type, $response['message']));
        }
    }
}

class ArtifactList implements IteratorAggregate
{
    private $artifacts = array();

    public function add($type, $file, $filterCallable = null)
    {
        if ( ! is_file($file)) {
            echo sprintf('Could not add artifact of type "%s" because the file "%s" does not exist.', $type, $file).PHP_EOL;

            return;
        }

        $content = file_get_contents($file);
        if (null !== $filterCallable) {
            $content = $filterCallable($content);
        }

        $this->artifacts[$type] = $content;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->artifacts);
    }
}

main();
