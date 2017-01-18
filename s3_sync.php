<?php

require('vendor/autoload.php');

class S3_interface {
    function __construct() {
        $this->client = new \Aws\S3\S3Client([
            'region'  => getenv('AWS_REGION'),
            'version' => '2006-03-01',
        ]);
        $this->local_directory = 'tt-rss/feed-icons';
    }

    function upload() {
        $files_to_upload = [];
        foreach (scandir($this->local_directory, SCANDIR_SORT_NONE) as $file) {
            if ($file === "" || $file[0] == '.' || $file == 'index.html')
                continue;
            $files_to_upload[] = $this->local_directory.'/'.$file;
        }
        if (!$files_to_upload)
            return "No files to upload to S3.\n";

        $manager = new \Aws\S3\Transfer($this->client, (new ArrayObject($files_to_upload))->getIterator(), 's3://'.getenv('AWS_S3_BUCKET_NAME'), [
            'base_dir' => 'tt-rss',
            'before' => function (\Aws\Command $command) {
                if (in_array($command->getName(), ['PutObject', 'CreateMultipartUpload'])) {
                    // $command['ACL'] = 'public-read'; // no really needed
                    $command['StorageClass'] = 'REDUCED_REDUNDANCY';
                }
            },
        ]);
        $manager->transfer();

        return "Uploaded ".count($files_to_upload)." files to S3.\n";
    }

    function download() {
        $manager = new \Aws\S3\Transfer($this->client, 's3://'.getenv('AWS_S3_BUCKET_NAME').'/feed-icons', $this->local_directory);
        $manager->transfer();
        return "Downloaded everything from S3.\n";
    }
}

if (!getenv('AWS_S3_BUCKET_NAME')) {
    echo "Sync to S3 disabled. See README.md to enable it.\n";
    exit;
}

$s3 = new S3_interface();
$cmd = $argv[1];
if (in_array($cmd, ['upload', 'download']))
    echo $s3->$cmd();
