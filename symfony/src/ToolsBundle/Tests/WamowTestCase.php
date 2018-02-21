<?php

namespace ToolsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\StringInput;
use Doctrine\ORM\EntityManager;

 /**
 * @property \Symfony\Bundle\FrameworkBundle\Client client
 */
class WamowTestCase extends WebTestCase
{

    protected static $staticClient;

    protected static $application;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    public static function setUpBeforeClass()
    {
        self::$staticClient = static::createClient(['environment' => 'test']);
        // kernel boot, so we can get the container and use our services
        self::bootKernel();
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
                            ->get($id);
    }

    protected function setUp()
    {
        $this->container     = self::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    protected function resetDb()
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load --no-interaction');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    protected function tearDown()
    {
        // purposefully not calling parent class, which shuts down the kernel
    }

    public function doLogin($username, $password)
    {
        $crawler = $this->client->request('GET', '/en/login');

        $form = $crawler
            ->selectButton('_submit')
            ->form([
                       '_username'  => $username,
                       '_password'  => $password
                   ]
            );
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();
    }

    public function performClientRequest(
        $method,
        $urlPath,
        $rawRequestBody = null,
        $username = null,
        $password = null
    ) {

        $this->client = static::createClient([], []);

        $token = null;
        if ($username != null)
            $this->doLogin($username, $password);

        $this->client = static::createClient([]);
        $this->client->request(
            $method,
            $urlPath,
            [],
            [],
            [],
            $rawRequestBody
        );

        return $this->client->getResponse();
    }
}