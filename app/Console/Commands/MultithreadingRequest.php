<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;

class MultithreadingRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:rtq';

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
        $client = new Client();

        $requests = function () use ($client)
        {
            $uri = 'http://www.dsoaf.gov.cn/list.asp?classid=25';
            yield function() use ($client, $uri)
            {
                return $client->getAsync($uri);
            };
        };

        $pool = new Pool($client, $requests(), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function ($response, $index)
            {

                file_put_contents('/tmp/op', $response->getBody()->getContents().PHP_EOL.PHP_EOL, 8);
                $res = json_decode($response->getBody()->getContents());

                $this->info($res);

                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index)
            {
                $this->error("rejected" );
                $this->error("rejected reason: " . $reason );
                $this->countedAndCheckEnded();
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            $this->counter++;
            return;
        }
        $this->info("请求结束！");
    }
}
