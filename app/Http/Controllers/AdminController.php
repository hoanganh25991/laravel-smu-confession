<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Facebook;
use App\Config;
use App\UserRole;

class AdminController extends Controller{
    /**
     * @param Request $req
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyPost(Request $req){
        $confessionIdConfig = Config::where('key', 'lastConfessionId')->first();
        $lastConfessionId = $confessionIdConfig->value;
        $nextConfessionId = $lastConfessionId + 1;

        if($req->method() == 'GET'){
            $posts = Post::where('status', 'pending')->orderBy('created_at', 'asc')->paginate(5);

            return view('admins.admin')->with(compact('posts', 'nextConfessionId'));
        }

        if($req->method() == 'POST'){
            $action = $req->get('action');
            $postId = $req->get('postId');
            $postContent = $req->get('postContent');

            $post = Post::find($postId);
            /**
             * Discard
             */
            if($action == 'discard'){
                $post->delete();
                return response(['msg' => "postId {$postId}: deleted"], 200, ['Content-Type' => 'application/json']);
            }

            if($action == 'approve'){
                // Update postContent if admin modify it
                $post->content = $postContent;
                $post->save();
            }

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

            $data = ['message' => "#{$nextConfessionId}\n========\n{$post->content}\n========\nConfess at: http://smuconfess.originally.us"];
            $postUrl = '/'.env('PAGE_ID').'/feed';
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

            /**
             * Log on who approve this post
             */
            $time =  date('Y-m-d H:m:s');
            $adminProviderId = session('providerId');
            $userRole = UserRole::where('provider_id', $adminProviderId)->first();
            /**
             * Log out current admin, bcs we don't store his providerId in the past
             * No way to find out who he is
             */
            if(empty($userRole)){
                session()->flush();
                $msg = "To enable log on admin's activities. We've logged you out.\nPlease relog in. Thank you";
                return response(['msg' => $msg, 'action' => 'reload page'], 422, ['Content-Type' => 'application/json']);
            }
            
            $logFileName = base_path().'/admin-activities.log';
            $recordLog = "[{$time}] {$userRole->name} approved a post, post-id: {$post->id}\n";
            $logFile = fopen($logFileName, 'a');
            fwrite($logFile, $recordLog);
            fclose($logFile);

            return response(['msg' => "Post id: {$graphNode->getField('id')}"], 200, ['Content-Type' => 'application/json']);
        }

    }

    public function addAdmin(Request $req){
        if($req->method() == 'GET'){
            $userRoles = UserRole::where('role', '!=', 'admin')->orWhereNull('role')->get();
            return view('admins.add-admin')->with(compact('userRoles'));
        }

        $userRoleId = $req->get('userRoleId');
        $userRole = UserRole::find($userRoleId);

        $action = $req->get('action');
        if($action == 'discard'){
            $userRole->delete();

            return response(['msg' => "userRole id {$userRoleId}: deleted"], 200,
                ['Content-Type' => 'application/json']);
        }

        $userRole->role = 'admin';
        $userRole->save();

        return response(['msg' => "userRole id {$userRoleId}: added role admin"], 200,
            ['Content-Type' => 'application/json']);
    }
    
    public function postByAdmin(Request $req){
        $postController = new PostController();
        return $postController->post($req);
    }

}
