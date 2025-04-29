@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div class="static_card" style="padding: 0.2vh 3vh;">
            <h2>Insert album:</h2>

            <form method="GET" action=""
                style="display: grid; grid-template-columns: 1fr 1fr 0.5fr 0.7fr; column-gap: 10px; padding: 0.5vh 1vh 1vh;">
                @csrf
                <label for="album_name">&nbsp&nbsp Album name:</label>
                <label for="artist_name">&nbsp&nbsp Artist name:</label>
                <span></span>
                <span></span>

                <input type="text" name="album_name" id="album_name" class="search" style="width: 100%;" required>
                <input type="text" name="artist_name" id="artist_name" class="search" style="width: 100%;" required>
                <input type="button" name="search_album" id="search_album" class="round_button" value="Search album">
                <input type="button" name="search_album" id="show_more_results" class="round_button"
                    value="Show more results" style="visibility: hidden; opacity: 0;">
            </form>
        </div>

        <div id="spinner" style="display: none;">
            <div class="loader"></div>
        </div>

        <div id="datos"></div>

        <div id="suggested_message"
            style="opacity: 0; transition: opacity ease 0.5s; text-align: center; padding: 0.5vh 3vh; margin-top: 2vh;"
            class="static_card">
            <h2 id="suggested_albums_header">Suggested albums:</h2>
        </div>

        <div id="suggested_albums"
            style="display: grid; grid-template-rows: 1fr 1fr 1fr 1fr 1fr; gap: 0.5%; margin-top: -1vh;">
        </div>

    </main>

    <script>

        let iterator = 0;

        const search_album = document.querySelector("#search_album");
        const show_more_results = document.querySelector("#show_more_results");
        const suggested_albums_header = document.querySelector("#suggested_albums_header");

        search_album.addEventListener("click", search, false);
        show_more_results.addEventListener("click", more_results, false);


        search_album.addEventListener("click", showMoreResultsButton, false);
        search_album.addEventListener("click", search, false);

        function showMoreResultsButton() {
            show_more_results.style.visibility = "visible";
            show_more_results.style.opacity = "1";
            show_more_results.addEventListener("click", more_results, false);
        }

        const api_key = 'YwWCSQkRamXqBJPQSsxs';

        const api_secret = 'cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU';

        async function callAPI() {
            spinner.style.display = 'block';
            suggested_albums.innerHTML = '';

            var album_name = document.querySelector("#album_name").value;
            var artist_name = document.querySelector("#artist_name").value;

            console.log('Iterador (buscar más):' + iterator);

            document.getElementById('suggested_message').style.opacity = '1';

            var album_coded = album_name.toLowerCase();
            album_coded = album_coded.replaceAll(" ", "-");

            console.log("album coded: " + album_coded);

            var artist_coded = artist_name.toLowerCase();
            artist_coded = artist_coded.replaceAll(" ", "-");

            console.log("artist coded: " + artist_coded);

            const api_url_1 = `https://api.discogs.com/database/search?q=&type=release&release_title=${album_coded}&artist=${artist_coded}&format=album&key=${api_key}&secret=${api_secret}`;


            console.log(api_url_1);

            var resultado = '';

            var top_5_results = "";

            $.ajaxSetup({
                beforeSend: function (request) {
                    request.setRequestHeader("User-Agent", "felmunpen_app_student");
                }
            });

            var ids_array = [];
            var covers_array = [];

            await $.ajax({
                url: api_url_1,
                method: 'GET',
                type: 'GET',
                dataType: 'jsonp',
                CORS: true,
                contentType: 'application/json',

                secure: true,
                headers: {
                    'Access-Control-Allow-Origin': '*',
                },

                success: function (response) {
                    for (i = 0; i < 5; i++) {
                        ids_array.push(response['data']['results'][i + 5 * iterator]['id']);
                        covers_array.push(response['data']['results'][i + 5 * iterator]['cover_image']);
                        console.log(response['data']['results'][i + 5 * iterator]['cover_image']);
                    }
                },
                error: function (xhr, status, error) {
                }
            });

            var results = ids_array.length;

            var api_url_2;

            console.log(ids_array);

            var artist;
            var title;
            var tracklist;
            var cover;
            var year;
            var genres;

            for (i = 0; i < ids_array.length; i++) {

                api_url_2 = 'https://api.discogs.com/releases/' + ids_array[i];
                await fetch(api_url_2)

                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        spinner.style.display = 'none';

                        id = data['id'];
                        artist = data['artists'][0]['name'];
                        title = data['title'];
                        tracklist = data['tracklist'];
                        year = data['year'];
                        genres = []

                        genres_number = 0;
                        genres_number += data['genres'].length;
                        for (j = 0; j < genres_number; j++) {
                            genres.push(data['genres'][j])
                        }
                        genres_number = 0;
                        genres_number += data['styles'].length;
                        for (k = 0; k < genres_number; k++) {
                            genres.push(data['styles'][k])
                        }

                        spinner.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        spinner.style.display = 'none';
                    })


                resultado = '<div class="album_card suggested_album static_card"><div><img class="album_cover" src="' + covers_array[i] + '"><form style="text-align:center;" method="POST" action=" {{ route('selected_album') }} ">@csrf <input type="hidden" name="id" value="' + id + '" readonly><br><input type="submit" class="button_1" value="Insert new album in database."></form></div><div class="clear" style="padding: 5%;">Artist: ' + artist + '<br>Title: ' + title + '<br>Year: ' + year + '<br>Genres: ' + genres.join(", ") + '.<br><br>';


                resultado = resultado + '<table class="tracklist"><tr><th>Track</th><th>Title</th><th>Duration</th>';
                for (j = 0; j < tracklist.length; j++) {
                    resultado = resultado + '<tr><td>' + tracklist[j]['position'] + '</td><td>' + tracklist[j]['title'] + '</td><td>' + tracklist[j]['duration'] + '</td></tr>';
                };
                resultado = resultado + '</table></div>';

                resultado = resultado + '</div>';

                top_5_results = top_5_results + resultado;

            };

            $("#suggested_albums_header").html("Suggested albums: " + results + " results.")
            $("#suggested_albums").html(top_5_results);

        }

        function search() {
            iterator = 0;
            callAPI();
        }

        function more_results() {
            iterator += 1;
            callAPI();
        }


    </script>

@endsection