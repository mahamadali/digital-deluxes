@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width-2-3@l uk-width-3-3@m uk-width-3-3@s">
            <h3 class="uk-text-lead">Recommended & Featured</h3>
            <div class="js-recommend">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="recommend-slide">
                                <div class="tour-slide__box">
                                    <a href=""><img src="{{ url('assets/frontend/img/t1.jpg') }}" alt="banner"></a>

                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide">
                            <div class="recommend-slide">
                                <div class="tour-slide__box">
                                    <a href=""><img src="{{ url('assets/frontend/img/t2.jpg') }}" alt="banner"></a>


                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide">
                            <div class="recommend-slide">
                                <div class="tour-slide__box">
                                    <a href=""><img src="{{ url('assets/frontend/img/t1.jpg') }}" alt="banner"></a>

                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide">
                            <div class="recommend-slide">
                                <div class="tour-slide__box">
                                    <a href=""><img src="{{ url('assets/frontend/img/t2.jpg') }}" alt="banner"></a>


                                </div>
                            </div>
                        </div>







                    </div>
                    <div class="swipper-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <div class="uk-width-1-3@l uk-width-3-3@m uk-width-3-3@s">
            <h3 class="uk-text-lead">Trending Now</h3>
            <div class="js-trending">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="game-card --horizontal">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/trending2.jpg') }}" alt="Alien Games" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Alien Games</a>
                                        <div class="game-card__genre">Warring factions have brought the Origin System to the brink of destruction.</div>
                                        <div class="game-card__rating-and-price">
                                            <div class="game-card__rating"><span>4.5</span><i class="ico_star"></i></div>
                                            <div class="game-card__price"><span>Free</span></div>
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
                        <div class="swiper-slide">
                            <div class="game-card --horizontal">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/trending3.jpg') }}" alt="Warframe" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Alien Games</a>
                                        <div class="game-card__genre">Warring factions have brought the Origin System to the brink of destruction.</div>
                                        <div class="game-card__rating-and-price">
                                            <div class="game-card__rating"><span>3.9</span><i class="ico_star"></i></div>
                                            <div class="game-card__price"><span>Free</span></div>
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
                        <div class="swiper-slide">
                            <div class="game-card --horizontal">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/trending.jpg') }}" alt="Warframe" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Alien Games</a>
                                        <div class="game-card__genre">Warring factions have brought the Origin System to the brink of destruction.</div>
                                        <div class="game-card__rating-and-price">
                                            <div class="game-card__rating"><span>4.2</span><i class="ico_star"></i></div>
                                            <div class="game-card__price"><span>$50</span></div>
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
                    <div class="swipper-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <div class="uk-width-1-1">
            <h3 class="uk-text-lead">Most Popular</h3>
            <div class="js-popular">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-1.jpg') }}" alt="Solitary Crusade" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Solitary Crusade</a>
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
                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-2.jpg') }}" alt="Immortal Tombs" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Immortal Tombs</a>
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
                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-3.jpg') }}" alt="Crush of Resitution" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> Crush of Resitution</a>
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


                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-7.jpg') }}" alt="Kill of Democracy" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html">Kill of Democracy</a>
                                        <div class="game-card__genre">Action / Adventure</div>
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

                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-4.jpg') }}" alt="Kill of Democracy" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html">Kill of Democracy</a>
                                        <div class="game-card__genre">Action / Adventure</div>
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

                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-5.jpg') }}" alt="Kill of Democracy" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html">Kill of Democracy</a>
                                        <div class="game-card__genre">Action / Adventure</div>
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


                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-6.jpg') }}" alt="Kill of Democracy" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html">Kill of Democracy</a>
                                        <div class="game-card__genre">Action / Adventure</div>
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
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endblock