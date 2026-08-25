<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiProducts;
use Illuminate\Support\Facades\Mail;
use App\Mail\PDFMail;

class AiPromptController extends Controller
{
    public function saveProductPdf(Request $request)
    {
        $productId = $request->id;

        $request->validate([
            'productname' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf'
        ]);

        $pdf = $request->file('document');

        // Check existing product
        $obj = AiProducts::where('id', $productId)->first();

        $fileName = time() . '_' . $pdf->getClientOriginalName();
        $pdf->move(public_path('upload/aiproducts'), $fileName);

        $absoluteFilePath = public_path('upload/aiproducts/' . $fileName);

        if ($obj) {

            $obj->productname = $request->productname;
            $obj->document = json_encode(['pdf' => $absoluteFilePath]);
            $obj->updated_at = now();
            $obj->save();

        } else {

            $obj = new AiProducts();
            $obj->productname = $request->productname;
            $obj->document = json_encode(['pdf' => $absoluteFilePath]);
            $obj->created_at = now();
            $obj->updated_at = now();
            $obj->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'PDF uploaded successfully',
            'data' => $obj
        ]);
    }
}