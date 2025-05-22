<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\QuestionRequest;

class QuestionController extends Controller
{
    public function index()
    {
        $questionDatas = Question::all();
        return view('question.index', compact('questionDatas'));
    }
    public function create()
    {
        $user = Auth::user();
        return view('question.create', compact('user'), ['mode' => 'input']);
    }
    public function store(QuestionRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        if ($request->input('remove_image') === 'true') {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));//フォルダ内に保存された画像を消去
                session()->forget('temp_image_path');//セッション内の画像を消去する
            }
            $validated['image_url'] = null;
        } elseif ($request->hasFile('image_url')) {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));
            }

            $tempPath = $request->file('image_url')->store('temp', 'public');
            session(['temp_image_path' => $tempPath]); //セッションに確認用保存
            $validated['image_url'] = $tempPath; //フォルダ内に保存した画像を確認用データに再代入
        } elseif (session('temp_image_path')) {
            // 再投稿時など画像を変更しなければセッションを再利用
            $validated['image_url'] = session('temp_image_path');
        }

        $mode = $request->input('mode');

        if ($mode === 'confirm') {
            return view('question.create', [
                'mode' => 'confirm',
                'input' => $validated,
                'user' => $user
            ]);
        } elseif ($mode === 'edit') {
            return view('question.create', [
                'mode' => 'input',
                'input' => $validated,
                'user' => $user
            ]);
        } elseif ($mode === 'post') {
            $imagePath = null;
            if (session('temp_image_path')) {
                $tempPath = session('temp_image_path');
                $newPath = 'images/' . basename($tempPath);//basename() はファイル名だけを取り出す
                //Storage はファイル操作 move(ファイル名が変えられる) は ストレージ内のファイルを別の場所に移動
                //第一引数：移動元のファイルパス（相対パス） 第二引数：移動先のファイルパス（相対パス）
                Storage::disk('public')->move($tempPath, $newPath);
                $imagePath = $newPath;

                //一時保存のデータを削除
                Storage::disk('public')->delete(session('temp_image_path'));
                session()->forget('temp_image_path');
            }
            Question::create([
                'user_id' => $user->id,
                'content' => $request->content,
                'image_url' => $imagePath,
                'auto_repost_enabled' => $request->has('auto_repost_enabled'),
                'reward' => $request->reward
            ]);
        }
        return redirect()->route('question.index');
    }
    public function cancel() {
        if (session('temp_image_path')) {
            Storage::disk('public')->delete(session('temp_image_path'));
            session()->forget('temp_image_path');
        }
        return redirect()->route('question.index');
    }
    public function show($id)
    {
        $questionData = Question::find($id);
        return view('question.show', compact('questionData'));
    }
}
