<?php

namespace App\Http\Controllers;

use App\Services\FonnteService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function sendMessage()
    {
        $target = '089688355159'; 
        $message = 'Test message from Laraset!';  
        $countryCode = '62';  

        $response = $this->fonnteService->sendMessage($target, $message, $countryCode);

        return response()->json(['response' => $response]);
    }
}
