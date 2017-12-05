<?php
namespace InfrastructureLayerBundle\Test;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait IntegrationTestTrait
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ContainerInterface */
    private $container;

    private function standardSetUp(Loader $loader)
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        $filePurger = new FilePurger($this->container->getParameter('kernel.project_dir').'/web');
        $filePurger->purge();

        $ORMPurger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $ORMPurger);
        $executor->execute($loader->getFixtures());
    }

    public function tearDown()
    {
        $this->em->close();
        $this->em = null;
        $this->container = null;
        parent::tearDown();
    }
}
