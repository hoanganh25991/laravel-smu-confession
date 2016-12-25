<?php

namespace App\Jobs;

use App\Config;
use Carbon\Carbon;
use App\Post;
use Facebook;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostToFacebookPageSelfLoop implements ShouldQueue{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(){
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        /**
         * Approve
         */
        $fb = new Facebook\Facebook([
            'app_id' => '1282309335173913',
            'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
            'default_graph_version' => 'v2.8'
        ]);

        $pageAccessToken = env('PAGE_ACCESS_TOKEN');
        $fb->setDefaultAccessToken($pageAccessToken);

        $confessionIdConfig = Config::where('key', 'lastConfessionId')->first();
        $lastConfessionId = $confessionIdConfig->value;
        $nextConfessionId = $lastConfessionId + 1;
        $post = Post::where('status', 'queued')->orderBy('created_at', 'asc')->first();

        if(empty($post)){
            $this->selfLoop();
            return;
        }

        $data =
            ['message' => "#{$nextConfessionId}\n========\n{$post->content}\n========\nConfess at: http://smuconfess.originally.us"];
        $postUrl = '/' . env('PAGE_ID') . '/feed';
        try{
            /**
             * Case post with image
             * Save to photo
             */
            if(!empty($post->photo_path)){
                $data['source'] = $fb->fileToUpload(public_path($post->photo_path));
                $postUrl = '/me/photos';
            }
            $res = $fb->post($postUrl, $data);
            $graphNode = $res->getGraphNode();

            /**
             * Log success post to capture does queue actually work
             */
            $time =  date('Y-m-d H:m:s');
            $logFileName = base_path().'/post-queue-success.log';
            $recordLog = "[{$time}] Post to page success, post-id: {$post->id}, graphNode-id: {$graphNode->getField('id')}\n";
            $logFile = fopen($logFileName, 'a');
            fwrite($logFile, $recordLog);
            fclose($logFile);
        }catch(Facebook\Exceptions\FacebookSDKException $e){
            return $e->getMessage();
            exit;
        }

        /**
         * Post to page success
         * 1. Change post status
         * 2. Update lastConfessionId
         */
        $post->status = 'approved';
        $post->save();

        $confessionIdConfig->value = $nextConfessionId;
        $confessionIdConfig->save();

        $this->selfLoop();

    }

    private function selfLoop(){
        /**
         * Self Loop determine continue run base on config
         */
        $postFacebookPageLoopConfig = Config::where('key', 'postFacebookPageLoop')->first();
        if(empty($postFacebookPageLoopConfig)){
            $postFacebookPageLoopConfig = new Config([
                'key' => 'postFacebookPageLoop',
                'value' => 'run'
            ]);
            $postFacebookPageLoopConfig->save();
        }
        $postFacebookPageLoopStatus = $postFacebookPageLoopConfig->value;
        /**
         * Self dispatch a new one
         */
        $carbonTime = Carbon::createFromTimestamp(time());
        $delayInMinutes = env('DELAY_POST_MINUTES', 30);
        if($postFacebookPageLoopStatus == 'run'){
            dispatch((new PostToFacebookPageSelfLoop())->delay($carbonTime->addMinutes($delayInMinutes)));
        }
    }
}
