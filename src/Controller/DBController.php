<?php

namespace App\Controller;

use PDO;
use PDOException;

class DBController
{
    public function connectToDatabase()
    {
        $dsn = "mysql:host=" . $_SESSION['ini_array']['host'] . ";dbname=" . $_SESSION['ini_array']['db'] . ";charset=" . $_SESSION['ini_array']['charset'];
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $_SESSION['ini_array']['user'], $_SESSION['ini_array']['pass'], $opt);
            return $pdo;
        } catch (PDOException $e) {
            print "Has errors: " . $e->getMessage();
            die();
        }
    }

    public function getAlbumPosters(PDO $pdo): array
    {
        $album_posters = [];
        $query = 'SELECT `o`.`album_poster`
                  FROM `overviews` `o`,
                       `films` `f`
                  WHERE `o`.`id_films` = `f`.`id_films`
                  LIMIT :limit';
        $params = [
            'limit' => 6
        ];
        if (!empty($pdo)) {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $album_posters[] = $row->album_poster;
            }
        }
        return $album_posters;
    }

    public function getShowcaseBoxes(PDO $pdo): array
    {
        $showcase_boxes = [];

        $query = 'SELECT `o`.`id_overviews`,
                                 `o`.`poster`              AS `card_poster`,
                                 `f`.`name_films`          AS `card_name`,
                                 YEAR(`f`.`date_films`)        AS `card_year`,
                                 (SELECT GROUP_CONCAT(`g`.`name_genres`)
                                  FROM `films_genres` `fg`,
                                       `genres` `g`
                                  WHERE `f`.`id_films` = `fg`.`id_films`
                                    AND `fg`.`id_genres` = `g`.`id_genres`
                                  GROUP BY `f`.`id_films`) AS `card_genre`
                          FROM `films` `f`,
                               `overviews` `o`
                          WHERE (`f`.`id_films` = `o`.`id_films`
                            AND YEAR(`f`.`date_films`) = 2021)';
        if (!empty($pdo)) {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $card_year_and_genre = $row->card_year . ', ' . $row->card_genre;

                $showcase_box = [
                    'id_overviews' => $row->id_overviews,
                    'card_poster' => $row->card_poster,
                    'card_name' => $row->card_name,
                    'card_year_and_genre' => $card_year_and_genre
                ];
                $showcase_boxes[] = $showcase_box;
            }
        }
        return $showcase_boxes;
    }

    public function getGenreName(PDO $pdo): array
    {
        $genres = [];
        $query = 'SELECT `name_genres`
                                  FROM `genres`
                                  ORDER BY `name_genres`';
        if (!empty($pdo)) {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $genres[] = $row->name_genres;
            }
        }
        return $genres;
    }

    public function getYears(PDO $pdo): array
    {
        $years = [];
        $query = 'SELECT DISTINCT YEAR(`date_films`) AS `date_films`
                                  FROM `films`
                                  ORDER BY `date_films`';
        if (!empty($pdo)) {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $years[] = $row->date_films;
            }
        }
        return $years;
    }

    public function getCountryName(PDO $pdo): array
    {
        $countries = [];
        $query = 'SELECT DISTINCT `country_films`
                                  FROM `films`
                                  ORDER BY `country_films`';
        if (!empty($pdo)) {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $countries[] = $row->country_films;
            }
        }
        return $countries;
    }

    public function getMovieCards(PDO $pdo): array
    {
        $movie_cards = [];
        $query = 'SELECT `o`.`name_overviews`,
                         `o`.`id_overviews`,
                         `o`.`poster`                 AS `card_poster`,
                         `f`.`name_films`             AS `card_name`,
                         `f`.`country_films`          AS `card_country`,
                         YEAR(`f`.`date_films`)       AS `card_year`,
                         (SELECT CONCAT(\'["\',GROUP_CONCAT(`g`.`name_genres` SEPARATOR \'", "\'), \'"]\')
                          FROM `films_genres` `fg`,
                               `genres` `g`
                          WHERE `f`.`id_films` = `fg`.`id_films`
                            AND `fg`.`id_genres` = `g`.`id_genres`
                          GROUP BY `f`.`id_films`) AS `card_genre`
                  FROM `films` `f`,
                       `overviews` `o`
                  WHERE (`f`.`id_films` = `o`.`id_films`)
                  ORDER BY `o`.`id_overviews` DESC
                  LIMIT :limit';
        if (!empty($pdo)) {
            $params = [
                'limit' => 1
            ];
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $card_year_and_genre = json_decode($row->card_genre)[0];
                if (count(json_decode($row->card_genre)) > 1) {
                    $card_year_and_genre .= ', ' . json_decode($row->card_genre)[1] . '...';
                } else {
                    $card_year_and_genre .= '.';
                }

                $card_year_and_genre = $row->card_year . ', ' . $card_year_and_genre;

                $movie_card = [
                    'name_overviews' => $row->name_overviews . '.',
                    'id_overviews' => $row->id_overviews,
                    'card_poster' => $row->card_poster,
                    'card_name' => $row->card_name,
                    'card_country' => $row->card_country,
                    'card_year' => $row->card_year,
                    'card_genre' => $row->card_genre,
                    'card_year_and_genre' => $card_year_and_genre,
                ];

                $movie_cards[] = $movie_card;
            }
        }
        return $movie_cards;
    }

    public function getAllComments(PDO $pdo, int $id_overviews): array
    {
        $comments = [];

        $query = "SELECT `id_comments`, `name_users`, `text_comments`, `u`.`id_users` AS id_current_user FROM `comments` `c`, `users` `u` WHERE `c`.`id_users` = `u`.`id_users` AND `c`.`id_overviews` = :id_overviews ORDER BY `c`.`date_comments` DESC ;";
        $params = [
            'id_overviews' => $id_overviews,
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $comment = [
                'id_comments' => $row->id_comments,
                'name_users' => $row->name_users,
                'text_comments' => $row->text_comments,
                'id_current_user' => $row->id_current_user,
            ];
            $comments[] = $comment;
        }

        return $comments;
    }

    public function getDetailPage(PDO $pdo, int $id_overviews): array
    {
        $query = 'SELECT `f`.`date_films`,
                     `f`.`country_films`,
                     `f`.`producer`,
                     `o`.`name_overviews`,
                     `o`.`poster`,
                     CONCAT(`f`.`name_films`, \' (\', YEAR(`f`.`date_films`), \')\') AS `name_film`,
                     DATE(`o`.`add_date`)                                    AS `date_added`,
                     `u`.`name_users`,
                     (SELECT GROUP_CONCAT(`name_genres`)
                      FROM `genres` `g`,
                           `films_genres` `fg`
                      WHERE `f`.`id_films` = `fg`.`id_films`
                        AND `fg`.`id_genres` = `g`.`id_genres`)                   AS `name_genres`,
                     `o`.`text_overview`,
                     `o`.`trailer`,
                     (SELECT count(*)
                      FROM `comments` `c`
                      WHERE `c`.`id_overviews` = `o`.`id_overviews`)              AS `comments_count`
              FROM `overviews` `o`,
                   `films` `f`,
                   `users` `u`
              WHERE (`u`.`id_users` = `o`.`id_users`
                 AND `o`.`id_overviews` = :id_overviews
                 AND o.`id_films` = `f`.`id_films`)';
        $params = [
            'id_overviews' => $id_overviews,
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_LAZY);

        return [
            'id_overviews' => $id_overviews,
            'date_films' => date('d.m.Y', strtotime($row->date_films)),
            'country_films' => $row->country_films,
            'producer' => $row->producer,
            'name_overviews' => $row->name_overviews,
            'poster' => $row->poster,
            'name_film' => $row->name_film,
            'date_added' => date('d.m.Y', strtotime($row->date_added)),
            'name_users' => $row->name_users,
            'name_genres' => $row->name_genres,
            'text_overview' => $row->text_overview,
            'trailer' => $row->trailer,
            'comments_count' => $row->comments_count
        ];
    }

    public function showMoreMoviesCards(PDO $pdo, int $endpoint): array
    {
        $movie_cards = [];

        $query = 'SELECT `o`.`name_overviews`,
                         `o`.`id_overviews`,
                         `o`.`poster`                 AS `card_poster`,
                         `f`.`name_films`             AS `card_name`,
                         `f`.`country_films`          AS `card_country`,
                         YEAR(`f`.`date_films`)       AS `card_year`,
                      (SELECT CONCAT(\'["\',GROUP_CONCAT(`g`.`name_genres` SEPARATOR \'", "\'), \'"]\')
                       FROM `films_genres` `fg`,
                            `genres` `g`
                       WHERE `f`.`id_films` = `fg`.`id_films`
                         AND `fg`.`id_genres` = `g`.`id_genres`
                       GROUP BY `f`.`id_films`) AS `card_genre`
               FROM `films` `f`,
                    `overviews` `o`
               WHERE (`f`.`id_films` = `o`.`id_films`
                  AND `o`.`id_overviews` < :endpoint)
               ORDER BY `o`.`id_overviews` DESC LIMIT :limit';
        $params = [
            'limit' => 1,
            'endpoint' => $endpoint,
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $card_year_and_genre = json_decode($row->card_genre)[0];
            if (count(json_decode($row->card_genre)) > 1) {
                $card_year_and_genre .= ', ' . json_decode($row->card_genre)[1] . '...';
            } else {
                $card_year_and_genre .= '.';
            }

            $card_year_and_genre = $row->card_year . ', ' . $card_year_and_genre;

            $movie_card = [
                'name_overviews' => $row->name_overviews . '.',
                'id_overviews' => $row->id_overviews,
                'card_poster' => $row->card_poster,
                'card_name' => $row->card_name,
                'card_year' => $row->card_year,
                'card_genre' => $row->card_genre,
                'card_year_and_genre' => $card_year_and_genre,
                'card_country' => $row->card_country
            ];

            $movie_cards[] = $movie_card;
        }

        return $movie_cards;
    }

    public function addComment(PDO $pdo, string $text_comment, int $id_overviews): bool
    {
        $query = "INSERT INTO `comments` (`id_overviews`, `id_users`, `text_comments`) VALUES (:id_overviews, :id_users, :text_comments)";
        $params = [
            'id_overviews' => $id_overviews,
            'id_users' => $_SESSION['user']['id_users'],
            'text_comments' => $text_comment
        ];
        $stmt = $pdo->prepare($query);
        if ($stmt->execute($params)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function add_film(PDO $pdo, string $name_movie, string $producer_movie, string $date_movie, string $country_movie): void
    {
        $query = 'INSERT INTO `films` (`name_films`, `producer`, `date_films`, `country_films`) VALUES (:name_films, :producer, :date_films, :country_films)';
        $params = [
            'name_films' => $name_movie,
            'producer' => $producer_movie,
            'date_films' => $date_movie,
            'country_films' => $country_movie
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $this->check_film($pdo, $name_movie, $producer_movie, $date_movie, $country_movie);
    }

    public function check_film(PDO $pdo, string $name_movie, string $producer_movie, string $date_movie, string $country_movie): void
    {
        if (!empty($pdo)) {
            $query = 'SELECT `id_films` FROM `films` WHERE `name_films` = :name_films AND `producer` = :producer AND `date_films` = :date_films AND `country_films` = :country_films';
            $params = [
                'name_films' => $name_movie,
                'producer' => $producer_movie,
                'date_films' => $date_movie,
                'country_films' => $country_movie
            ];
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            if ($stmt->rowCount() == 0) {
                $this->add_film($pdo, $name_movie, $producer_movie, $date_movie, $country_movie);
            } else {
                $row = $stmt->fetch(PDO::FETCH_LAZY);
                $_SESSION['current']['id_films'] = $row->id_films;
            }
        }
    }

    public function add_ignore_genres(PDO $pdo, array $genres): void
    {
        $query = 'INSERT IGNORE INTO `genres` (`name_genres`) VALUES ';
        for ($i = 0; $i < count($genres); $i++) {
            $query .= '("' . mb_strtolower(($genres[$i])) . '")';
            if ($i != count($genres) - 1) {
                $query .= ', ';
            } else {
                $query .= ';';
            }
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    public function check_added_genres(PDO $pdo, array $genres): void
    {
        $query = "SELECT id_genres FROM `genres` WHERE `name_genres` IN ('";
        for ($i = 0; $i < count($genres); $i++) {
            $query .= mb_strtolower($genres[$i]);
            if ($i != count($genres) - 1) {
                $query .= "', '";
            }
        }
        $query .= "')";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $j = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $_SESSION['current']['id_genres'][$j] = $row->id_genres;
            $j++;
        }
    }

    public function add_films_and_genres(PDO $pdo, int $current_id_genre, int $current_id_films): void
    {
        $query = "INSERT IGNORE INTO `films_genres` (`id_genres`, `id_films`) VALUES ";
        for ($i = 0; $i < count($current_id_genre); $i++) {
            $query .= '(' . $current_id_genre[$i] . ', ' . $current_id_films . ')';
            if ($i != count($current_id_genre) - 1) {
                $query .= ', ';
            }
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    public function add_overview(PDO $pdo, int $id_users, int $id_films, string $name_overviews, string $text_overview, string $trailer): void
    {
        $path_poster = 'uploads/' . time() . $_FILES['poster']['name'];
        $path_album_poster = 'uploads/' . time() . $_FILES['album_poster']['name'];
        move_uploaded_file($_FILES['poster']['tmp_name'], $path_poster);
        move_uploaded_file($_FILES['album_poster']['tmp_name'], $path_album_poster);
        $query = 'INSERT INTO `overviews`(
            `id_users`,
            `id_films`,
            `name_overviews`,
            `poster`,
            `album_poster`,
            `text_overview`,
            `trailer`        )
        VALUES(
            :id_users,
            :id_films,
            :name_overviews,
            :poster,
            :album_poster,
            :text_overview,
            :trailer)';
        $params = [
            'id_users' => $id_users,
            'id_films' => $id_films,
            'name_overviews' => $name_overviews,
            'poster' => $path_poster,
            'album_poster' => $path_album_poster,
            'text_overview' => $text_overview,
            'trailer' => $trailer
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
    }

    public function checkAuthorization(): array
    {
        if (!($_SESSION['api_token']) != null) {
            //Пользователь не авторизован
            $_SESSION['user']['status'] = 0;
        }

        return ($_SESSION['user']);
    }

    public function deleteComment(PDO $pdo, int $id_comments): bool
    {
        $query = "DELETE FROM `comments` WHERE `id_comments` = :id_comments";
        $params = [
            'id_comments' => $id_comments,
        ];
        $stmt = $pdo->prepare($query);
        if ($stmt->execute($params)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function searchOverviews(PDO $pdo, string $search): array
    {
        $search_overviews = [];

        $query = 'SELECT `o`.`name_overviews`,
                         `o`.`id_overviews`,
                         `o`.`poster`                 AS `card_poster`,
                         `f`.`name_films`             AS `card_name`,
                         `f`.`country_films`          AS `card_country`,
                         YEAR(`f`.`date_films`)       AS `card_year`,
                         (SELECT CONCAT(\'["\',GROUP_CONCAT(`g`.`name_genres` SEPARATOR \'", "\'), \'"]\')
                          FROM `films_genres` `fg`,
                               `genres` `g`
                          WHERE `f`.`id_films` = `fg`.`id_films`
                            AND `fg`.`id_genres` = `g`.`id_genres`
                          GROUP BY `f`.`id_films`) AS `card_genre`
                  FROM `films` `f`,
                       `overviews` `o`
                  WHERE (`f`.`id_films` = `o`.`id_films` AND `f`.`name_films` LIKE ?)
                  ORDER BY `o`.`id_overviews` DESC';
        $params = ["%$search%"];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        if ($stmt->rowCount() == 0) {
            $search_overviews = [
                'rowCount' => 0,
                'message' =>  'Упс...По вашему запросу ' . $search . ' ничего не найдено!'
            ];
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $card_year_and_genre = json_decode($row->card_genre)[0];
                if (count(json_decode($row->card_genre)) > 1) {
                    $card_year_and_genre .= ', ' . json_decode($row->card_genre)[1] . '...';
                } else {
                    $card_year_and_genre .= '.';
                }

                $card_year_and_genre = $row->card_year . ', ' . $card_year_and_genre;

                $search_overview = [
                    'name_overviews' => $row->name_overviews . '.',
                    'id_overviews' => $row->id_overviews,
                    'card_poster' => $row->card_poster,
                    'card_name' => $row->card_name,
                    'card_year' => $row->card_year,
                    'card_genre' => $row->card_genre,
                    'card_year_and_genre' => $card_year_and_genre,
                    'card_country' => $row->card_country
                ];

                $search_overviews[] = $search_overview;
            }
        }

        return $search_overviews;
    }

    public function getNumberAddedOverviews(PDO $pdo, int $id_users)
    {
        $stmt = $pdo->prepare(
            'SELECT (SELECT count(*) FROM `overviews` `o` WHERE `u`.`id_users` = `o`.`id_users`) AS count_overviews FROM `users` `u` WHERE `u`.`id_users` = ?'
        );
        $stmt->execute([$id_users]);
        $row = $stmt->fetch(PDO::FETCH_LAZY);
        return $row->count_overviews;
    }

    public function getAllAddedOverviews(PDO $pdo, int $id_users): array
    {
        $movie_cards = [];

        $query = 'SELECT `o`.`name_overviews`,
                         `o`.`id_overviews`,
                         `o`.`poster`                 AS `card_poster`,
                         `f`.`name_films`             AS `card_name`,
                         `f`.`country_films`          AS `card_country`,
                         YEAR(`f`.`date_films`)       AS `card_year`,
                         (SELECT CONCAT(\'["\',GROUP_CONCAT(`g`.`name_genres` SEPARATOR \'", "\'), \'"]\')
                          FROM `films_genres` `fg`,
                               `genres` `g`
                          WHERE `f`.`id_films` = `fg`.`id_films`
                            AND `fg`.`id_genres` = `g`.`id_genres`
                          GROUP BY `f`.`id_films`) AS `card_genre`
                  FROM `films` `f`,
                       `overviews` `o`
                  WHERE (`f`.`id_films` = `o`.`id_films` AND `o`.`id_users` = ?)
                  ORDER BY `o`.`id_overviews` DESC';
        $params = [$id_users];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $card_year_and_genre = json_decode($row->card_genre)[0];
            if (count(json_decode($row->card_genre)) > 1) {
                $card_year_and_genre .= ', ' . json_decode($row->card_genre)[1] . '...';
            } else {
                $card_year_and_genre .= '.';
            }

            $card_year_and_genre = $row->card_year . ', ' . $card_year_and_genre;

            $movie_card = [
                'name_overviews' => $row->name_overviews . '.',
                'id_overviews' => $row->id_overviews,
                'card_poster' => $row->card_poster,
                'card_name' => $row->card_name,
                'card_country' => $row->card_country,
                'card_year' => $row->card_year,
                'card_genre' => $row->card_genre,
                'card_year_and_genre' => $card_year_and_genre,
            ];

            $movie_cards[] = $movie_card;
        }
        return $movie_cards;
    }

    private function getFilmsForHint(PDO $pdo): array
    {
        $filmsName = [];

        $query = "SELECT DISTINCT `name_films` FROM `films`";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $filmsName[] = $row->name_films;
        }

        return $filmsName;
    }

    private function getProducerForHint(PDO $pdo): array
    {
        $producersName = [];

        $query = "SELECT DISTINCT `producer` FROM `films`";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $producersName[] = $row->producer;
        }

        return $producersName;
    }

    private function getCountryForHint(PDO $pdo): array
    {
        $countriesName = [];

        $query = "SELECT DISTINCT `country_films` FROM `films`";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $countriesName[] = $row->country_films;
        }

        return $countriesName;
    }

    private function getGenreForHint(PDO $pdo): array
    {
        $genres = [];

        $query = "SELECT `name_genres` FROM `genres`";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $genres[] = $row->name_genres;
        }

        return $genres;
    }

    public function getHints(PDO $pdo): array
    {
        $films = $this->getFilmsForHint($pdo);
        $producers = $this->getProducerForHint($pdo);
        $countries = $this->getCountryForHint($pdo);
        $genres = $this->getGenreForHint($pdo);

        return [
            'films' => $films,
            'producers' => $producers,
            'countries' => $countries,
            'genres' => $genres,
        ];
    }
}
