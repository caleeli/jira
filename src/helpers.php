<?php

use Codeflow\Jira\JiraService;

function getTicketsAssignedToUser($username)
{
    $jira = new JiraService();
    $tickets = $jira->getTicketsAssignedToUser($username);
    
    return $tickets;
}
