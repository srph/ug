<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\GenerateStylesheetArchive;
 
class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function download(Request $request)
    {
        $inputs = $request->only([
            'base',
            'blockquote',
            'button',
            'code',
            'form',
            'grid',
            'link',
            'list',
            'misc',
            'spacing',
            'table',
            'typography',
            'utility'
        ]);

        $job = new GenerateStylesheetArchive($inputs);
        $file = $this->dispatch($job);

        return response()->download($file->path, $file->filename, [
            'Content-Type' => 'application/zip'
        ]);
    }
}