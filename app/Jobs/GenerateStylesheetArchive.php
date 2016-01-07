<?php

namespace App\Jobs;

use App\Jobs\Job;
use Chumper\Zipper\Zipper;
use Illuminate\Filesystem\Filesystem;

class GenerateStylesheetArchive extends Job
{

    /**
     * List of milligram stylesheets
     *
     * @var array
     *
    protected $stylesheets = [
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
        $temp = public_path("temp/{$filename}.css");

        $this->generate($temp);
        $zip = $this->zip($temp, $filename);

        // Casting this StdClass so it's not used awkwardly
        return (object) [
        	'path' => $zip,
        	'filename' => "{$filename}.zip"
      	];
	}

    /**
     * Compiles the stylesheets
     *
     * @param $temp Path to temp file
     * @return void
     */
    protected function generate($temp)
    {
        // @refactor
        // This code doesn't make any sense.
        // We need to add a validation that at least a single stylesheet is required
        $this->fs->append($temp, '');

        foreach($this->stylesheets as $stylesheet) {
            if ( !array_get($this->inputs, strtolower($stylesheet)) ) {
                continue;
            }

            $file = $this->fs->get(public_path("milligram/{$stylesheet}.css"));
            $this->fs->append($temp, $file);
        }
    }

    /**
     * Zips the generated file
     *
     * @param string $temp Path of the temporary file (generated) to zip
     * @param string $filename Filename of the  (generated) file to zip
     * @return string Path to the generated zip file
     */
	protected function zip($temp, $filename) {
        $path = public_path("download/{$filename}.zip");

		$zipper = new Zipper;
        $zipper->make($path)
        	->add([$temp, public_path('temp/normalize.css')])
        	->close();

        return $path;
	}

}
