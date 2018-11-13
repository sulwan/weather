<?php
/**
 * Created by PhpStorm.
 * User: liujunying
 * Date: 2018/11/12
 * Time: 9:39 PM
 */

namespace Sulwan\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Mockery\Matcher\AnyArgs;
use Sulwan\Weather\Weather;
use PHPUnit\Framework\TestCase;
use Sulwan\Weather\Exceptions\InvalidArgumentException;
use Sulwan\Weather\Exceptions\HttpException;
use GuzzleHttp\Psr7\Response;

class WeatherTest extends TestCase
{

    public function testGetWeather()
    {
        // json
        $response = new Response(200, [], '{"success":true}');

        $client = \Mockery::mock(Client::class);

        $url = "https://restapi.amap.com/v3/weather/weatherInfo";
        $client->allows()->get($url, [
            'query' => [
                'key' => 'mock-key',
                'city' => '石家庄',
                'output' => 'json',
                'extensions' => 'base',
            ]
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $w->getWeather('石家庄', 'base', 'json'));

        // xml
        $response = new Response(200, [], '<hello>content</hello>');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '石家庄',
                'extensions' => 'all',
                'output' => 'xml',
            ],
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('<hello>content</hello>', $w->getWeather('石家庄', 'all', 'xml'));

    }

    public function testGetHttpClient()
    {
        $w = new Weather('mock-key');

        $this->assertInstanceOf(ClientInterface::class, $w->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $w = new Weather('mock-key');

        // 设置参数前，timeout 为 null
        $this->assertNull($w->getHttpClient()->getConfig('timeout'));

        // 设置参数
        $w->setGuzzleOptions(['timeout' => 5000]);

        // 设置参数后，timeout 为 5000
        $this->assertSame(5000, $w->getHttpClient()->getConfig('timeout'));
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->get(new AnyArgs())
            ->andThrow(new \Exception('request timeout'));
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();

        $w->allows()->getHttpClient()->andReturn($client);

        // 接着需要断言调用时会产生异常。
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $w->getWeather('石家庄', 'all', 'xml');

    }

    // 检查 $type 参数
    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid type value(base/all): foo');

        $w->getWeather('石家庄', 'foo','json' );

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    // 检查 $format 参数
    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather("mock-key");

        $this->expectExceptionMessage(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid response format: array');

        $w->getWeather('石家庄', 'base', 'array');

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }
}