<?php

class HomeControllerTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testResponseToBeDownloadWithContentTypeOfZip()
    {
        $response = $this->call('POST', '/');
        $content = $response->headers->get('content-disposition');
        $this->assertRegExp('/attachment/', $content);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJobToBeCalled()
    {
        // // https://github.com/laravel/lumen-framework/issues/207
        $dispatcher = $this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher'];
        unset($this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher']);

        $this->expectsJobs('App\Jobs\GenerateStylesheetArchive');
        $this->post('/');

        // // Undo `unset`
        $this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher'] = $dispatcher;
    }

}
