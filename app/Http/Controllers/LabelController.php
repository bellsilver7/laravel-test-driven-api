<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;

class LabelController extends Controller
{
    public function store(LabelRequest $request)
    {
        return Label::create($request->validated());
    }
}
