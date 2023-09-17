<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Helper {

    public static function document(string $type = "", array $attributes= [], string $id = "", array $meta = [] ): JsonResponse
    {
        $document=[];
        if(!empty($id)){
            $document["data"]["id"] = $id;
        }
        if(!empty($type)){
            $document["data"]["type"] = $type;
        }
        if(!empty($attributes)){
            $document["data"]["attributes"] = $attributes;
        }       
        if(!empty($meta)){
            $document["data"]["meta"] = $meta;
        }
        return response()->json($document);
    }
}