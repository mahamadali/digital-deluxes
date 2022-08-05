@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("content")
<main class="page-main">
    <div class="widjet --filters">
        <div class="widjet__head">
            <h3 class="uk-text-lead">Games Store</h3>
        </div>
        <div class="widjet__body">
            <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-3@l uk-child-width-1-2@s uk-flex-middle uk-grid-small" data-uk-grid>
                <div class="uk-width-1-1">
                    <div class="search">
                        <div class="search__input"><i class="ico_search"></i><input type="search" name="search" placeholder="Search"></div>
                        <div class="search__btn"><button type="button"><i class="ico_microphone"></i></button></div>
                    </div>
                </div>
                <div><select class="js-select">
                        <option value="">Sort By: Price</option>
                        <option value="Price 1">Price 1</option>
                        <option value="Price 2">Price 2</option>
                        <option value="Price 3">Price 3</option>
                    </select></div>
                <div><select class="js-select">
                        <option value="">Category: Strategy</option>
                        <option value="Category 1">Category 1</option>
                        <option value="Category 2">Category 2</option>
                        <option value="Category 3">Category 3</option>
                    </select></div>
                <div><select class="js-select">
                        <option value="">Platform: All</option>
                        <option value="Platform 1">Platform 1</option>
                        <option value="Platform 2">Platform 2</option>
                        <option value="Platform 3">Platform 3</option>
                    </select></div>
                <div><select class="js-select">
                        <option value=""># of Players: All</option>
                        <option value="Platform 1">Platform 1</option>
                        <option value="Platform 2">Platform 2</option>
                        <option value="Platform 3">Platform 3</option>
                    </select></div>
                <div>
                    <div class="price-range"><label>Price</label><input class="uk-range" type="range" value="2" min="0" max="10" step="0.1"></div>
                </div>
                <div class="uk-text-right"><a href="#!">25 items</a></div>
            </div>
        </div>
    </div>
    <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small" data-uk-grid>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-1.jpg') }}" alt="Struggle of Rivalry" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Struggle of Rivalry</a>
                        <div class="game-card__genre">Shooter / Platformer</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$4.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-2.jpg') }}" alt="Hunt of Duplicity" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Hunt of Duplicity</a>
                        <div class="game-card__genre">Action / Adventure</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.6</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$9.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-3.jpg') }}" alt="Journey and Dimension" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Journey and Dimension</a>
                        <div class="game-card__genre">Survival / Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$13.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-4.jpg') }}" alt="Reckoning and Freedom" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Reckoning and Freedom</a>
                        <div class="game-card__genre">Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$49.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-5.jpg') }}" alt="Pillage of Redemption" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Pillage of Redemption</a>
                        <div class="game-card__genre">Survival / Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$13.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-6.jpg') }}" alt="Invade of Heroes" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Invade of Heroes</a>
                        <div class="game-card__genre">Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$49.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-7.jpg') }}" alt="Genesis and Renegade" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Genesis and Renegade</a>
                        <div class="game-card__genre">Shooter / Platformer</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$4.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-8.jpg') }}" alt="Barbarians and Truth" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Barbarians and Truth</a>
                        <div class="game-card__genre">Shooter / Platformer</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$4.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-9.jpg') }}" alt="Fire and Demons" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Fire and Demons</a>
                        <div class="game-card__genre">Shooter / Platformer</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$4.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-6.jpg') }}" alt="Strife of Retribution" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Strife of Retribution</a>
                        <div class="game-card__genre">Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$49.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-10.jpg') }}" alt="Crimson Resurrection" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Crimson Resurrection</a>
                        <div class="game-card__genre">Shooter / Platformer</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$4.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-4.jpg') }}" alt="Bio Armada" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Bio Armada</a>
                        <div class="game-card__genre">Strategy</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__rating"><span>4.1</span><i class="ico_star"></i></div>
                            <div class="game-card__price"><span>$49.99 </span></div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                            <div class="game-card__users">
                                <ul class="users-list">
                                    <li><img src="{{ url('assets/frontend/img/user-1.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-2.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-3.png') }}" alt="user" /></li>
                                    <li><img src="{{ url('assets/frontend/img/user-4.png') }}" alt="user" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endblock