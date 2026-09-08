<?php

namespace Tests\Unit;

use App\Services\CrmService;
use CodeIgniter\Test\CIUnitTestCase;

final class CrmConfigurationTest extends CIUnitTestCase
{
    public function testStatusLifecycleIsCompleteAndOrdered(): void
    {
        $this->assertSame(
            ['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP','DONE'],
            CrmService::STATUSES
        );
    }

    public function testExampleWorkflowIsValid(): void
    {
        $json='[{"action":"SEND_MESSAGE","message":"Halo"},{"action":"CHANGE_STATUS","status":"FOLLOW_UP"}]';
        $steps=json_decode($json,true,512,JSON_THROW_ON_ERROR);
        $this->assertSame('SEND_MESSAGE',$steps[0]['action']);
        $this->assertContains($steps[1]['status'],CrmService::STATUSES);
    }

    public function testCrmRoutesAndWebhookIntegrationExist(): void
    {
        $routes=file_get_contents(APPPATH.'Config/Routes.php');
        $webhook=file_get_contents(APPPATH.'Controllers/Webhook/BalesotomatisWebhook.php');
        $this->assertStringContainsString("crm/api/dashboard/summary",$routes);
        $this->assertStringContainsString('onMessageReceived',$webhook);
    }
}
