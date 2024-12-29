<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_title = htmlspecialchars($_POST['task_title']);
    $task_description = htmlspecialchars($_POST['task_description']);
    $column = htmlspecialchars($_POST['column']);


    $new_task = [
        'title' => $task_title,
        'description' => $task_description
    ];


    session_start();
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [
            'todo' => [],
            'doing' => [],
            'done' => []
        ];
    }

    array_unshift($_SESSION['tasks'][$column], $new_task);
    header("Location: dashboard.php");
    exit;
}
?>
