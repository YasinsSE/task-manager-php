<?php
// Görev ekleme işlemi için POST kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_title = htmlspecialchars($_POST['task_title']);
    $task_description = htmlspecialchars($_POST['task_description']);
    $column = htmlspecialchars($_POST['column']); // Hangi sütuna ekleneceği bilgisi

    // Yeni görev dizisi
    $new_task = [
        'title' => $task_title,
        'description' => $task_description
    ];

    // Sütun verilerini oturumda saklıyoruz (örnek için)
    session_start();
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [
            'todo' => [],
            'doing' => [],
            'done' => []
        ];
    }

    // Görevi ilgili sütunun en üstüne ekle
    array_unshift($_SESSION['tasks'][$column], $new_task);
    header("Location: dashboard.php"); // Görev eklendikten sonra yönlendir
    exit;
}
?>
