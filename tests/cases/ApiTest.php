<?php

use LicenseKeys\Utility\Api;

/**
 * Tests Api class.
 *
 * @author Alejandro Mostajo <info@10quality.com> 
 * @version 1.0.0
 * @package LicenseKeys\Utility
 * @license MIT
 */
class ApiTest extends Api_TestCase
{
    /**
     * Tests exception on activate endpoint.
     * @since 1.0.0
     * @expectedException Exception
     */
    public function testActivateException()
    {
        // Prepare
        $response = Api::activate(
            $this->getSimpleClientMock(),
            function() {},
            function() {}
        );
        // Assert
        $this->assertFail('Closure must return an object instance of LicenseRequest.');
    }
    /**
     * Tests exception on validate endpoint.
     * @since 1.0.0
     * @expectedException Exception
     */
    public function testValidateException()
    {
        // Prepare
        $response = Api::validate(
            $this->getSimpleClientMock(),
            function() {},
            function() {}
        );
        // Assert
        $this->assertFail('Closure must return an object instance of LicenseRequest.');
    }
    /**
     * Tests activate result with no response.
     * @since 1.0.0
     */
    public function testActivateWithNoResponse()
    {
        // Prepare
        $response = Api::activate(
            $this->getClientMock(''),
            function() { return $this->getLicenseRequestMock(); },
            function() {}
        );
        // Assert
        $this->assertNull($response);
    }
    /**
     * Tests activate result with error response.
     * @since 1.0.0
     */
    public function testActivateWithError()
    {
        // Prepare
        $response = Api::activate(
            $this->getClientMock('{"error":true}'),
            function() { return $this->getLicenseRequestMock(); },
            function() {}
        );
        // Assert
        $this->assertInternalType('object', $response);
        $this->assertTrue($response->error);
    }
    /**
     * Tests activate.
     * @since 1.0.0
     */
    public function testActivate()
    {
        // Prepare
        $response = '{"error":false,"data":{"activation_id":1,"expire":897}}';
        $license = '{"settings":[],"request":[],"data":{"activation_id":1,"expire":897}}';
        ob_start();
        $response = Api::activate(
            $this->getClientMock($response),
            function() use(&$license) { return $this->getTouchedLicenseRequestMock($license); },
            function($string) { echo $string; }
        );
        $echoed = ob_get_clean();
        // Assert response
        $this->assertInternalType('object', $response);
        // Assert set closure
        $this->assertInternalType('string', $echoed);
        $this->assertEquals($license, $echoed);
    }
    /**
     * Tests validate result with no response.
     * @since 1.0.0
     */
    public function testValidateWithNoResponse()
    {
        // Prepare
        $valid = Api::validate(
            $this->getClientMock(''),
            function() { return $this->getLicenseRequestMock(); },
            function() {}
        );
        // Assert
        $this->assertInternalType('bool', $valid);
        $this->assertFalse($valid);
    }
    /**
     * Tests validate result error response.
     * @since 1.0.0
     */
    public function testValidateWithError()
    {
        // Prepare
        $valid = Api::validate(
            $this->getClientMock('{"error":true}'),
            function() { return $this->getLicenseRequestMock(); },
            function() {}
        );
        // Assert
        $this->assertInternalType('bool', $valid);
        $this->assertFalse($valid);
    }
    /**
     * Tests validate.
     * @since 1.0.0
     */
    public function testValidate()
    {
        // Prepare
        $response = '{"error":false,"data":{"activation_id":1,"expire":897}}';
        $license = '{"settings":[],"request":[],"data":{"activation_id":1,"expire":897}}';
        ob_start();
        $valid = Api::validate(
            $this->getClientMock($response),
            function() use(&$license) { return $this->getTouchedLicenseRequestMock($license); },
            function($string) { echo $string; }
        );
        $echoed = ob_get_clean();
        // Assert response
        $this->assertInternalType('bool', $valid);
        $this->assertTrue($valid);
        // Assert set closure
        $this->assertInternalType('string', $echoed);
        $this->assertEquals($license, $echoed);
    }
    /**
     * Tests deactivate result with no response.
     * @since 1.0.0
     */
    public function testDeactivateWithNoResponse()
    {
        // Prepare
        $response = Api::deactivate(
            $this->getClientMock(''),
            function() { return $this->getLicenseRequestMock(); },
            function() {}
        );
        // Assert
        $this->assertNull($response);
    }
    /**
     * Tests deactivate.
     * @since 1.0.0
     */
    public function testDeactivate()
    {
        // Prepare
        $response = '{"error":false,"message":"deactivated"}';
        ob_start();
        $response = Api::deactivate(
            $this->getClientMock($response),
            function() { return $this->getLicenseRequestMock(); },
            function($string) { echo $string; }
        );
        $echoed = ob_get_clean();
        // Assert response
        $this->assertInternalType('object', $response);
        // Assert set closure
        $this->assertInternalType('string', $echoed);
        $this->assertEquals('', $echoed);
    }
}