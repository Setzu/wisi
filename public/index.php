<?php
/**
 * Created by PhpStorm.
 * User: david b. <david.blanchard@gfpfrance.com>
 * Date: 19/02/18
 * Time: 11:19
 */

// Affiche les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$loader = require_once __DIR__ . '/../vendor/autoload.php';

if (!isset($_COOKIE['user'])) {
    header('Location: /');
    exit;
}

try {
    try {
        \Wisi\Stdlib\Router::dispatch();
    } catch (\Exception $e) {
        die($e->getMessage());
    }
} catch(\Exception $e) { ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-not-found">
                    <h3 class="page-not-found">
                        <?php
                        if (\Wisi\Stdlib\Module::getEnv() == 'development') {
                            echo 'Exception : ' . $e->getMessage();
                        } else {
                            echo 'Une erreur est survenue, veuillez réessayez ultérieurement.';
                        }
                        ?>
                    </h3>
                </div>
                <a href="" class="btn btn-danger btn-retour" style="float: right;">Retour</a>
            </div>
        </div>
    </div>

<?php } ?>