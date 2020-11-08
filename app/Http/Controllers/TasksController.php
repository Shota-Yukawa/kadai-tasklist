<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    
    public function welcome()
    {

        // メッセージ一覧ビューでそれを表示
        return view('welcome', [
            
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // Welcomeビューでそれらを表示
        return view('tasks.index', $data);


        // //メッセージ一覧を取得
        // $tasks = Task::all();
        // // メッセージ一覧ビューでそれを表示
        // return view('tasks.index', [
        //     'tasks' => $tasks,
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        //タスクの作成
         // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create ([
            'status' => $request->status,
            'content' => $request->content,
        ]);

        return back();
        
        // $task = new Task;
        // $task->status = $request->status;
        // $task->content = $request->content;
        // $task->save();
        
        // //トップページにリダイレクト
        // return redirect('/');
        
        
        // return view('tasks.index', [
        //     'tasks' => $tasks,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
             //idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);

         if (\Auth::id() === $task->user_id) {
            //メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
         }
         else {
            // 前のURLへリダイレクトさせる
            return back();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            //idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);
        
         if (\Auth::id() === $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
         }
         else {
            // 前のURLへリダイレクトさせる
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
            //idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);
            // メッセージを更新
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
            
             // トップページへリダイレクトさせる
            return redirect('/');

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // 前のURLへリダイレクトさせる
        return redirect('/');
    }
}
