@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <h2>Data report.</h2>

        <div>
            <div class="static_card report">
                <div class="section">
                    <div>
                        Registered users:
                        <?php echo count($users)?>.
                        <br><br>
                        <?php foreach ($users_by_gender as $user_by_gender) {
        $percent = number_format(($user_by_gender->user_count / count($users) * 100), 2);
        echo $user_by_gender->gender . " " . $user_by_gender->user_count . " users. " . $percent . "%<br>";
    }?>
                        <br>
                    </div>
                    <div>
                        <canvas id="users_by_gender_pie_chart"></canvas>
                    </div>
                </div>
                <div class="section">
                    <div>
                        <?php echo "Average user age: " . $users_avg_age . " years."?>
                        <br><br>
                        Users by age:<br>
                        <?php foreach ($generations as $gen => $count) {
        echo $gen . " years: " . $count . " users.<br>";
    }?>
                        <br>
                    </div>
                    <div>
                        <canvas id="users_by_age_bar_chart"></canvas>
                    </div>
                </div>
                <div class="section">
                    <div>
                        Top 5 countries with users:<br><br>
                        <?php $i = 0;
    foreach ($users_by_country as $user_by_country) {
        echo $user_by_country->country . ": " . $user_by_country->user_count . " users. " . $string_users_country_gender[$i];
        ++$i;
    }?>
                        <br>
                        Social:<br><br>
                        <?php echo count($followings) . " follows between users.";?>
                        <br>
                        <?php echo "The " . $mutuals_percent . "% of them (" . $mutuals . ") are mutuals.";?>
                    </div>
                    <div>
                        <canvas id="users_by_country_pie_chart"></canvas>
                    </div>
                </div>
            </div>
            <br>
            <div class="static_card report">
                <div class="section">
                    <div>
                        <?php echo count($albums)?> albums in total.<br><br>
                        Albums by decades:<br><br>
                        <?php foreach ($decades as $decade => $count) {
        echo $decade . " years: " . $count . " albums.<br>";
    }?>
                        <br>
                    </div>
                    <div>
                        <canvas id="albums_by_decade_bar_chart"></canvas>
                    </div>
                </div>
                <div class="section">
                    <div>
                        <?php echo count($lists)?> lists with an average length of
                        <?php echo $avg_list_length?> albums.
                    </div>
                    <div>
                    </div>
                </div>
                <div class="section">
                    <div>
                        <?php echo count($reviews)?> reviews in total with an average rating of
                        <?php echo $reviews_avg_rating?>.<br><br>
                        <?php echo $string_avg_rating_gender?>
                    </div>
                    <div>
                    </div>
                </div>
            </div>

        </div>
        <br>
        <div class="static_card report">
            <div class="section">
                <div>
                    Top 5 most featured album genres:<br><br>
                    <?php foreach ($genres as $genre) {
        echo $genre->name . " " . $genre->genre_count . " albums.<br>";
    }?>
                </div>
                <div>
                    <canvas id="genres_pie_chart"></canvas>
                </div>
            </div>
        </div>
        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        Chart.defaults.color = 'rgb(255, 255, 255)';
        Chart.defaults.backgroundColor = 'rgb(255, 255, 255)';
        Chart.defaults.borderColor = 'rgb(255, 255, 255)';

        const users_by_gender_pie_chart = document.getElementById('users_by_gender_pie_chart');

        new Chart(users_by_gender_pie_chart, {
            type: 'pie',
            data: {
                labels: [
                    '<?php echo $users_by_gender[0]->gender;?>',
                    '<?php echo $users_by_gender[1]->gender;?>',
                    '<?php echo $users_by_gender[2]->gender;?>'
                ],
                datasets: [{
                    label: 'Users by gender',
                    data: [
                        '<?php echo $users_by_gender[0]->user_count;?>',
                        '<?php echo $users_by_gender[1]->user_count;?>',
                        '<?php echo $users_by_gender[2]->user_count;?>'
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Users by gender:'
                    }
                }
            },
        });

        const users_by_age_bar_chart = document.getElementById('users_by_age_bar_chart');

        const DATA_COUNT = 6;
        const NUMBER_CFG = { count: DATA_COUNT, min: 0, max: 100 };

        const labels = ['<20', '20-30', '30-40', '40-50', '50-60', '<60'];

        new Chart(users_by_age_bar_chart, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Users by age range (years):',
                        data: [
                            <?php echo $generations['Under 20'] ?>,
                            <?php echo $generations['20-30'] ?>,
                            <?php echo $generations['30-40'] ?>,
                            <?php echo $generations['40-50'] ?>,
                            <?php echo $generations['50-60'] ?>,
                            <?php echo $generations['More than 60'] ?>,
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                    }
                }
            },
        });

        const users_by_country_pie_chart = document.getElementById('users_by_country_pie_chart');


        new Chart(users_by_country_pie_chart, {
            type: 'pie',
            data: {
                labels: [
                    '<?php echo $users_by_country[0]->country;?>',
                    '<?php echo $users_by_country[1]->country;?>',
                    '<?php echo $users_by_country[2]->country;?>',
                    '<?php echo $users_by_country[3]->country;?>',
                    '<?php echo $users_by_country[4]->country;?>',
                ],
                datasets: [{
                    label: 'Users by gender',
                    data: [
                        '<?php echo $users_by_country[0]->user_count;?>',
                        '<?php echo $users_by_country[1]->user_count;?>',
                        '<?php echo $users_by_country[2]->user_count;?>',
                        '<?php echo $users_by_country[3]->user_count;?>',
                        '<?php echo $users_by_country[4]->user_count;?>',
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Users by country - Top 5:'
                    }
                }
            },
        });

        const albums_by_decade_bar_chart = document.getElementById('albums_by_decade_bar_chart');

        new Chart(albums_by_decade_bar_chart, {
            type: 'bar',
            data: {
                labels: ['1960s', '1970s', '1980s', '1990s', '2000s', '2010s', '2020s'],
                datasets: [
                    {
                        label: 'Albums by decade:',
                        data: [
                            <?php echo $decades['1960s'] ?>,
                            <?php echo $decades['1970s'] ?>,
                            <?php echo $decades['1980s'] ?>,
                            <?php echo $decades['1990s'] ?>,
                            <?php echo $decades['2000s'] ?>,
                            <?php echo $decades['2010s'] ?>,
                            <?php echo $decades['2020s'] ?>
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)',
                            'rgba(230, 162, 86, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)',
                            'rgb(230, 162, 86)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                    }
                }
            },
        });

        const genres_pie_chart = document.getElementById('genres_pie_chart');

        new Chart(genres_pie_chart, {
            type: 'pie',
            data: {
                labels: [
                    '<?php echo $genres[0]->name;?>',
                    '<?php echo $genres[1]->name;?>',
                    '<?php echo $genres[2]->name;?>',
                    '<?php echo $genres[3]->name;?>',
                    '<?php echo $genres[4]->name;?>',
                ],
                datasets: [{
                    label: 'Most featured genres.',
                    data: [
                        '<?php echo $genres[0]->genre_count;?>',
                        '<?php echo $genres[1]->genre_count;?>',
                        '<?php echo $genres[2]->genre_count;?>',
                        '<?php echo $genres[3]->genre_count;?>',
                        '<?php echo $genres[4]->genre_count;?>',
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Most featured genres:'
                    }
                }
            },
        });

    </script>


@endsection