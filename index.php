<?php require 'common.php'; ?>
<!doctype html>
<html>
<head>
    <!--
        JSON viewer v<?php echo JSON_VIEWER; ?>
        (c) 2008-<?php echo date("y"); ?> by Hay Kranen <http://www.haykranen.nl/projects/jsonviewer>
        Released under the MIT license, see jsonviewer.php
    -->
    <meta charset="utf-8" />
    <title>JSON viewer &raquo; haykranen.nl</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="js/jsonviewer.js"></script>
</head>
<body>

<div id="wrapper">
    <h1><a href="http://www.haykranen.nl/projects/jsonviewer">JSON Viewer</a></h1>
    <h2><a href="http://www.haykranen.nl"><em>by Hay Kranen</em></a></h2>

    <noscript>
        <h2>Javascript is required for the JSON viewer. Please enable Javascript in your browser!</h2>
    </noscript>

    <p>
        This is a simple <a href="http://www.json.org">JSON</a> viewer.
        To use it, simply enter an URL to the JSON file in the input field
        below here. If you don't have an URL but you want to view the results,
        simply press 'Get JSON' to view my recent Tweets from
        <a href="http://www.twitter.com/huskyr">Twitter</a>.
        This viewer is made by <a href="http://www.haykranen.nl">Hay Kranen</a>.
        Get the source code for this application (released under the MIT license)
        <a href="http://www.haykranen.nl/projects/jsonviewer">here</a>.
    </p>

    <fieldset>
        <legend>Enter JSON by</legend>

        <div>
            <button data-target="url" class="current">URL</button>
            <button data-target="data">Text</button>
        </div>

        <form method="post" action="" name="jsonviewer" id="jsonviewer">
            <div id="formurl" class="dataentry">
                <input type="text" size="160" name="url" id="url" value="" />
            </div>

            <div id="formdata" class="jshide dataentry">
                <textarea id="data" name="data" rows="15" cols="100"></textarea>
            </div>

            <button id="btnClear">Clear</button>

            <fieldset>
                <legend><a href="#options" id="toggle-options">Advanced options</a></legend>

                <div id="options" class="jshide">
                    <a name="options"></a>

                    <p>
                        <input type="checkbox" id="rawoutput" name="rawoutput">
                        <label for="rawoutput">Raw output? (faster for very large JSON files)</label>
                    </p>

                    <p>
                        <input type="checkbox" id="showinvalidjson" name="showinvalidjson" />
                        <label for="showinvalidjson">Show invalid JSON</label>
                    </p>
                </div>
            </fieldset>

            <input type="hidden" id="entrytype" name="entrytype" value="url" />
            <input type="submit" value="Submit" name="submit" id="submit" />
        </form>
    </fieldset>

    <hr />

    <img src="img/loading.gif" alt="Loading..." id="loading" class="jshide" />

    <button id="expandAll" class="jshide">Expand all nodes</button>
    <pre id="output">
        <?php
            // If JavaScript is disabled this will still show the results
            if (isset($_POST['url'])) {
                require 'jsonviewer.php';
                echo json_viewer($_POST['url']);
            }
        ?>
    </pre>

    <a href="http://www.haykranen.nl" style="width:200px;margin:0 auto;display:block;">
        <img src="http://static.haykranen.nl/common/hknl_button_200.png" alt="" style="border:0;"/>
    </a>
</div> <!-- /wrapper -->
</body>
</html>