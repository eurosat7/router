<?php

declare(strict_types=1);

http_response_code(404);

?><h1>file not found</h1>

    <ul>
        <li><a href="/">home</a></li>
    </ul>

<?php
include(__DIR__ . '/debug.php');
