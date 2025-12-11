<?php

class Controller {

    public function render($view, $data = [], $fullLayout = true)
    {
        extract($data);

        if ($fullLayout) {
            require_once __DIR__ . "/../../views/layout/header.php";
        }

        require_once __DIR__ . "/../../views/$view.php";

        if ($fullLayout) {
            require_once __DIR__ . "/../../views/layout/footer.php";
        }
    }
}
