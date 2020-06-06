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
     * websocket
     * @var null
     */
    protected $server = NULL;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->server = new \swoole_websocket_server("0.0.0.0", 9503);

        // 心跳检测
        //        $this->server->set(array(
        //            'heartbeat_check_interval' => 5,  // 上面的设置就是每5秒侦测一次心跳
        //            'heartbeat_idle_time' => 60,  // 一个TCP连接如果在10秒内未向服务器端发送数据，将会被切断。
        //        ));

        $this->server->on('open', [$this, 'open']);   //监听WebSocket连接打开事件
        $this->server->on('message', [$this, 'message']); // 监听WebSocket消息事件
        $this->server->on('close', [$this, 'close']);  // 监听WebSocket连接关闭事件

        $this->server->start();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        if($this->argument('action') == 'start'){
//
//        }

        //
//        $ws = new \swoole_websocket_server("0.0.0.0", 9503);
//
//        //监听WebSocket连接打开事件
//        $ws->on('open', function ($ws, $request) {
//            var_dump($request->fd, $request->get, $request->server);
//            $ws->push($request->fd, "hello, welcome\n");
//        });
//
//        //监听WebSocket消息事件
//        $ws->on('message', function ($ws, $frame) {
//            echo "Message: {$frame->data}\n";
//            $ws->push($frame->fd, "server: {$frame->data}");
//        });
//
//        //监听WebSocket连接关闭事件
//        $ws->on('close', function ($ws, $fd) {
//            echo "client-{$fd} is closed\n";
//            $ws->push($fd, "client-{$fd} is closed!");
//        });
//        $ws->start();

        $this->info("success action [{$this->argument('action')}]");
    }

    /**
     * 监听WebSocket连接打开事件
     * @param $server
     * @param $request
     */
    public function open($server, $request){
        echo "connection open: {$request->fd}\n";
        $server->push($request->fd, "hello, welcome\n");
    }

    /**
     * 监听WebSocket消息事件
     */
    public function message($server, $frame){
        echo "Message: {$frame->data}\n";
        $server->push($frame->fd, "server: {$frame->data}");
    }

    /**
     * 监听WebSocket连接关闭事件
     * @param $server
     * @param $fd
     */
    public function close($server, $fd){
        echo "client-{$fd} is closed\n";
        $server->push($fd, "client-{$fd} is closed");
    }

}
