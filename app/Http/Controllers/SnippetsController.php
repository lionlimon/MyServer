<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Snippet;
use Validator;

class SnippetsController extends BaseController
{
    public function show($componentId) 
    {
        $snippets = Snippet::where('component_id', $componentId)->get();
        return $this->sendResponse($snippets);
    }

    public function store(Request $request) 
    {
        $validator = Validator::make($request->input(), [
            'type' => 'required',
            'component_id' => 'required',
        ]);

        if ($validator->fails())
            $this->sendError('Ошибка валидации', $validator->errors());

        $newSnippet = Snippet::create(array_merge(
            ['user_id' => Auth::id()], 
            $request->input()
        ));

        return $this->sendResponse($newSnippet);
    }

    public function update(Request $request, $snippetId) 
	{
		$userId = Auth::id();
		$snippet = Snippet::findOrFail($snippetId);
        
        $snippet->update($request->all());

		return $this->sendResponse($snippet);
	}
}
