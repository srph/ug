<?php

class ExampleTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJobToBeCalled()
    {
        // https://github.com/laravel/lumen-framework/issues/207
        unset($this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher']);  

        $this->expectsJobs('App\Jobs\GenerateStylesheetArchive');
        $this->post('/');
    }
    
}
