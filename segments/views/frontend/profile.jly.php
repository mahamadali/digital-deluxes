@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("content")
<main class="page-main">
                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width-2-3@l">
                        <div class="widjet --profile">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Profile</h3>
                            </div>
                            <div class="widjet__body">
                                <div class="user-info">
                                    <div class="user-info__avatar"><img src="{{ url('assets/frontend/img/profile.png') }}" alt="profile"></div>
                                    <div class="user-info__box">
                                        <div class="user-info__title">John Doe</div>
                                        <div class="user-info__text">Egypt, Member since May 2022</div>
                                    </div>
                                </div><a class="uk-button uk-button-danger" href="04_profile.html"><i class="ico_edit"></i><span class="uk-margin-small-left">Edit Profile</span></a>
                            </div>
                        </div>
                        <div class="widjet --bio">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Bio</h3>
                            </div>
                            <div class="widjet__body"><span>Here you can put your biography you need try to make it attractive and professional, just be honest and polite.</span></div>
                        </div>
                        <div class="widjet --activity">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Recent Activity</h3><a href="04_profile.html">View All</a>
                            </div>
                            <div class="widjet__body">
                                <div class="widjet-game">
                                    <div class="widjet-game__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-2.jpg') }}" alt="image"></a></div>
                                    <div class="widjet-game__info"><a class="widjet-game__title" href="10_game-profile.html"> Chrome Fear</a>
                                        <div class="widjet-game__record">3 hours on record</div>
                                        <div class="widjet-game__last-played">last played on 18 Feb, 2022</div>
                                    </div>
                                </div>
                                <div class="widjet-game-info">
                                    <div class="widjet-game-info__title">Achievement Progress</div>
                                    <div class="widjet-game-info__progress"><span>50 of 150</span>
                                        <div class="progress-box">
                                            <div class="progress-line" style="width: 80%"></div>
                                        </div>
                                    </div>
                                    <div class="widjet-game-info__acheivement">
                                        <ul>
                                            <li><img src="{{ url('assets/frontend/img/acheivement-1.png') }}" alt="acheivement"></li>
                                            <li><img src="{{ url('assets/frontend/img/acheivement-2.png') }}" alt="acheivement"></li>
                                            <li><img src="{{ url('assets/frontend/img/acheivement-3.png') }}" alt="acheivement"></li>
                                            <li><img src="{{ url('assets/frontend/img/acheivement-4.png') }}" alt="acheivement"></li>
                                            <li><img src="{{ url('assets/frontend/img/acheivement-5.png') }}" alt="acheivement"></li>
                                            <li><span>+10</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="widjet__body">
                                <div class="widjet-game">
                                    <div class="widjet-game__media"><a href="10_game-profile.html"><img src="{{ url('assets/frontend/img/game-3.jpg') }}" alt="image"></a></div>
                                    <div class="widjet-game__info"><a class="widjet-game__title" href="10_game-profile.html"> Retaliate of Prosecution</a>
                                        <div class="widjet-game__record">0.2 hours on record</div>
                                        <div class="widjet-game__last-played">last played on 25 Apr, 2022</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-3@l">
                        <div class="widjet --upload">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Upload Item</h3>
                            </div>
                            <div class="widjet__body"><select class="js-select uk-flex-1">
                                    <option value="">Select a Category</option>
                                    <option value="Category 1">Category 1</option>
                                    <option value="Category 2">Category 2</option>
                                    <option value="Category 3">Category 3</option>
                                </select><button class="uk-button uk-button-secondary" type="button">Next</button></div>
                        </div>
                        <div class="widjet --badges">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Badges</h3><a href="04_profile.html">View All</a>
                            </div>
                            <div class="widjet__body">
                                <ul class="badges-list">
                                    <li><img src="{{ url('assets/frontend/img/badge-1.png') }}" alt="badge"></li>
                                    <li><img src="{{ url('assets/frontend/img/badge-2.png') }}" alt="badge"></li>
                                    <li><img src="{{ url('assets/frontend/img/badge-3.png') }}" alt="badge"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="widjet --games">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Games</h3><a href="04_profile.html">View All</a>
                            </div>
                            <div class="widjet__body">
                                <ul class="games-list">
                                    <li><img src="{{ url('assets/frontend/img/game-1.jpg') }}" alt="game"></li>
                                    <li><img src="{{ url('assets/frontend/img/game-2.jpg') }}" alt="game"></li>
                                    <li><img src="{{ url('assets/frontend/img/game-3.jpg') }}" alt="game"></li>
                                    <li><img src="{{ url('assets/frontend/img/game-4.jpg') }}" alt="game"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
@endblock