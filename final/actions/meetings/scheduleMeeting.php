<?php

include_once('../../database/meetings.php');
include_once('../../database/common.php');
include_once('../../database/tag.php');
include_once('../../database/files.php');
include_once('../../config/init.php');



if (isset($_POST['title']) && isset($_POST['date']) && isset($_POST['time'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $duration = $_POST['duration'];
    $invited_users = $_POST['invited_users'];
    $total = count($_FILES['file']['name']);

    $meeting_id = scheduleMeeting($title, $description, $date, $time, $duration, $_SESSION['project_id']);
    if(isset($_POST['invited_users']))
        inviteUserToMeeting($meeting_id, $invited_users, $_SESSION['user_id']);

    if (isset($_POST['tagOption'])) {

        $tagOption = htmlspecialchars($_POST['tagOption']);
        $tag = existsTag($tagOption);

        if ($tag != -1)
            $tagId = $tag;
        else {
            $tagId = createTag($tagOption);
            addTagToProject($_SESSION['project_id'], $tagId);
        }

        addTagToMeeting($meeting_id, $tagId);
    }

    for ($i = 0; $i < $total; $i++) {

        if ($_FILES['file']['error'][$i] == 0) {

            if ($_FILES['file']['tmp_name'][$i] != "") {
                $path = $BASE_DIR . 'images/files/';
                $location = $path . $_FILES['file']['name'][$i];

                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $location)) {
                    $_SESSION['success_messages'][] = '<br>' . 'Uploaded File';
                }
                $_SESSION['success_messages'][] = '<br>' . 'Error on uploading File';
            }
            $current_date = date('m/d/Y h:i:s a', time());
            $file = addFile($current_date, $_SESSION['user_id'], $_SESSION['project_id'], $_FILES['file']['name'][$i]);
            addFileToMeeting($file, $meeting_id);

            if (isset($tagId))
                addTagToFile($file, $tagId);
        }
    }



    $_SESSION['success_messages'][] = '<br>' . 'Schedule Meeting successful';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    $_SESSION['error_messages'][] = '<br>' . 'You are not allowed to schedule a meeting without filling the title, date and time fields. You must invite at least one member.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
