<?php

namespace Bloatless\Endocore\Tests\Unit\Logger;

use Bloatless\Endocore\Components\Logger\LoggerException;
use Bloatless\Endocore\Components\Logger\FileLogger;
use Bloatless\Endocore\Components\Logger\LogLevel;
use PHPUnit\Framework\TestCase;

class FileLoggerTest extends TestCase
{
    /** @var array $config */
    public $config;

    public function setUp(): void
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = $configData['logger'];
    }

    public function testInitWithValidLogPath()
    {
        $logger = new FileLogger($this->config);
        $this->assertInstanceOf(FileLogger::class, $logger);
    }

    public function testWithoutLogPath()
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $config = $configData['logger'];
        unset($config['path_logs']);
        $this->expectException(LoggerException::class);
        $logger = new FileLogger($config);
    }

    public function testInitWithInvalidLogPath()
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $config = $configData['logger'];
        $config['path_logs'] = 'foo';
        $this->expectException(LoggerException::class);
        $logger = new FileLogger($config);
    }

    public function testLogfileIsOpened()
    {
        $pathToLogfile = $this->providePathToLogfile();
        $logger = new FileLogger($this->config);
        $logger->log(LogLevel::DEBUG, 'foobar');
        $this->assertFileExists($pathToLogfile);
        unlink($pathToLogfile);
    }

    public function testLogsAtAllLevels()
    {
        $logger = new FileLogger($this->config);
        $pathToLogfile = $this->providePathToLogfile();
        $messages = $this->provideLevelsAndMessages();
        foreach ($messages as $level => $message) {
            $logger->{$level}($message);
        }
        $logfileContent = file_get_contents($pathToLogfile);
        foreach ($messages as $level => $message) {
            $expected = sprintf('%s: %s', ucfirst($level), $message);
            $this->assertTrue(strpos($logfileContent, $expected) !== false);
        }
        unlink($pathToLogfile);
    }

    public function testLogsWithoutContext()
    {
        $logger = new FileLogger($this->config);
        $pathToLogfile = $this->providePathToLogfile();
        $logger->log(LogLevel::ERROR, 'foobar');
        $logfileContent = file_get_contents($pathToLogfile);
        $this->assertFalse(strpos($logfileContent, '--- Context ---') !== false);
        $this->assertTrue(strpos($logfileContent, 'foobar') !== false);
        unlink($pathToLogfile);
    }

    public function testLogsWithContext()
    {
        $logger = new FileLogger($this->config);
        $pathToLogfile = $this->providePathToLogfile();
        $logger->log(LogLevel::ERROR, 'foobar', ['from' => 'unit test']);
        $logfileContent = file_get_contents($pathToLogfile);
        $this->assertTrue(strpos($logfileContent, '--- Context ---') !== false);
        $this->assertTrue(strpos($logfileContent, '[from] => unit test') !== false);
        unlink($pathToLogfile);
    }

    public function testDoesNotLogInvalidLevel()
    {
        $logger = new FileLogger($this->config);
        $this->expectException(\InvalidArgumentException::class);
        $logger->log('foo', 'bar');
    }

    public function testGetLevels()
    {
        $logger = new FileLogger($this->config);
        $levels = $logger->getLevels();
        $this->assertTrue(is_array($levels));
        $this->assertTrue(count($levels) === 8);
    }

    public function testGetLevelCode()
    {
        $logger = new FileLogger($this->config);
        $this->assertEquals(0, $logger->getLevelCode(LogLevel::DEBUG));
        $this->assertEquals(7, $logger->getLevelCode(LogLevel::EMERGENCY));
        $this->expectException(\InvalidArgumentException::class);
        $logger->getLevelCode('foo');
    }

    public function testSetGetMinLogLevel()
    {
        $logger = new FileLogger($this->config);
        $logger->setMinLevel(LogLevel::NOTICE);
        $this->assertEquals(LogLevel::NOTICE, $logger->getMinLevel());
        $this->expectException(\InvalidArgumentException::class);
        $logger->setMinLevel('foo');
    }

    public function testRespectsMinLevel()
    {
        $pathToLogfile = $this->providePathToLogfile();
        $logger = new FileLogger($this->config);
        $logger->setMinLevel(LogLevel::WARNING);
        $logger->debug('foobar');
        $this->assertFileNotExists($pathToLogfile);
        $logger->emergency('barfoo');
        $this->assertFileExists($pathToLogfile);
        unlink($pathToLogfile);
    }

    public function testLevelIsValid()
    {
        $logger = new FileLogger($this->config);
        $this->assertTrue($logger->levelIsValid(LogLevel::WARNING));
        $this->assertFalse($logger->levelIsValid('invalid level'));
    }

    private function provideLevelsAndMessages(): array
    {
        return [
            LogLevel::EMERGENCY => 'Test message of level emergency.',
            LogLevel::ALERT => 'Test message of level alert.',
            LogLevel::CRITICAL => 'Test message of level critical.',
            LogLevel::ERROR => 'Test message of level error.',
            LogLevel::WARNING => 'Test message of level warning.',
            LogLevel::NOTICE => 'Test message of level notice.',
            LogLevel::INFO => 'Test message of level info.',
            LogLevel::DEBUG => 'Test message of level debug.',
        ];
    }

    private function providePathToLogfile(): string
    {
        $pathToLogfile = $this->config['path_logs'];
        $pathToLogfile = rtrim($pathToLogfile, '/') . '/';
        $pathToLogfile .= date('Y-m-d') . '_endocore.log';
        return $pathToLogfile;
    }
}
