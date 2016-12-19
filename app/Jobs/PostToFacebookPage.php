<?php

namespace App\Jobs;

use App\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Facebook;
use App\Config;

class PostToFacebookPage implements ShouldQueue{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $post;

    /**
     * Create a new job instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post){
        $this->post = $post;
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
        $post = $this->post;

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

        /**
         * Update lastPostAt
         */
        $lastPostAtConfig = Config::where('key', 'lastPostAt')->first();
        $lastPostAtConfig->value = time();
        $lastPostAtConfig->save();

        $confessionIdConfig->value = $nextConfessionId;
        $confessionIdConfig->save();
    }
}
