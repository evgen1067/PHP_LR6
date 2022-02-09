<?php

namespace App\Controller;

use PDO;
use Symfony\Component\HttpFoundation\Response;

class MainController extends BaseController
{
    private DBController $db;

    public function __construct()
    {
        $this->db = new DBController();
    }

    public function homepage(): Response
    {
        $pdo = $this->db->connectToDatabase();
        $album_posters = $this->db->getAlbumPosters($pdo);
        $showcase_boxes = $this->db->getShowcaseBoxes($pdo);
        $genres = $this->db->getGenreName($pdo);
        $years = $this->db->getYears($pdo);
        $countries = $this->db->getCountryName($pdo);
        $movie_cards = $this->db->getMovieCards($pdo);
        $main_page = [
            'album_posters' => $album_posters,
            'showcase_boxes' => $showcase_boxes,
            'genres' => $genres,
            'years' => $years,
            'countries' => $countries,
            'movie_cards' => $movie_cards
        ];
        return $this->renderTemplate('home_page.php', $main_page);
    }

    public function detailpage(): Response
    {
        $id_overviews = $this->clear_string($_GET['id_overviews']);
        $pdo = $this->db->connectToDatabase();
        $detail_page = $this->db->getDetailPage($pdo, $id_overviews);
        return $this->renderTemplate('detail_page.php', $detail_page);
    }

    public function showmore(): Response
    {
        $endpoint = $this->clear_string($_GET['endpoint']);
        $pdo = $this->db->connectToDatabase();
        $movie_cards = $this->db->showMoreMoviesCards($pdo, $endpoint);
        return $this->renderTemplate('more_overviews.php', $movie_cards);
    }

    public function all_comments(): Response
    {
        $id_overviews = $this->clear_string($_GET['id_overviews']);
        $pdo = $this->db->connectToDatabase();
        $comments = $this->db->getAllComments($pdo, $id_overviews);
        return $this->renderTemplate('all_comments.php', $comments);
    }

    public function search(): Response
    {
        $search_query = $this->clear_string($_GET['search_query']);
        $pdo = $this->db->connectToDatabase();
        $search_overviews = $this->db->searchOverviews($pdo, $search_query);
        return $this->renderTemplate('search_result_page.php', $search_overviews);
    }

