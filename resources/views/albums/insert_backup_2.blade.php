@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div>
            <h2>INSERT ALBUM</h2>

            <!-- <form method="POST" action="{{ route('inserted_album') }}"> -->
            <form method="POST" action="">

                @csrf

                <div>
                    <label for="album_name">Album name:</label>
                    <input type="text" name="album_name" id="album_name" required>
                </div>

                <div>
                    <label for="artist_name">Artist name:</label>
                    <input type="text" name="artist_name" id="artist_name" required>
                </div>

                <div>
                    <label for="release_year">Release year:</label>
                    <select name="release_year" id="release_year">
                        <?php for ($i = 1900; $i <= 2025; $i++): ?>
                        <option value="<?php    echo $i; ?>">
                            <?php    echo $i; ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label for="cover">cover:</label>
                    <input type="text" name="cover">
                </div>

                <div>
                    <label for="genre">Genres:</label>
                    <input type="text" name="genre">
                </div>

                <div>
                    <!-- <input type="submit" name="insert_album" id="insert_album" value="Insert album"> -->
                    <input type="button" name="insert_album" id="insert_album" value="Insert album">
                </div>


            </form>

            <div id="search_results">
                <div>Suggested albums:</div>
                <div id="suggested_albums"></div>
            </div>
        </div>
    </main>

    <script>
        const insert_album = document.querySelector("#insert_album");

        /*https://api.discogs.com/database/search?q=&type=release&release_title=blackening&artist=machine&key=YwWCSQkRamXqBJPQSsxs&secret=cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU */

        insert_album.addEventListener("click", checkboxClick, false);

        const api_key = 'YwWCSQkRamXqBJPQSsxs';

        const api_secret = 'cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU';




        async function checkboxClick() {

            const album_name = document.querySelector("#album_name").value;
            const artist_name = document.querySelector("#artist_name").value;

            /*Formateo album_name para que quede bonito*/
            /*NO DEBE SER NECESARIO*/

            // var words = "";
            // words = album_name.split(" ");

            // for (let i = 0; i < words.length; i++) {
            //     words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            // }

            // const album_cool = words.join(" ");

            // console.log("album cool: " + album_cool);

            /*-----------------------*/

            /*Formateo album_name para api*/

            var album_coded = album_name.toLowerCase();
            album_coded = album_coded.replaceAll(" ", "-");

            console.log("album coded: " + album_coded);

            /*Formateo artist_name para que quede bonito*/
            /*NO DEBE SER NECESARIO*/
            // words = artist_name.split(" ");

            // for (let i = 0; i < words.length; i++) {
            //     words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            // }

            // const artist_cool = words.join(" ");

            // console.log("artist cool: " + artist_cool);
            /*---------------------*/

            /*Formateo artist_name para api*/

            var artist_coded = artist_name.toLowerCase();
            artist_coded = artist_coded.replaceAll(" ", "-");

            console.log("artist coded: " + artist_coded);


            const api_url_1 = `https://api.discogs.com/database/search?q=&type=release&release_title=${album_coded}&artist=${artist_coded}&key=${api_key}&secret=${api_secret}`;

            // console.log("quieto pájaro! estoy buscando");

            // var release_id = "";

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
                    // console.log(response);
                    // console.log(response['data']['results'][0]['title']);
                    // console.log(response['data']['results'][0]['id']);
                    // release_id = response['data']['results'][0]['id'];
                    /*****************/
                    // top_5_results = "";

                    for (i = 0; i < 5; i++) {
                        ids_array.push(response['data']['results'][i]['id']);
                        covers_array.push(response['data']['results'][i]['cover_image']);
                    }

                    // for (i = 0; i < 5; i++) {
                    //     await fetch(`https://api.discogs.com/releases/${response['data']['results'][i]['id']}`)
                    //         .then(response => {
                    //             if (!response.ok) {
                    //                 throw new Error('Network response was not ok');
                    //             }
                    //             return response.json();
                    //         })
                    //         .then(data => {
                    //             console.log("Iteración " + i + ":");
                    //         })
                    //         .catch(error => {
                    //             console.error('Error:', error);
                    //         });

                    //     resultado = `<div><div>${response['data']['results'][i]['title']}</div><img width="200px" src=\"${response['data']['results'][i]['cover_image']}\"><div>Songs:</div><div>`;
                    //     top_5_results = top_5_results + resultado;
                    // }
                    // $("#suggested_albums").html(top_5_results);

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
                        // console.log(data);
                        // console.log("Primera canción:");
                        // console.log(data['tracklist'][0]['title']);
                        // console.log("Songs:");
                        // console.log(data['tracklist'].length);
                        id = data['id'];
                        artist = data['artists'][0]['name'];
                        title = data['title'];
                        tracklist = data['tracklist'];
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                // resultado = '<div id="suggested_album"><form method="POST" action=" {{ route('selected_album') }} ">@csrf<label for="artist">Discogs ID: </label><input type="text" name="id" value="' + id + '" readonly><label for="artist">Artist: </label><input type="text" name="artist" value="' + artist + '" readonly><br><label for="title">Title</label><input type="text" name="title" value="' + title + '" readonly><br><label for="cover">Cover:</label><input type="text" name="cover" value="' + covers_array[i] + '"><br><img width="200px" src="' + covers_array[i] + '"><br> Tracklist:<br>';
                resultado = `<div class="suggested_album" id="` + i + `"><form method="POST" action="">@csrf<label for="artist">Discogs ID: </label><input type="text" name="id" value="` + id + `" readonly><label for="artist">Artist: </label><input type="text" name="artist" value="` + artist + `" readonly><br><label for="title">Title</label><input type="text" name="title" value="` + title + `" readonly><br><label for="cover">Cover:</label><input type="text" name="cover" value="` + covers_array[i] + `"><br><img width="200px" src="` + covers_array[i] + `"><br> Tracklist:<br>`;

                resultado = resultado + '<table><tr><th>Track</th><th>Title</th><th>Duration</th>';
                for (j = 0; j < tracklist.length; j++) {
                    resultado = resultado + '<tr><td>' + tracklist[j]['position'] + '</td><td>' + tracklist[j]['title'] + '</td><td>' + tracklist[j]['duration'] + '</td></tr>';
                };
                // resultado = resultado + '</table><input type="submit" value="Select album"></form></div>';
                resultado = resultado + '</table><input type="button" class="copy_data" value="Copy data." id="' + i + '"></form></div>';
                // resultado = '<div>' + artist + '</div>';
                top_5_results = top_5_results + resultado;
            }
            $("#suggested_albums").html(top_5_results);

            /******************/
            const copy_data = document.querySelectorAll(".copy_data");

            copy_data.addEventListener("click", copyData, false);

            function copyData() {
                // var title = this.querySelector("#title").value;
                // alert(title);
                // alert("aló");
                alert(this.id);
            }
            /******************/


            // const api_url_2 = `https://api.discogs.com/releases/${release_id}`;
            // // const api_url_2 = `https://api.discogs.com/releases/1725299`;

            // fetch(api_url_2)
            //     .then(response => {
            //         if (!response.ok) {
            //             throw new Error('Network response was not ok');
            //         }
            //         return response.json();
            //     })
            //     .then(data => {
            //         console.log(data);
            //         console.log("Sacando de ajax2 la primera canción para testeo:");
            //         console.log(data['tracklist'][0]['title']);
            //     })
            //     .catch(error => {
            //         console.error('Error:', error);
            //     });



            // $.ajax({
            //     url: api_url_2,
            //     method: 'GET',
            //     type: 'GET',
            //     dataType: 'jsonp',
            //     CORS: true,
            //     contentType: 'text/javascript',
            //     secure: true,
            //     headers: {
            //         'Access-Control-Allow-Origin': '*',
            //         'X-Content-Type-Options': 'nosniff'
            //     },
            //     success: function (response) {
            //         console.log("Hemos llegado al segundo ajax, al menos");
            //         console.log("Sacando de ajax2 la primera canción para testeo:");
            //         console.log(response['tracklist'][0]['title']);
            //     },
            //     error: function (xhr, status, error) {
            //         console.log("Ajax 2 ha fallado");
            //         console.log(xhr);
            //         console.log(status);
            //         console.log(error);
            //     }
            // });




            /*************************/


            // const options = {
            //     method: 'GET',
            //     headers: {
            //         'Access- Control - Allow - Origin: *'
            //     }
            // };

            // fetch(api_url_1, options)
            //     .then(response => {
            //         if (response.ok) {
            //             return response.json();
            //         } else {
            //             throw new Error('API request failed');
            //         }
            //     })
            //     .then(data => {
            //         console.log(data["results"][0]["id"]);
            //         console.log(data["results"][0]["title"]);
            //         release_id = data["results"][0]["id"];
            //     })
            //     .catch(error => {
            //         console.error(error); 
            //     });


            // const api_url_2 = `https://api.discogs.com/releases/${release_id}`;

            // fetch(api_url_2, options)
            //     .then(response => {
            //         if (response.ok) {
            //             return response.json(); 
            //         } else {
            //             throw new Error('API request failed');
            //         }
            //     })
            //     .then(data => {
            //         console.log(data);
            //     })
            //     .catch(error => {
            //         console.error(error); 
            //     });

            // event.preventDefault();

        }

    </script>

@endsection