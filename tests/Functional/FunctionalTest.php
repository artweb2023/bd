<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    private Client $client;
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost:8080',
            'http_errors' => false,
        ]);
    }
    public function testShowBranchesPage(): void
    {
        $response = $this->client->request('GET', '/index.php');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Филиялы', (string)$response->getBody());
    }
    public function testAddBranchFormSubmission(): void
    {
        $response = $this->client->request('POST', '/index.php', [
            'form_params' => [
                'city' => 'Test City',
                'address' => 'Test Address'
            ]
        ]);
        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();
        if ($statusCode !== 201) {
            echo "Response status code: $statusCode\n";
            echo "Response body: $body\n";
        }
        $this->assertSame(201, $statusCode);
        $responseData = json_decode($body, true);
        $this->branchId = $responseData['branch_id'] ?? null;
        $this->assertNotNull($this->branchId, 'Branch ID должен быть возвращен');
    }   
    public function testShowBranchesPageWithAddBranch(): void
    {
        $this->testAddBranchFormSubmission();
        $response = $this->client->request('GET', '/index.php');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Филиялы', (string)$response->getBody());
        $this->assertStringContainsString('Test City', (string)$response->getBody());
        $this->assertStringContainsString('Test Address', (string)$response->getBody());
    }
    public function testShowBranchPage(): void
    {
        $this->testAddBranchFormSubmission();

        $response = $this->client->request('GET', '/show_branch.php', [
            'query' => ['branch_id' => $this->branchId]
        ]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Филиял', (string)$response->getBody());
        $this->assertStringContainsString('Test City', (string)$response->getBody());
        $this->assertStringContainsString('Test Address', (string)$response->getBody());
    }
    public function testChangeBranchAddress(): void
    {
        $this->testAddBranchFormSubmission();
        $newAddress = 'New Test Address';
        $response = $this->client->request('POST', '/show_branch.php', [
            'query' => ['branch_id' => $this->branchId],
            'form_params' => [
                'address' => $newAddress
            ]
        ]);
        $this->assertSame(200, $response->getStatusCode());
        $response = $this->client->request('GET', '/show_branch.php', [
            'query' => ['branch_id' => $this->branchId]
        ]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Филиял', (string)$response->getBody());
        $this->assertStringContainsString('Test City', (string)$response->getBody());
        $this->assertStringContainsString($newAddress, (string)$response->getBody());
    }
    public function testAddEmployeeForm(): void
    {
        $response = $this->client->request('GET', '/add_employee.php', [
            'query' => ['branch_id' => $this->branchId]
        ]);
        $this->assertSame(200, $response->getStatusCode());
    }
    /*public function testAddEmployeeFormSubmission(): void
    {
        $response = $this->client->request('POST', '/add_employee.php?branch_id=' . $this->branchId, 
        [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen('/test/1.webp', 'r'),
                    'filename' => '1.webp'
                ],
                [
                    'name'     => 'full_name',
                    'contents' => 'John Doe'
                ],
                [
                    'name'     => 'position',
                    'contents' => 'Manager'
                ],
                [
                    'name'     => 'phone_number',
                    'contents' => '1234567890'
                ],
                [   
                    'name'     => 'email',
                    'contents' => 'john.doe@example.com'
                ],
                [
                    'name'     => 'gender',
                    'contents' => 'M'
                ],
                [
                    'name'     => 'date_of_birth',
                    'contents' => '1990-01-01'
                ],
                [
                    'name'     => 'hire_date',
                    'contents' => '2020-01-01'
                ],
                [
                    'name'     => 'comment',
                    'contents' => 'No comments'
                ],
            ]
        ]);
        $this->assertSame(303, $response->getStatusCode(), 'Expected 303 redirect status after adding an employee.');
        $response = $this->client->request('GET', '/show_branch.php', [
            'query' => ['branch_id' => $this->branchId]
        ]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('John Doe', (string)$response->getBody());
        $this->assertStringContainsString('Manager', (string)$response->getBody());
    } */
}