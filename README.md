### Weather
基于 [高德开放平台](https://lbs.amap.com/dev/id/newuser) 的 PHP 天气信息组件。

安装
```
$ composer require sulwan/weather -vvv
```
配置
在使用本扩展之前，你需要去 高德开放平台 注册账号，然后创建应用，获取应用的 API Key。

使用
```
use Sulwan\Weather\Weather;

$key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

$weather = new Weather($key);
```
获取实时天气
```
$response = $weather->getWeather('石家庄');
```
示例：
```
{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "lives": [
        {
            "province": "河北",
            "city": "石家庄市",
            "adcode": "130100",
            "weather": "晴",
            "temperature": "12",
            "winddirection": "南",
            "windpower": "6",
            "humidity": "62",
            "reporttime": "2018-11-13 15:00:00"
        }
    ]
}
```
获取近期天气预报
```
$response = $weather->getWeather('石家庄', 'all');
```
示例：
```
{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "forecasts": [
        {
            "city": "石家庄市",
            "adcode": "130100",
            "province": "河北",
            "reporttime": "2018-11-13 11:00:00",
            "casts": [
                {
                    "date": "2018-11-13",
                    "week": "2",
                    "dayweather": "晴",
                    "nightweather": "多云",
                    "daytemp": "15",
                    "nighttemp": "4",
                    "daywind": "南",
                    "nightwind": "北",
                    "daypower": "≤3",
                    "nightpower": "≤3"
                },
                {
                    "date": "2018-11-14",
                    "week": "3",
                    "dayweather": "阴",
                    "nightweather": "小雨",
                    "daytemp": "14",
                    "nighttemp": "5",
                    "daywind": "南",
                    "nightwind": "北",
                    "daypower": "≤3",
                    "nightpower": "≤3"
                },
                {
                    "date": "2018-11-15",
                    "week": "4",
                    "dayweather": "小雨",
                    "nightweather": "雨夹雪",
                    "daytemp": "11",
                    "nighttemp": "3",
                    "daywind": "东北",
                    "nightwind": "北",
                    "daypower": "≤3",
                    "nightpower": "≤3"
                },
                {
                    "date": "2018-11-16",
                    "week": "5",
                    "dayweather": "阴",
                    "nightweather": "阴",
                    "daytemp": "8",
                    "nighttemp": "0",
                    "daywind": "北",
                    "nightwind": "北",
                    "daypower": "≤3",
                    "nightpower": "≤3"
                }
            ]
        }
    ]
}
```
获取 XML 格式返回值
第三个参数为返回值类型，可选 json 与 xml，默认 json：

$response = $weather->getWeather('石家庄', 'all', 'xml');
示例：
```
<?xml version="1.0" encoding="utf-8"?>

<response>
  <status>1</status>
  <count>1</count>
  <info>OK</info>
  <infocode>10000</infocode>
  <forecasts type="list">
    <forecast>
      <city>石家庄市</city>
      <adcode>130100</adcode>
      <province>河北</province>
      <reporttime>2018-11-13 11:00:00</reporttime>
      <casts type="list">
        <cast>
          <date>2018-11-13</date>
          <week>2</week>
          <dayweather>晴</dayweather>
          <nightweather>多云</nightweather>
          <daytemp>15</daytemp>
          <nighttemp>4</nighttemp>
          <daywind>南</daywind>
          <nightwind>北</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-11-14</date>
          <week>3</week>
          <dayweather>阴</dayweather>
          <nightweather>小雨</nightweather>
          <daytemp>14</daytemp>
          <nighttemp>5</nighttemp>
          <daywind>南</daywind>
          <nightwind>北</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-11-15</date>
          <week>4</week>
          <dayweather>小雨</dayweather>
          <nightweather>雨夹雪</nightweather>
          <daytemp>11</daytemp>
          <nighttemp>3</nighttemp>
          <daywind>东北</daywind>
          <nightwind>北</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-11-16</date>
          <week>5</week>
          <dayweather>阴</dayweather>
          <nightweather>阴</nightweather>
          <daytemp>8</daytemp>
          <nighttemp>0</nighttemp>
          <daywind>北</daywind>
          <nightwind>北</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
      </casts>
    </forecast>
  </forecasts>
</response>

```
### 参数说明
```
array | string   getWeather(string $city, string $type = 'base', string $format = 'json')
```
* $city - 城市名，比如：“深圳”；
* $type - 返回内容类型：base: 返回实况天气 / all:返回预报天气；
* $format - 输出的数据格式，默认为 json 格式，当 output 设置为 “xml” 时，输出的为 XML 格式的数据。

在 Laravel 中使用
在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php` 中：
```
    .
    .
    .
     'weather' => [
        'key' => env('WEATHER_API_KEY'),
    ],
```
然后在 `.env` 中配置 `WEATHER_API_KEY` ：
```
WEATHER_API_KEY=xxxxxxxxxxxxxxxxxxxxx
```
可以用两种方式来获取 `Sulwan\Weather\Weather` 实例：

#### 方法参数注入
```
    .
    .
    .
    public function edit(Weather $weather) 
    {
        $response = $weather->getWeather('深圳');
    }
    .
    .
    .
```
#### 服务名访问
```
    .
    .
    .
    public function edit() 
    {
        $response = app('weather')->getWeather('深圳');
    }
    .
    .
    .
```
#### 参考
[高德开放平台天气接口](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)
#### License
MIT