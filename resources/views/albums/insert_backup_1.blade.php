@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div>
            <h2>INSERT ALBUM</h2>

            <!-- <form method="POST" action="{{ route('inserted_album') }}"> -->
            <form method="POST" action="">
                @csrf
                BUSCAR UN ÁLBUM EN DISCOGS
                <div>
                    <label for="album_name">Album name:</label>
                    <input type="text" name="album_name" id="album_name" required>
                </div>

                <div>
                    <label for="artist_name">Artist name:</label>
                    <input type="text" name="artist_name" id="artist_name" required>
                </div>

                <div>
                    <input type="button" name="search_album" id="search_album" value="Insert album">
                </div>
            </form>

            <div id="search_results">
                <div>Suggested albums:</div>
                <div id="suggested_albums">
                </div>
            </div>
        </div>
    </main>

    <script>
        const search_album = document.querySelector("#search_album");

        /*https://api.discogs.com/database/search?q=&type=release&release_title=blackening&artist=machine&key=YwWCSQkRamXqBJPQSsxs&secret=cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU */

        search_album.addEventListener("click", searchAPI, false);

        const api_key = 'YwWCSQkRamXqBJPQSsxs';

        const api_secret = 'cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU';

        async function searchAPI() {

            const album_name = document.querySelector("#album_name").value;
            const artist_name = document.querySelector("#artist_name").value;

            /*Formateo album_name para api*/

            var album_coded = album_name.toLowerCase();
            album_coded = album_coded.replaceAll(" ", "-");

            console.log("album coded: " + album_coded);

            var artist_coded = artist_name.toLowerCase();
            artist_coded = artist_coded.replaceAll(" ", "-");

            console.log("artist coded: " + artist_coded);

            const api_url_1 = `https://api.discogs.com/database/search?q=&type=release&release_title=${album_coded}&artist=${artist_coded}&key=${api_key}&secret=${api_secret}`;

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
                        ids_array.push(response['data']['results'][i]['id']);
                        covers_array.push(response['data']['results'][i]['cover_image']);
                    }
                },
                error: function (xhr, status, error) {
                }
            });

            var api_url_2;

            console.log(ids_array);

            var artist;
            var title;
            var tracklist;
            var cover;
            var year;

            for (i = 0; i < 5; i++) {
                api_url_2 = 'https://api.discogs.com/releases/' + ids_array[i];
                console.log('Control de id. Iteración: ' + i);
                console.log('ID: ' + ids_array[i]);
                await fetch(api_url_2)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        id = data['id'];
                        artist = data['artists'][0]['name'];
                        title = data['title'];
                        tracklist = data['tracklist'];
                        year = data['year'];
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                resultado = '<div id="suggested_album"><form method="POST" action=" {{ route('selected_album') }} ">@csrf<label for="artist">Artist: </label><input type="text" name="artist" value="' + artist + '" readonly><label for="title">Title</label><input type="text" name="title" value="' + title + '" readonly><label for="year">Year</label><input type="number" name="year" value="' + year + '" readonly><label for="cover">Cover:</label><input type="text" name="cover" value="' + covers_array[i] + '"><img width="200px" src="' + covers_array[i] + '"><br>';
                // resultado = `<div class="suggested_album" id="` + i + `"><form method="POST" action="">@csrf<label for="artist">Discogs ID: </label><input type="text" name="id" value="` + id + `" readonly><label for="artist">Artist: </label><input type="text" name="artist" value="` + artist + `" readonly><br><label for="title">Title</label><input type="text" name="title" value="` + title + `" readonly><br><label for="cover">Cover:</label><input type="text" name="cover" value="` + covers_array[i] + `"><br><img width="200px" src="` + covers_array[i] + `"><br> Tracklist:<br>`;

                resultado = resultado + '<table><tr><th>Track</th><th>Title</th><th>Duration</th>';
                for (j = 0; j < tracklist.length; j++) {
                    resultado = resultado + '<tr><td>' + tracklist[j]['position'] + '</td><td>' + tracklist[j]['title'] + '</td><td>' + tracklist[j]['duration'] + '</td></tr>';
                };
                resultado = resultado + '</table><input type="submit" value="Select album"></form></div>';
                // resultado = resultado + '</table><input type="button" class="copy_data" value="Copy data." id="' + i + '"></form></div>';
                top_5_results = top_5_results + resultado;
            }
            $("#suggested_albums").html(top_5_results);

            /******************/
            // var copy_data = document.querySelectorAll(".copy_data");

            // for (var i = 0; i < copy_data.length; i++) {
            //     copy_data[i].addEventListener("click", copyData, false);
            // }

            // function copyData() {
            //     alert(this.id);
            // }
            /******************/


        }

    </script>

@endsection