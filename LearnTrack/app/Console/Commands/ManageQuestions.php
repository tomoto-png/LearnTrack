<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:manage';//コマンド名（artisanから実行する用）

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '質問の削除と再投稿管理';//作成したコマンドの説明

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //受付終了に変更
        $closedCount = Question::where('created_at', '<', now()->subDays(7))
            ->has('answers')
            ->where('is_closed', false)
            ->update(['is_closed' => true]);

        //削除再投稿指定してない投稿を削除
        $oldQuestions = Question::where('created_at', '<', now()->subDays(7))
            ->where('auto_repost_enabled', false)
            ->where('is_closed', false)
            ->get();

        $deletedCount = 0;

        foreach ($oldQuestions as $question) {
            if ($question->image_url) {
                $url = $question->image_url;
                $path = Str::after($url, config('filesystems.disks.s3.url') . '/');
                Storage::disk('s3')->delete($path);
            }
            $user = $question->user;
            if ($user && $question->reward !== null) {
                $user->update([
                    'count' => $user->count + $question->reward,
                ]);
            }

            $question->delete();
            $deletedCount++;
        }

        //指定した投稿を再投稿
        $questions = Question::where('created_at', '<', now()->subDays(7))
            ->where('auto_repost_enabled', true)
            ->where('is_closed', false)
            ->get();

        $reposted = 0;
        foreach ($questions as $question) {
            Question::create([
                'user_id' => $question->user_id,
                'content' => $question->content,
                'image_url' => $question->image_url,
                'reward' => $question->reward,
                'auto_repost_enabled' => $question->auto_repost_enabled,
                'created_at' => now(),
            ]);
            $question->delete();
            $reposted++;
        }

        $this->info("削除：{$deletedCount}件 / 再投稿：{$reposted}件 / 受付終了：{$closedCount}件 を処理しました。");
    }
}
