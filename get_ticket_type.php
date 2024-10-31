<?php

function get_ticket_type($cn) {
    $query = "SELECT `type_name`, `multi` from `ticket_type`";
    $result = mysqli_query($cn, $query);

    $ticket_type = [];

    while ($row = mysqli_fetch_row($result)) {
        if ($row) {
            $ticket_type[] = [
                "type name" => $row[0],
                "multiplicator" => $row[1]
            ];
        }
    }

    echo json_encode($ticket_type);
}

?>