<?php

function get_ticket_type($cn) {
    $query = "SELECT `id`, `type_name`, `multi` from `ticket_type`";
    $result = mysqli_query($cn, $query);

    $ticket_type = [];

    while ($row = mysqli_fetch_row($result)) {
        if ($row) {
            $ticket_type[] = [
                "type id" => $row[0],
                "type name" => $row[1],
                "multiplicator" => $row[2]
            ];
        }
    }

    echo json_encode($ticket_type);
}

?>