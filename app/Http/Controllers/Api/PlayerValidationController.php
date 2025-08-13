<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class PlayerValidationController extends Controller
{
    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function validate(Request $request)
    {
        $request->validate([
            'game' => 'required|string',
            'uid' => 'required|string',
            'server' => 'nullable|string',
        ]);

        $result = $this->validationService->validatePlayer(
            $request->game,
            $request->uid,
            $request->server
        );

        return response()->json($result);
    }
}