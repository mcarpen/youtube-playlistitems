<?php
require __DIR__ . '/vendor/autoload.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            padding: 50px;
        }

        button {
            padding: 10px;
        }

        #results {
            display: none;
        }

        #results.active {
            display: block;
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border: 5px solid transparent;
            border-top-color: #555;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
<form action="#" id="playlist-url">
    <label>
        Playlist url<br>
        <input type="text" name="url" required>
    </label>
    <input type="submit" value="Submit">
</form>
<div id="results">
    <p id="results-number"></p>
    <p id="text"></p>
    <div class="tooltip">
        <button id="copy-ids">
            <span class="tooltiptext" id="tooltip">Copy to clipboard</span>
            Copy ids
        </button>
    </div>
</div>
</body>
<footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

    <script type="text/javascript">
        const copyBtn = document.querySelector('#copy-ids');
        const tooltip = document.getElementById("tooltip");

        copyBtn.addEventListener('click', function() {
            const text = document.querySelector('#text');
            const range = document.createRange();
            window.getSelection().removeAllRanges();
            range.selectNode(text);
            window.getSelection().addRange(range);

            try {
                const successful = document.execCommand('Copy');
                const msg = successful ? 'successful' : 'unsuccessful';
                tooltip.innerHTML = "Copied";
                setTimeout(function () {
                    tooltip.innerHTML = "Copy to clipboard";
                }, 2500);
                console.log('Copy text command was ' + msg);
            } catch(err) {
                console.log('Oops, unable to copy');
            }
            window.getSelection().removeAllRanges();
        });

        const $form = $('#playlist-url');

        $form.submit(function(e) {
            e.preventDefault();

            const $results = $('#results');
            const $resultsNumber = $('#results-number');
            const $text = $('#text');

            if ($results.hasClass('active')) {
                $resultsNumber.html('');
                $text.html('Loading...')
            }

            const serialize = $form.serialize();

            $.ajax({
                type: 'GET',
                dataType: 'html',
                url: 'ajaxCall.php',
                data: serialize,
                success: function (data) {
                    const $data = JSON.parse(data);

                    $results.addClass('active');
                    $resultsNumber.html($data.totalResults);
                    $text.html($data.videosIds);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
                }
            });
        })
    </script>
</footer>
</html>
