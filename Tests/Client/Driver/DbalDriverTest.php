<?php

namespace Enqueue\Tests\Client\Driver;

use Enqueue\Client\Driver\DbalDriver;
use Enqueue\Client\Driver\GenericDriver;
use Enqueue\Client\DriverInterface;
use Enqueue\Client\RouteCollection;
use Enqueue\Dbal\DbalContext;
use Enqueue\Dbal\DbalDestination;
use Enqueue\Dbal\DbalMessage;
use Enqueue\Dbal\DbalProducer;
use Enqueue\Test\ClassExtensionTrait;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProducer;
use Interop\Queue\PsrQueue;
use Interop\Queue\PsrTopic;

class DbalDriverTest extends \PHPUnit_Framework_TestCase
{
    use ClassExtensionTrait;
    use GenericDriverTestsTrait;

    public function testShouldImplementsDriverInterface()
    {
        $this->assertClassImplements(DriverInterface::class, DbalDriver::class);
    }

    public function testShouldBeSubClassOfGenericDriver()
    {
        $this->assertClassExtends(GenericDriver::class, DbalDriver::class);
    }

    public function testShouldSetupBroker()
    {
        $context = $this->createContextMock();
        $context
            ->expects($this->once())
            ->method('getTableName')
        ;
        $context
            ->expects($this->once())
            ->method('createDataBaseTable')
        ;

        $driver = new DbalDriver(
            $context,
            $this->createDummyConfig(),
            new RouteCollection([])
        );

        $driver->setupBroker();
    }

    protected function createDriver(...$args): DriverInterface
    {
        return new DbalDriver(...$args);
    }

    /**
     * @return DbalContext
     */
    protected function createContextMock(): PsrContext
    {
        return $this->createMock(DbalContext::class);
    }

    /**
     * @return DbalProducer
     */
    protected function createProducerMock(): PsrProducer
    {
        return $this->createMock(DbalProducer::class);
    }

    /**
     * @return DbalDestination
     */
    protected function createQueue(string $name): PsrQueue
    {
        return new DbalDestination(new \SplFileInfo($name));
    }

    /**
     * @return DbalDestination
     */
    protected function createTopic(string $name): PsrTopic
    {
        return new DbalDestination(new \SplFileInfo($name));
    }

    /**
     * @return DbalMessage
     */
    protected function createMessage(): PsrMessage
    {
        return new DbalMessage();
    }
}
