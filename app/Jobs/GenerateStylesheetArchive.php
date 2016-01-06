<?php

namespace App\Jobs;

use App\Jobs\Job;
use Chumper\Zipper\Zipper;
use Illuminate\Filesystem\Filesystem;

class GenerateStylesheetArchive extends Job {

	/**
	 * @var array
	 */
	protected $inputs;

	/**
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $fs;

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
	public function handle(Filesystem $fs)
	{
		$this->fs = $fs;

        $filename = 'milligram_custom_' . str_random(6);
        $path = 'temp/' . $filename . '.css';

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

            $file = $fs->get("milligram/{$stylesheet}.css");
            $fs->append($path, $file, FILE_APPEND);
        }

        $zip = $this->zip($path, $filename);

        // Casting this StdClass so it's not used awkwardly
        return (object) [
        	'path' => $zip,
        	'filename' => "{$filename}.zip"
      	];
	}

    /**
     * Zips the generated file
     *
     * @param string $path Path of the (generated) file to zip
     * @param string $path Filename of the  (generated) file to zip
     * @return string Path to the generated zip file
     */
	protected function zip($path, $filename) {
		$zipper = new Zipper;
        $zipper->make("download/{$filename}.zip")
        	->add([$path, 'temp/normalize.css'])
        	->close();

        return app()->basePath("public/download/{$filename}.zip");
	}

}