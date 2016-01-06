<?php

namespace App\Jobs;

use App\Jobs\Job;
use Chumper\Zipper\Zipper;

class GenerateStylesheetArchive extends Job {

	/**
	 * @var array
	 */
	protected $inputs;

	/**
	 * Create a new job instance*
	 *
	 * @param array $inputs Stylesheets to be generated
	 */
	public function __construct(array $inputs)
	{
		$this->inputs = $inputs;
	}

	/**
	 * Execute the job
	 *
	 * @return StdClass
	 */
	public function handle(Zipper $zipper)
	{
		$digits = 5;
        $filename = 'milligram_custom_' . str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        $temp_path = 'temp/' . $filename . '.css';
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
            if ( !array_get($this->inputs, strtolower($stylesheet)) ) {
                continue;
            }

            $file = file_get_contents("milligram/{$stylesheet}.css");
            file_put_contents($temp_path, $file, FILE_APPEND);
        }

        fclose($temp);

        $zipper->make('download/' . $filename . '.zip')->add([$temp_path, 'temp/normalize.css']);
        $zipper->close();

        $path = rtrim(app()->basePath('public/'), '/') . "/download/" . $filename . '.zip';

        // Casting this StdClass so it's not used awkwardly
        return (object) [
        	'path' => $path,
        	'filename' => "{$filename}.zip"
      	];
	}

}