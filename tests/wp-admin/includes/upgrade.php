<?php
function dbDelta($sql) {
    $GLOBALS['last_sql'] = $sql;
}
