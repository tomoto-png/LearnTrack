<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required|exists:questions,id',
            'content' => 'required|string|min:5|max:2000',
            'image_url' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => '質問文を入力してください！',
            'content.min' => '質問文は最低5文字入力してください！',
            'content.max' => '質問文は2000文字以内です！',
            'content.string' => '質問文は文字列で入力してください！',
            'image_url.max' => '画像は2MB以内でアップロードしてください！',
            'image_url.image' => '画像形式でアップロードしてください！',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        //前のページに戻るのではなくしっかり入力ページに戻るように指定を代入
        $questionId = $this->input('question_id');
        $redirectUrl = route('answer.create', ['id' => $questionId, 'mode' => 'input']);

        //失敗時のリダイレクト先を強制的に入をしたルーティングで指定する ValidationExceptionはバリデーションに失敗した
        throw (new ValidationException($validator))//エラー情報を含むバリデーション例外を作成 throwを使用して手動で例外を投げて処理を中断し、Laravelに「エラーが起きた」と伝える
            ->redirectTo($redirectUrl);
    }
}
