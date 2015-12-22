<?php namespace BackupManager\Procedures;

use BackupManager\Tasks;
use DateTime;

/**
 * Class DeleteOldFilesProcedure
 * @package BackupManager\Procedures
 */
class DeleteOldFilesProcedure extends Procedure
{

    /**
     * @param string $sourceType
     * @param string $directoryPath
     * @param DateTime $olderThan
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     */
    public function run($sourceType, $directoryPath, DateTime $olderThan)
    {
        $sequence = new Sequence;

        // delete old files which are not modified after specified time
        $sequence->add(new Tasks\Storage\DeleteOldFiles(
            $this->filesystems->get($sourceType),
            $directoryPath,
            $olderThan
        ));

        $sequence->execute();
    }
}
