<?php

function book ($cn) {
    // Выделяем id события
    $event_id = $_GET['event_id'];

    // Выделяем id типа билета и сколько их нужно
    $tickets = mb_substr(substr($_GET['tickets'], 1), 0, -1);
    
    // Создаём счётчик с количеством типов билетов
    $count = substr_count($tickets, '%') + 1;

    // Выделяем группы билетов
    $ticket_group = explode("%", $tickets);

    // Генерируем id брони
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

            // Генерируем штрихкод билета
            $barcode = barcode_gen(8);

            // Записываем множитель в переменную
            $multi_query = mysqli_query($cn, "SELECT `multi` from `ticket_type` WHERE `id` = '$quan_type[0]'");

            // Записываем в переменную коэффициент
            $fix_part_query = mysqli_query($cn, "SELECT `fix_part` from `events` WHERE `id` = '$event_id'");

            $one_ticket = mysqli_fetch_row($multi_query)[0] * mysqli_fetch_row($fix_part_query)[0];

            // Запрос на создание билета
            $query = "INSERT INTO `tickets` (`barcode`, `event_id`, `ticket_type_id`, `book_id`, `price`) 
                VALUES ('$barcode', '$event_id', '$quan_type[0]', '$book_id', '$one_ticket')";

            $ful_query = mysqli_query($cn, $query);

            // Рассчитываем полную стоимость всех билетов в одном заказе
            $price = $price + $one_ticket;
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

?>