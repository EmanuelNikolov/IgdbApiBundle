<?php

namespace EN\IgdbApiBundle\Tests\Functional;


use EN\IgdbApiBundle\ENIgdbApiBundle;
use EN\IgdbApiBundle\Igdb\IgdbWrapperInterface;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
//use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

class DependencyInjectionTest extends TestCase
{

    protected function tearDown()
    {
        $fileSys = new Filesystem();
        $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';

        if ($fileSys->exists($cacheDir)) {
            $fileSys->remove($cacheDir);
        }
    }

    public function testServiceWiringWithConfig()
    {
//        $dotEnv = new Dotenv();
//        $dotEnv->load(__DIR__ . '/../../.env');

        $kernel = new TestKernel([
          'base_url' => getenv('BASE_URL'),
          'api_key' => getenv('API_KEY'),
        ]);
        $kernel->boot();
        $container = $kernel->getContainer();

        $wrapper = $container->get('en_igdb_api.wrapper');
        $this->assertInstanceOf(IgdbWrapperInterface::class, $wrapper);

        $builder = $container->get('en_igdb_api.parameter.builder');
        $this->assertInstanceOf(ParameterBuilderInterface::class, $builder);
    }
}

class TestKernel extends Kernel
{

    private $wrapperConfig;

    public function __construct(array $wrapperConfig = [])
    {
        $this->wrapperConfig = $wrapperConfig;
        parent::__construct('test', true);
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return iterable|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles()
    {
        return [
          new ENIgdbApiBundle(),
        ];
    }

    /**
     * Loads the container configuration.
     *
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('en_igdb_api', $this->wrapperConfig);
        });
    }

    public function getCacheDir()
    {
        return __DIR__
          . DIRECTORY_SEPARATOR
          . 'cache'
          . DIRECTORY_SEPARATOR
          . spl_object_hash($this);
    }
}