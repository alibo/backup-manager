<?php namespace BackupManager\Procedures;

use BackupManager\Tasks;
use DateTime;
use League\Flysystem\Plugin\ListWith;

/**
 * Class DeleteOldFilesProcedure
 * @package BackupManager\Procedures
 */
class DeleteOldFilesProcedure extends Procedure
{

    /**
     * @param string $storageType
     * @param string $directoryPath
     * @param DateTime $olderThan
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     */
    public function run($storageType, $directoryPath, DateTime $olderThan)
    {
        $sequence = new Sequence;

        $fileSystem = $this->filesystems->get($storageType);
        $fileSystem->addPlugin(new ListWith());

        // delete old files which are not modified after specified time
        $sequence->add(new Tasks\Storage\DeleteOldFiles(
            $fileSystem,
            $directoryPath,
            $olderThan
        ));

        $sequence->execute();
    }
}
