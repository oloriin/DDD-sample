<?php
namespace InfrastructureLayerBundle\Test;

class FilePurger
{

    /**
     * @var string
     */
    private $webDir;

    public function __construct(string $webDir)
    {
        $this->webDir = $webDir;
    }

    public function purge()
    {
        $this->recurseRmdir($this->webDir.'/images');
    }

    private function recurseRmdir($dir)
    {
        if (is_dir($dir) === false) {
            return false;
        }

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->recurseRmdir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
