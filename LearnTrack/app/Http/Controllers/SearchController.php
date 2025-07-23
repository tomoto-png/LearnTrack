<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\CategoryGroup;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $groupId = $request->input('group');
        $categoryId = $request->input('category');
        $keyword = $request->input('keyword');

        $Question = Question::with('category.group');

        // 絞り込み（グループまたはカテゴリ）
        if ($groupId) {
            $Question->whereHas('category.group', function ($query) use ($groupId) {
                $query->where('id', $groupId);
            });
        } elseif ($categoryId) {
            $Question->where('category_id', $categoryId);
        }

        // キーワード検索（選択されたカテゴリ内で）
        if ($keyword) {
            $Question->where(function ($query) use ($keyword) {
                $query->where('content', 'like', "%$keyword%");
            });
        }

        switch ($request->input('sort')) {
            case 'newest':
                $Question->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $Question->orderBy('created_at', 'asc');
                break;
            case 'open':
                $Question->where('is_closed', false)
                    ->orderBy('created_at', 'desc');
                break;
            case 'solved':
                $Question->where('is_closed', true)
                    ->orderBy('created_at', 'desc');
                break;
            case 'fewest_answers':
                $Question->withCount('answers')
                    ->orderBy('answers_count', 'asc');
                break;
            case 'most_answers':
                $Question->withCount('answers')
                    ->orderBy('answers_count', 'desc');
                break;
            case 'least_reward':
                $Question->orderBy('reward', 'asc');
                break;
            case 'most_reward':
                $Question->orderBy('reward', 'desc');
                break;
        }

        $questions = $Question->paginate(5)->withQueryString();;

        $category = Category::with('group')->find($categoryId);
        if ($groupId) {
            $group = CategoryGroup::find($groupId);
        } elseif ($category) {
            $group = $category->group;
        }
        return view('search.index', compact('questions', 'category', 'group'));
    }
    public function category()
    {
        $categoryGroups = CategoryGroup::with('categories')->get();
        return view('search.category', compact('categoryGroups'));
    }
}
