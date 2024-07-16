<?php
declare(strict_types=1);

include "./includes/config.php";
include ROOT_PATH . "includes/autoloader.inc.php";

use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase {
    public function testSetAlertReturnsCorrectAlert() {
        $result = SystemAlert::SetAlert("info", "This is a test.");
        $expected = [
            "alertActive" => true,
            "alertType" => "info",
            "alertMessage" => "This is a test."
        ];
        $this->assertSame($expected, $result);
    }

    public function testSetPermissionAlertReturnsCorrectAlert() {
        $result = SystemAlert::SetPermissionAlert("lists", "view");
        $expected = [
            "alertActive" => true,
            "alertType" => "danger",
            "alertMessage" => "You do not have permission to view lists."
        ];
        $this->assertSame($expected, $result);
    }

    public function testSetAdminAlertReturnsCorrectAlert() {
        $result = SystemAlert::SetAdminAlert();
        $expected = [
            "alertActive" => true,
            "alertType" => "danger",
            "alertMessage" => "You do not have permission to access administration.."
        ];
        $this->assertSame($expected, $result);
    }
}