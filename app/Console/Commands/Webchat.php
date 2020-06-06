<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Webchat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webchat {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $ws = new \swoole_websocket_server("0.0.0.0", 9503);

        //监听WebSocket连接打开事件
        $ws->on('open', function ($ws, $request) {
            var_dump($request->fd, $request->get, $request->server);
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件
        $ws->on('message', function ($ws, $frame) {
            echo "Message: {$frame->data}\n";
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
            $ws->push($fd, "client-{$fd} is closed!");
        });
        $ws->start();

        $this->info("success action [{$this->argument('action')}]");
    }
}
