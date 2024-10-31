<?php

header("Content-Type: application/json");

// Добавляем файл подкючения
include 'db.php';

// Добавляем файл с бронированием билетов
include 'book.php';

// Добавляем файл с просмотром категорий билетов
include 'get_ticket_type.php';


// Добавляем файл с просмотром событий
include 'get_events.php';

// Получение текущего URI
$request_uri = $_SERVER['REQUEST_URI'];

// Определяем метод 
$method = $_SERVER['REQUEST_METHOD'];

// Переменная для подключения к БД
$cn = db_cn();

// Генерация штрихкода
function barcode_gen($length) {
    return substr(str_shuffle(str_repeat("0123456789", $length)), 0, $length);
}

// Генератор id брони
function book_id_gen($length) {
    return substr(str_shuffle(str_repeat("012345", $length)), 0, $length);
}

// Эндпоинт для создания брони
if ($method == 'POST' && strpos($request_uri, '/index.php/book') !== false) {

    book($cn);

}


// Эндпоинт для просмотра категорий билетов
if ($method == 'GET' && strpos($request_uri, '/index.php/get_ticket_type') !== false) {

    get_ticket_type($cn);

}

// Эндпоинт для просмотра событий
if ($method == 'GET' && strpos($request_uri, '/index.php/get_events') !== false) {

    get_events($cn);

}
?>