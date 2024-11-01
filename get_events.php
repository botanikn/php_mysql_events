<?php

    function get_events($cn) {
        $query = "SELECT `id`, `event_name`, `fix_part`, `date` from `events`";
        $result = mysqli_query($cn, $query);

        $events = [];

        while ($row = mysqli_fetch_row($result)) {
            if ($row) {
                $events[] = [
                    "event id" => $row[0],
                    "event" => $row[1],
                    "regular price" => $row[2],
                    "event date" => $row[3]
                ];
            }
        }

        echo json_encode($events);
    }

?>