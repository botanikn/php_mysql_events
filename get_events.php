<?php

    function get_events($cn) {
        $query = "SELECT `event_name`, `fix_part` from `events`";
        $result = mysqli_query($cn, $query);

        $events = [];

        while ($row = mysqli_fetch_row($result)) {
            if ($row) {
                $events[] = [
                    "event" => $row[0],
                    "regular price" => $row[1]
                ];
            }
        }

        echo json_encode($events);
    }

?>