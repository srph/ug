<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;
 
 
class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function download(Request $request)
    {
        $digits = 5;
        $build_string = 'milligram_custom_' . str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        $temp_path = 'temp/' . $build_string . '.css';
        $temp = fopen($temp_path, 'a');

        $stylesheets = [
            'Base',
            'Blockquote',
            'Button',
            'Code',
            'Form',
            'Grid',
            'Link',
            'List',
            'Misc',
            'Spacing',
            'Table',
            'Typography',
            'Utility'
        ];

        foreach($stylesheets as $stylesheet) {
            if ( !$request->has(strtolower($stylesheet)) ) {
                continue;
            }

            $file = file_get_contents("milligram/{$stylesheet}.css");
            file_put_contents($temp_path, $file, FILE_APPEND);
        }

        fclose($temp);

        $zipper = new Zipper;
        $zipper->make('download/' . $build_string . '.zip')->add([$temp_path, 'temp/normalize.css']);
        $zipper->close();

        $download_path = rtrim(app()->basePath('public/'), '/') . "/download/" . $build_string . '.zip';

        return response()->download($download_path, $build_string . '.zip', [ 'Content-type' => 'application/zip' ]);
    }
}