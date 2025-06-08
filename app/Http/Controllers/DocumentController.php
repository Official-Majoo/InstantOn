<?php

namespace App\Http\Controllers;

use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Serve a document securely
     */
    public function show($id)
    {
        $document = VerificationDocument::findOrFail($id);
        
        // Check if the current user is authorized to view this document
        if (Auth::id() !== $document->customerProfile->user_id && 
            !Auth::user()->isBankOfficer() && 
            !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized to view this document');
        }
        
        // Check if file exists
        if (!Storage::disk('secure')->exists($document->file_path)) {
            abort(404, 'Document file not found');
        }
        
        // Get file content
        $fileContents = Storage::disk('secure')->get($document->file_path);
        
        // Return file with appropriate headers
        return response($fileContents)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . basename($document->file_path) . '"');
    }

    public function view($id)
    {
        $document = VerificationDocument::findOrFail($id);
        
        // Check if user has permission to view this document
        // Add your authorization logic here
        // if (! auth()->user()->can('view', $document)) {
        //     abort(403);
        // }
        
        // Check if file exists
        if (!Storage::disk('secure')->exists($document->file_path)) {
            abort(404, 'Document not found');
        }
        
        // Get file content
        $fileContent = Storage::disk('secure')->get($document->file_path);
        
        // Return appropriate response based on file type
        return response($fileContent)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . basename($document->file_path) . '"');
    }
}