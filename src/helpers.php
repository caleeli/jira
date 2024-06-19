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
    return $jira->getTicketsAssignedToUser($username);
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

echo import_jira_function();
