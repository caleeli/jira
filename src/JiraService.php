<?php 

namespace Codeflow\Jira;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class JiraService
{
    protected $client;
    protected $baseUrl;
    protected $username;
    protected $apiToken;

    public function __construct()
    {
        $this->baseUrl = getenv('JIRA_BASE_URL');
        $this->username = getenv('JIRA_USERNAME');
        $this->apiToken = getenv('JIRA_API_TOKEN');
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->apiToken),
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getTicketsAssignedToUser($username)
    {
        $project = getenv('JIRA_PROJECT') ?: 'MOON';
        $jql = "assignee=\"$username\" and project = $project and status in ('To Do', 'In Backlog')";

        try {
            $response = $this->client->request('GET', '/rest/api/3/search', [
                'query' => [
                    'jql' => $jql,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Manejar errores aquí
            return ['error' => $e->getMessage()];
        }
    }
}