    public function generateToken(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[rand(0, $max)];
        }
        return implode('', $pieces);
    }

    public function updateUserToken(int $userId, string $token): void
    {
        $pdo = $this->db->connectToDatabase();
        $query = 'UPDATE `users` SET `api_token` = :api_token WHERE `id_users` = :id_users';
        $params = [
            'api_token' => $token,
            'id_users' => $userId
        ];
        $pdo->prepare($query)->execute($params);
        setcookie('api_token', $token);
        $_SESSION['api_token'] = $token;
    }

    public function getUser(string $email, string $password): void
    {
        $pdo = $this->db->connectToDatabase();
        $stmt = $pdo->prepare(
            "SELECT `id_users`, `id_roles`, `name_users`, `last_visit`, `password`, `phone` FROM `users` WHERE `email` = ?"
        );
        $stmt->execute(
            [
                $email
            ]
        );
        $row = $stmt->fetch(PDO::FETCH_LAZY);
        if ($stmt->rowCount() == 0 || !password_verify($password, $row->password)) {
            if ($stmt->rowCount() == 0) {
                $_SESSION['user'] = [
                    'status' => 0,
                    'message' => 'Такой email не найден'
                ];
            } else {
                $_SESSION['user'] = [
                    'status' => 0,
                    'message' => 'Неверный пароль'
                ];
            }
        } else {
            $stmt = $pdo->prepare("UPDATE `users` SET `last_visit`= now() WHERE `email` = :email");
            $stmt->execute(
                [
                    $email
                ]
            );
            $_SESSION['user'] = [
                "status" => 1,
                "id_users" => $row->id_users,
                "id_roles" => $row->id_roles,
                "name_users" => $row->name_users,
                "email" => $email,
                "phone" => $row->phone,
                "last_visit" => date('d.m.Y H:i')
            ];
            $token = $this->generateToken();
            $this->updateUserToken($_SESSION['user']['id_users'], $token);
        }
    }

    public function check_auth_valid(string $email, string $password): bool
    {
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Email введен неверно'
            ];
            return false;
        }
        if (strlen($password) < 6) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Длина пароля неправильна'
            ];
            return false;
        }
        return true;
    }

    public function logInUser(): Response
    {
        $email = $this->clear_string($_POST['au_email']);
        $password = $this->clear_string($_POST['au_password']);
        $token = '';
        if ($this->check_auth_valid($email, $password)) {
            $this->getUser($email, $password);
        }
        return $this->renderTemplate('logUser.php', $_SESSION['user']);
    }

    public function checkUserAuthorization(): Response
    {
        $authorization_flag = $this->db->checkAuthorization();
        return $this->renderTemplate('logUser.php', $_SESSION['user']);
    }

    private function clearUserInformation(): void
    {
        $_SESSION['user']['status'] = null;
        $_SESSION['user']['id_users'] = null;
        $_SESSION['user']['id_roles'] = null;
        $_SESSION['user']['name_users'] = null;
        $_SESSION['user']['email'] = null;
        $_SESSION['user']['phone'] = null;
        $_SESSION['user']['last_visit'] = null;
        $_SESSION['api_token'] = null;
        $_SESSION['test'] = null;
        $_COOKIE['api_token'] = null;
    }

    public function logOutUser(): Response
    {
        $this->clearUserInformation();
        return $this->renderTemplate('logUser.php', ['status' => 1]);
    }

    public function profileUserInfo(): Response
    {
        if (!empty($_SESSION['user']['id_users'])) {
            if ($_SESSION['user']['id_roles'] == '2') {
                $role = 'Пользователь';
            } else {
                $role = 'Администратор';
            }
            $today = date('d.m.Y');
            if ($today == date('d.m.Y', strtotime($_SESSION['user']['last_visit']))) {
                $date = 'сегодня';
            } elseif ($today == (date('d.m.Y', strtotime($_SESSION['user']['last_visit']. ' + 1 day')))) {
                $date = 'вчера';
            } else {
                $date = date('d.m.Y', strtotime($_SESSION['user']['last_visit']));
            }

            $user = [
                'id_users' => $_SESSION['user']['id_users'],
                'id_roles' => $_SESSION['user']['id_roles'],
                'email' => $_SESSION['user']['email'],
                'phone' => $_SESSION['user']['phone'],
                'name_users' => $_SESSION['user']['name_users'],
                'role' => $role,
                'date' => $date . ' в ' . date('H:m', strtotime($_SESSION['user']['last_visit'])),
            ];

            $pdo = $this->db->connectToDatabase();
            $numberAddedOverviews = $this->db->getNumberAddedOverviews($pdo, $user['id_users']);

            $movie_cards = $this->db->getAllAddedOverviews($pdo, $user['id_users']);

            $result = [
                'user' => $user,
                'numberAddedOverviews' => $numberAddedOverviews,
                'movie_cards' => $movie_cards
            ];

            return $this->renderTemplate('user_profile_page.php', $result);
        } else {
            header('Location: /');
        }
    }

    public function deleteComments(): Response
    {
        if (!empty($_SESSION['user']['id_users']) && $_SESSION['user']['id_roles'] == 1) {
            $id_comments = $this->clear_string($_GET['id_comments']);
            $pdo = $this->db->connectToDatabase();
            $comments_flag = $this->db->deleteComment($pdo, $id_comments);
            return $this->renderTemplate('logUser.php', ['status' => $id_comments]);
        } else {
            header('Location: /');
        }
    }

    public function addComments(): Response
    {
        if (!empty($_SESSION['user']['id_users'])) {
            $pdo = $this->db->connectToDatabase();
            $text_comment = $this->clear_string($_POST['text_comment']);
            $id_overviews = $this->clear_string($_POST['id_overviews']);
            $comments_flag = $this->db->addComment($pdo, $text_comment, $id_overviews);
            $pdo = $this->db->connectToDatabase();
            $comments = $this->db->getAllComments($pdo, $id_overviews);
            return $this->renderTemplate('all_comments.php', $comments);
        } else {
            header('Location: /');
        }
    }

    public function addpage(): Response
    {
        if (!empty($_SESSION['user']['id_users']) && $_SESSION['user']['id_roles'] == 1) {
            $pdo = $this->db->connectToDatabase();
            $hints = $this->db->getHints($pdo);
            return $this->renderTemplate('add_film_page.php', $hints);
        } else {
            header('Location: /');
        }
    }

    public function addOverview()
    {
        if (!empty($_SESSION['user']['id_users']) && $_SESSION['user']['id_roles'] == 1) {
            // Film
            $name_movie = $this->clear_string($_POST['name_movie']);
            $date_movie = $this->clear_string($_POST['date_movie']);
            $country_movie = $this->clear_string($_POST['country_movie']);
            $producer_movie = $this->clear_string($_POST['producer_movie']);

            // Overview
            $id_users = $this->clear_string($_SESSION['user']['id_users']);
            $overview_name = $this->clear_string($_POST['overview_name']);
            $trailer = $this->clear_string($_POST['trailer']);
            $genres = json_decode($_POST['genres']);
            $text_movie = $this->clear_string($_POST['text_movie']);

            $current_id_films = null;
            $current_id_overview = null;
            $current_id_genres = array();

            if (!empty($name_movie)
                && !empty($date_movie)
                && !empty($country_movie)
                && !empty($producer_movie)
                && !empty($id_users)
                && !empty($overview_name)
                && !empty($trailer)
                && !empty($genres)
                && !empty($text_movie)
                && !empty($_FILES['poster'])
                && !empty($_FILES['album_poster'])) {
                if ($this->check_add_valid(
                    $name_movie,
                    $producer_movie,
                    $date_movie,
                    $country_movie,
                    $overview_name,
                    $text_movie,
                    $trailer
                )) {
                    $pdo = $this->db->connectToDatabase();
                    $this->db->check_film($pdo, $name_movie, $producer_movie, $date_movie, $country_movie);
                    $this->db->add_ignore_genres($pdo, $genres);
                    $this->db->check_added_genres($pdo, $genres);
                    $this->db->add_films_and_genres($pdo, $_SESSION['current']['id_genres'], $_SESSION['current']['id_films']);
                    $this->db->add_overview($pdo, $id_users, $_SESSION['current']['id_films'], $overview_name, $text_movie, $trailer);

                    $_SESSION['add'] = [
                        'status' => '1'
                    ];
                }
            }
            return $this->renderTemplate('logUser.php', $_SESSION['add']);
        } else {
            header('Location: /');
        }
    }

    private function check_add_valid(
        string $name_movie,
        string $producer_movie,
        string $date_movie,
        string $country_movie,
        string $overview_name,
        string $text_movie,
        string $trailer
    ): bool {
        if (empty($name_movie)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите название фильма'
            ];
            return false;
        }
        if (empty($producer_movie)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите имя режиссера'
            ];
            return false;
        }
        if (empty($date_movie)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите дату выхода'
            ];
            return false;
        }
        if (empty($country_movie)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите страну'
            ];
            return false;
        }
        if (empty($overview_name)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите название обзора'
            ];
            return false;
        }
        if (empty($text_movie)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите текст обзора'
            ];
            return false;
        }
        if (empty($trailer)) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Введите ссылку на трейлер'
            ];
            return false;
        }
        $mimetype = mime_content_type($_FILES['poster']['tmp_name']);
        if (!(in_array($mimetype, array('image/jpeg', 'image/png', 'image/jpg')))) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Проверьте mime-type загружаемого постера'
            ];
            return false;
        }
        $mimetype = mime_content_type($_FILES['album_poster']['tmp_name']);
        if (!(in_array($mimetype, array('image/jpeg','image/png', 'image/jpg')))) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Проверьте mime-type загружаемого альбомного постера'
            ];
            return false;
        }
        if (!(in_array($this->getExtension($_FILES['poster']['name']), array('jpeg','png','jpg')))) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Проверьте тип загружаемого постера'
            ];
            return false;
        }
        if (!(in_array($this->getExtension($_FILES['album_poster']['name']), array('jpeg','png','jpg')))) {
            $_SESSION['add'] = [
                'status' => 0,
                'message' => 'Проверьте тип загружаемого постера'
            ];
            return false;
        }
        return true;
    }

    private function getExtension($filename)
    {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }

    private function checkUserEmail(string $name, string $email, string $phone, string $password): void
    {
        $pdo = $this->db->connectToDatabase();
        $query = 'SELECT * FROM `users` WHERE `email` = :email;';
        $params = [
            'email' => $email
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        if ($stmt->rowCount() == 0) {
            $this->regUser($name, $email, $phone, $password);
        } else {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Такой Email уже существует'
            ];
        }
    }

    private function check_valid(string $name, string $email, string $phone, string $password_1, string $password_2): bool
    {
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Email введен неверно'
            ];
            return false;
        }
        if (!(preg_match('/^[А-Яа-яЁё\s + -]+$/iu', $name))) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Имя введено неправильно'
            ];
            return false;
        }
        if (!(preg_match('/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', $phone))) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Email введен неправильно'
            ];
            return false;
        }
        if (strlen($password_1) < 6) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Длина пароля неправильна'
            ];
            return false;
        }
        if (strlen($password_2) < 6) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Длина пароля неправильна'
            ];
            return false;
        }
        if ($password_1 != $password_2) {
            $_SESSION['user'] = [
                'status' => 0,
                'message' => 'Пароли не совпадают'
            ];
            return false;
        }
        return true;
    }

    private function regUser(string $name, string $email, string $phone, string $password)
    {
        $pdo = $this->db->connectToDatabase();
        //Регистрирую пользователя
        $query = "INSERT INTO `users` (`name_users`, `email`, `phone`, `password`, `api_token`) VALUES ( :name_users, :email, :phone, :password, :token)";
        $params = [
            'name_users' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'token' => 'token'
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $this->getUser($email, $password);
    }

    public function signUpUser(): Response
    {
        if (isset($_POST['email'])) {
            $name = $this->clear_string($_POST['name']);
            $email = $this->clear_string($_POST['email']);
            $phone = $this->clear_string($_POST['phone']);
            $password_1 = $this->clear_string($_POST['password-1']);
            $password_2 = $this->clear_string($_POST['password-2']);
        }
        if (!empty($name)
            && !empty($email)
            && !empty($phone)
            && !empty($password_1)
            && !empty($password_2)) {
            if ($this->check_valid($name, $email, $phone, $password_1, $password_2)) {
                $pdo = $this->db->connectToDatabase();
                $this->checkUserEmail($name, $email, $phone, $password_1);
            }
        }
        return $this->renderTemplate('logUser.php', $_SESSION['user']);
    }

    private function clear_string(string $str): string
    {
        return trim(($this->filter_string_polyfill($str)));
    }

    private function filter_string_polyfill(string $string): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }
}
