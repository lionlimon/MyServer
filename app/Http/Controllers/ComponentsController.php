<?php

namespace App\Http\Controllers;

use App\Component;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ComponentsController extends BaseController
{

    public function show($projectId) {

        $components = Component::where('project_id', $projectId)->get(); 
        return $this->sendResponse($components);
    }

    public function store(Request $request) {
        $userId = Auth::id();
        $projectId = $request->input('project_id');
        $componentName = $request->input('component_name');
        $categoryName = $request->input('category_name');
        
        

        $validator = Validator::make($request->input(), [
			'component_name' => 'required',
			'project_id' => 'required',
			'category_name' => 'required',
        ]);
        
        if ( $validator->fails() ) 
            $this->sendError('Ошибка валидации', $validator->errors());

        $category = Category::firstOrCreate([
            'project_id' => $projectId, 
            'name' => $categoryName
        ]);
        
        $newComponent = Component::create([
            'name' => $componentName,
            'category_id' => $category->id,
            'project_id' => $projectId,
            'user_id' => $userId
        ]);

        return $this->sendResponse([
            'component' => $newComponent,
            'category' => $category
        ]);
    }

    public function delete($id) {
        $component = Component::findOrFail($id);
        $component->delete();        

        return $this->sendResponse($component);
    }
}
