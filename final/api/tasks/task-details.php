<?php
include_once('../../config/init.php');
include_once('../../database/tasks.php');

if (isset($_POST['taskId'])) {

    $taskId = htmlspecialchars($_POST['taskId']);

    $taskDetails = null;
    $taskTags = null;
    $taskAssignedName = null;
    $projectMembers = null;
    $taskComments = null;
    $projectTags = null;

    $taskDetails = getTaskDetails($taskId);
    $taskTags = getTagFromTaskId($taskId);
    $taskAssignedName = getTaskAssignedName($taskId);
    $projectMembers = getProjectMembersNames($_SESSION['project_id']);
    $taskComments = getTaskComments($taskId);
    $projectTags = getTagsFromProject();

    print json_encode([$taskDetails, $taskTags, $taskAssignedName, $projectMembers, $taskComments, $projectTags]);
}

