<?php namespace BackupManager\Tasks\Storage;

use DateTime;
use League\Flysystem\Filesystem;
use BackupManager\Tasks\Task;

/**
 * Class DeleteFile
 * @package BackupManager\Tasks\Storage
 */
class DeleteOldFiles implements Task
{

    /** @var Filesystem */
    private $filesystem;
    /**
     * @var string
     */
    private $directoryPath;
    /**
     * @var DateTime
     */
    private $olderThan;


    /**
     * @param Filesystem $filesystem
     * @param $directoryPath
     * @param DateTime $olderThan
     */
    public function __construct(Filesystem $filesystem, $directoryPath, DateTime $olderThan)
    {
        $this->filesystem = $filesystem;
        $this->directoryPath = $directoryPath;
        $this->olderThan = $olderThan;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $files = $this->filesystem->listWith(['timestamp'], $this->directoryPath, false);

        foreach ($files as $file) {
            if ($file['timestamp'] < $this->olderThan->getTimestamp()) {
                $this->filesystem->delete($file['path']);
            }
        }
    }
}
