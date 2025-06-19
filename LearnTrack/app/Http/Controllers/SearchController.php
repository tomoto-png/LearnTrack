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

        $query = Question::with('category.group');

        // 絞り込み（グループまたはカテゴリ）
        if ($groupId) {
            $query->whereHas('category.group', function ($q) use ($groupId) {
                $q->where('id', $groupId);
            });
        } elseif ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // キーワード検索（選択されたカテゴリ内で）
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('content', 'like', "%$keyword%");
            });
        }

        switch ($request->input('sort')) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'open':
                $query->where('is_closed', false);
                break;
            case 'solved':
                $query->where('is_closed', true);
                break;
            case 'fewest_answers':
                $query->withCount('answers')->orderBy('answers_count', 'asc');
                break;
            case 'most_answers':
                $query->withCount('answers')->orderBy('answers_count', 'desc');
                break;
            case 'least_reward':
                $query->orderBy('reward', 'asc');
                break;
            case 'most_reward':
                $query->orderBy('reward', 'desc');
                break;
        }

        $questions = $query->latest()->paginate(5)->withQueryString();;

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
