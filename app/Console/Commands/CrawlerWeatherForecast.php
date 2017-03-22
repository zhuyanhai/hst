<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerWeatherForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:weather_forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '多线程抓包';

    private $totalPageCount = 1;
    private $counter        = 1;
    private $concurrency    = 1;  // 同时并发抓取

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = callService('foundation.getWeatherForecastV1', [
            'dates'   => date('Y-m-d'),
            'isCheck' => 1,
        ]);

        if ($result['data']['isCrawler'] > 0) {
            echo 'already crawler';
            exit;
        }

        $client = new Client();

        $res = $client->request('GET', 'http://www.dsoaf.gov.cn/list.asp?classid=25');
        $html =  $res->getBody()->getContents();
        $crawler1 = new Crawler();
        $crawler1->addHtmlContent($html, 'gb2312');
        $crawler1 = $crawler1->filter('table')->eq(3)->children()
            ->filter('table')->eq(7)->children()
            ->filter('a')->eq(0);

        //提炼标题
        $aContent = trim($crawler1->text());
        //提炼属性
        $aHref = $crawler1->extract(array('href'))[0];
        $aHref = 'http://www.dsoaf.gov.cn/'.$aHref;

        //待匹配每日时间
        $dateMatchContent = date('Y年n月j日');
        if (preg_match('%^'.$dateMatchContent.'%i', $aContent)) {//匹配上

            $res = $client->request('GET', $aHref);
            $html =  $res->getBody()->getContents();
            $crawler2 = new Crawler();
            $crawler2->addHtmlContent($html, 'gb2312');
            $html = $crawler2->filter('table')->eq(3)->children()
                ->filter('table')->eq(6)->children()
                ->filter('div')->eq(0)->html();

            $htmlArray = explode('<hr>', $html);

            $result = callService('foundation.setWeatherForecastV1', [
                'dates'    => date('Y-m-d'),
                'contents' => $htmlArray,
            ]);

            if ($result['code'] != 0) {
                //todo log
                echo 'fail';
            } else {
                echo 'success';
            }

        } else {
            echo 'no match';
        }

        exit;

    }
}
