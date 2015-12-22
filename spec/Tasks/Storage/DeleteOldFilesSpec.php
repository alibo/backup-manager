<?php

namespace spec\BackupManager\Tasks\Storage;

use BackupManager\Tasks\Storage\DeleteOldFiles;
use DateTime;
use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class DeleteOldFilesSpec
 * @mixin DeleteOldFiles
 */
class DeleteOldFilesSpec extends ObjectBehavior
{

    function it_is_initializable(Filesystem $filesystem, DateTime $olderThan)
    {
        $this->beConstructedWith($filesystem, '/dir', $olderThan);
        $this->shouldHaveType('BackupManager\Tasks\Storage\DeleteOldFiles');
    }

    function it_should_execute_the_delete_old_files_command(Filesystem $filesystem, DateTime $olderThan)
    {

        $filesystem->listWith(['timestamp'], '/dir', false)->willReturn([
            [
                'path' => '/dir/file-new.gz',
                'timestamp' => 2000,
            ],
            [
                'path' => '/dir/file-old.gz',
                'timestamp' => 1000,
            ],
            [
                'path' => '/dir/file-older.gz',
                'timestamp' => 500,
            ],
        ]);

        $olderThan->getTimestamp()->willReturn(1500);

        $filesystem->delete('/dir/file-older.gz')->shouldBeCalled();
        $filesystem->delete('/dir/file-old.gz')->shouldBeCalled();

        $this->beConstructedWith($filesystem, '/dir', $olderThan);

        $this->execute();
    }

}
