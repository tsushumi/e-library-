<?php

namespace App\Http\Controllers\Administrator\Library;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\CreatesAlerts;
use App\Http\Controllers\Controller;
use App\Actions\Store\StoreCategoryAction;
use App\Actions\Update\UpdateCategoryAction;
use App\Http\Requests\Store\StoreCategoryRequest;
use App\Http\Requests\Update\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use CreatesAlerts;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('perPage') ?: 5;

        return Inertia::render('Administrator/Library/Categories', [
            'filters' => request()->only(['search', 'perPage']),
            'categories' => Category::categoryQuery()
                ->paginate($perPage)
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'title' => $category->title,
                    'slug' => $category->slug,
                    'created_at' => Carbon::parse($category->created_at)->diffForHumans(),
                    'books_count' => $category->books_count
                ])
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request, StoreCategoryAction $action)
    {
        $action->execute($request);
        $this->createAlert('Success', 'New Category created successfully', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action)
    {
        $action->execute($request, $category);
        $this->createAlert('Success', 'Updated category successfully', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        $this->createAlert('Success', 'Category has been deleted', 'error');
    }
}
