<?php

use Codeflow\Jira\JiraService;

/**
 * Get tickets assigned to a user
 *
 * @param mixed $username The username of the user
 * @return array The tickets assigned to the user
 */
function getTicketsAssignedToUser($username): array
{
    $jira = new JiraService();
    $tickets = $jira->getTicketsAssignedToUser($username);
    $tickets['issues'] = $tickets['issues'] ?? [];
    $tickets['issues'] = array_map(function ($ticket) use ($jira) {
        return [
            'issue_key' => $ticket['key'],
            'issue_summary' => $ticket['fields']['summary'],
            'issue_description' => is_array($ticket['fields']['description'])
                ? $jira->parseJiraDescription($ticket['fields']['description'])
                : '',
            'issue_status' => $ticket['fields']['status']['name'],
        ];
    }, $tickets['issues']);

    return $tickets;
}

function import_jira_function()
{
    $definitions = [];
    // get functions declared in this file
    $functions = get_defined_functions();
    $functions = $functions['user'];
    $functions = array_filter($functions, function ($function) {
        return strpos($function, 'import_') !== 0;
    });
    foreach ($functions as $function) {
        $reflection = new ReflectionFunction($function);
        $filename = $reflection->getFileName();
        $docblock = $reflection->getDocComment();
        if ($filename === __FILE__) {
            $line = $reflection->getStartLine();
            $code = trim(file($filename)[$line - 1], ' {');
            $definitions[] = $docblock;
            $definitions[] = $code;
        }
    }
    return implode("\n", $definitions);
}
