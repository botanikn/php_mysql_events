<?php

header("Content-Type: application/json");

// Добавляем файл подкючения
include 'db.php';

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

if ($method == 'POST') {

    // Выделяем id события
    $event_id = $_GET['event_id'];

    // Выделяем id типа билета и сколько их нужно
    $tickets = mb_substr(substr($_GET['tickets'], 1), 0, -1);

    echo json_encode($tickets);
    
    // Создаём счётчик с количеством типов билетов
    $count = substr_count($tickets, '%') + 1;

    // Выделяем группы билетов
    $ticket_group = explode("%", $tickets);

    // Генерируем штрихкод и id брони
    $barcode = barcode_gen(12);
    $book_id = book_id_gen(5);

    // Пишем в переменную текущее время
    $created = date("Y-m-d H:m:s", time());

    // Переменная для общей стоимости билетов
    $price = 0;

    // Массив для вывода информации в конце функции
    $response = [];

    $book = "INSERT INTO `book` (`id`, `event_id`, `created`) VALUES ('$book_id', '$event_id', '$created')";
    $ful_book = mysqli_query($cn, $book);

    if($ful_book == true) {
        echo json_encode("Создана запись в таблице book c id $book_id");
    }

    // Цикл по каждому типу билетов
    for ($i = 0; $i < $count; $i++) {

        // $quan_type[0] = id типа билета; $quan_type[1] = количество билетов;
        $quan_type = explode(";", $ticket_group[$i]);

        // Цикл для создания отдельных билетов одной группы
        for ($j = 0; $j < (int)$quan_type[1]; $j++) {

            // Запрос на создание билета
            $query = "INSERT INTO `tickets` (`barcode`, `event_id`, `ticket_type_id`, `book_id`) 
                VALUES ('$barcode', '$event_id', '$quan_type[0]', '$book_id')";

            $ful_query = mysqli_query($cn, $query);

            // Записываем множитель в переменную
            $multi_query = mysqli_query($cn, "SELECT `multi` from `ticket_type` WHERE `id` = '$quan_type[0]'");

            // Записываем в переменную коэффициент
            $fix_part_query = mysqli_query($cn, "SELECT `fix_part` from `events` WHERE `id` = '$event_id'");

            // Рассчитываем полную стоимость всех билетов в одном заказе
            $price = $price + (mysqli_fetch_row($multi_query)[0] * mysqli_fetch_row($fix_part_query)[0]);
        }

    }

    // Записываем полную цену билетов в таблицу book в уже созданную бронь
    $price_add_query = mysqli_query($cn, "UPDATE `book` SET `equal_price` = '$price' WHERE id = '$book_id'");

    // Делаем выборку наших билетов
    $book_show_query = mysqli_query($cn, "SELECT * from `book` WHERE `id` = '$book_id'");

    $row = mysqli_fetch_row($book_show_query);

    // Выводим основную информацию о билетах
    if ($row) {
        $response[] = [
            "book id" => $row[0],
            "event_id" => $row[1],
            "equal_price" => $row[2],
            "created" => $row[3]
        ];
    }

    echo json_encode($response);
}

// if ($method == 'POST') {

//     // Выделяем из запроса необходимые вводимые через API данные
//     $event_id = isset($_GET['event_id']) ? intval($_POST['events_id']) : null;
//     $event_date = isset($_GET['event_date']);
//     $ticket_adult_price = isset($_GET['ticket_adult_price']) ? intval($_GET['ticket_adult_price']) : null;
//     $ticket_adult_quantity = isset($_GET['ticket_adult_quantity']) ? intval($_GET['ticket_adult_qunatity']) : null;
//     $ticket_kid_price = isset($_GET['ticket_kid_price']) ? intval($_GET['ticket_kid_price']) : null;
//     $ticket_kid_quantity = isset($_GET['ticket_kid_quantity']) ? intval($_GET['ticket_kid_quantity']) : null;

//     // Подготавливаем невводимые данные
//     $barcode = barcode_gen(12);
//     $equal_price = $ticket_adult_price * $ticket_adult_quantity + $ticket_kid_price * $ticket_kid_quantity;
//     $create = date("Y-m-d H:m:s", time());

//     $query = "INSERT INTO book (event_id, event_date, ticket_adult_price, 
//                 ticket_adult_quantity, ticket_kid_price, ticket_kid, quantity, $barcode, $equal_price, $create)";

//     $try_query = mysqli_query($cn, $query);
// }

?>