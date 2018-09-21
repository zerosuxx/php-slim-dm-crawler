<?php

namespace Test\Skeleton\App;

use App\Skeleton\TestSuite\AppSlimTestCase;

class HealthCheckActionTest extends AppSlimTestCase
{

    /**
     * @test
     */
    public function callsHealthCheckPage_Returns200OK()
    {
        $response = $this->runApp('GET', '/healthcheck');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', (string)$response->getBody());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function callsHealthCheckPage_WithBadConnectionData_Returns500()
    {
        $this->disableSlimErrorHandler(false);
        putenv('DB_USER=""');
        $response = $this->runApp('GET', '/healthcheck');
        $this->assertEquals(500, $response->getStatusCode());
    }


}