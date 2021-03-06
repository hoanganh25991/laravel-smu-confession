<?php

namespace App\Http\Controllers;

use App\Jobs\PostToFacebookPage;
use Carbon\Carbon;
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
        /**
         * Instead of go to serve add this default config
         * How could i remember later on?
         * If not set, just run a default
         */
        if(empty($confessionIdConfig)){
            $confessionIdConfig = new Config([
                'key' => 'lastConfessionId',
                'value' => 24691
            ]);
        }
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

            /**
             * Approve
             */
            if($action == 'approve'){
                /**
                 * Update postContent if admin modify it
                 */
                $post->content = $postContent;
                // Bring post to 'queued'
                // Post to page now is on another process
                $post->status = 'queued';
                $post->save();
            }

            $this->logToFile($post);
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

    private function logToFile($post){
        /**
         * Log on who approve this post
         */
        $time = date('Y-m-d H:i:s');
        $adminProviderId = session('providerId');
        $userRole = UserRole::where('provider_id', $adminProviderId)->first();
        /**
         * Log out current admin, bcs we don't store his providerId in the past
         * No way to find out who he is
         */
        if(empty($userRole)){
            session()->flush();
            $msg = "To enable log on admin's activities. We've logged you out.\nPlease relog in. Thank you";
            return response([
                'msg' => $msg,
                'action' => 'reload page'
            ], 422, ['Content-Type' => 'application/json']);
        }

        $logFileName = base_path() . '/admin-activities.log';
        $recordLog = "[{$time}] {$userRole->name} approved a post, post-id: {$post->id}\n";
        $logFile = fopen($logFileName, 'a');
        fwrite($logFile, $recordLog);
        fclose($logFile);

        return response(['msg' => "Post queued"], 200, ['Content-Type' => 'application/json']);
    }

}
