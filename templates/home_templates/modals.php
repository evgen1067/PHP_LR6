<!-- Detail Page -->
<div class=" modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div id="movie-detail-page" class="movie-detail-page">
                    <div class="movie-detail-page__column-1">
                        <div class="column-1__poster">
                            <img class="column-1__poster-image">
                        </div>
                    </div>
                    <div class="movie-detail-page__column-2">
                        <div class="heading">

                        </div>
                        <div class="movie-detail">
                            <div class="set">
                                <label>Дата добавления:</label>
                                <span></span>
                            </div>
                            <div class="set">
                                <label>Имя автора:</label>
                                <span></span>
                            </div>
                            <div class="set">
                                <label>Жанр:</label>
                                <span></span>
                            </div>
                        </div>
                        <div class="movie-description">

                        </div>
                        <div class="video-player">
                            <iframe src="https://www.youtube.com/embed/-LkHS8geUjQ" title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="column-3">
                        <div class="heading" style="color:#fff">
                            Комментарии
                            <span>
                                <br>
                            </span>
                        </div>
                        <div class="form_Bx">
                            <form>
                                <textarea type="text" placeholder="Оставьте комментарий"></textarea>
                                <input type="submit" value="Отправить" class="btn-submit">
                            </form>
                            <div class="comment">
                                <div class="comment-header">

                                </div>
                                <div class="comment-content">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auth and Registration -->
<div class=" modal fade" id="authModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="auth-container">
                    <div class="auth-container__transition-pages">
                        <div class="transition-pages__box signin">
                            <div class="transition-pages__box-text">
                                Уже есть аккаунт?
                            </div>
                            <button id="signinBtn" class="signinBtn">Войти</button>
                        </div>
                        <div class="transition-pages__box signup">
                            <div class="transition-pages__box-text">
                                Все еще нет аккаунта?
                            </div>
                            <button id="signupBtn" class="signupBtn">Зарегистрироваться</button>
                        </div>
                    </div>
                    <div class="auth-container__formBx">
                        <div class="formBx-form signinForm">
                            <form name="Authorisation" id="auth-form" class="formBx-form__form">
                                <div id="auth_form-title" class="formBx-form__form-title">
                                    Авторизация
                                </div>
                                <div id="auth-info-error" class="formBx-form__form-title">

                                </div>
                                <input name="email" id="au_email" required type="email" placeholder="E-mail">
                                <input name="password" required minlength="6" type="password" placeholder="Пароль">
                                <input type="submit" value="Вход">
                            </form>
                        </div>
                        <div class="formBx-form signupForm">
                            <form name="Registration" id="reg-form" class="formBx-form__form">
                                <div id="reg-form-title" class="formBx-form__form-title">
                                    Регистрация
                                </div>
                                <div id="reg-info-error" class="formBx-form__form-title">

                                </div>
                                <input name="name" id="name" required type="text" pattern="^[А-Яа-яЁё\s + -]+$"
                                       title="Допустимы только русские буквы, пробелы и дефисы" placeholder="Имя">
                                <input name="email" id="email" required type="email" placeholder="E-mail">
                                <input name="phone" id="phone" required type="tel"
                                       pattern="^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$"
                                       title="Используйте любой существующий российский формат мобильного телефона."
                                       placeholder="Телефон">
                                <input title="Длина пароля - от 6 до 30 символов. Пароли должны совпадать."
                                       name="password-1" id="password_1" required minlength="6" maxlength="30"
                                       type="password" placeholder="Пароль">
                                <input title="Длина пароля - от 6 до 30 символов. Пароли должны совпадать."
                                       name="password-2" id="password_2" required minlength="6" maxlength="30"
                                       type="password" placeholder="Повторите пароль">
                                <div class="box_chBx">
                                    <input name="terms" type="checkbox" id="checkbox_check">
                                    <span>
                                        Я согласен на обработку персональных данных.
                                    </span>
                                </div>
                                <input name="send" id="btn_registration" disabled class="btn_registration disabled"
                                       type="submit" value="Зарегистрироваться">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
