<?php
    require 'common.php';
    /*
        JSON Viewer 1.6.1
        Copyright (c) 2008-2009, Hay Kranen <http://www.haykranen.nl/projects/jsonviewer/>

        This software includes jQuery, (c) 2009 by John Resig and others, which
        is also released under the MIT license

        JSON Viewer is Released under the MIT license, reproduced here below

        Permission is hereby granted, free of charge, to any person
        obtaining a copy of this software and associated documentation
        files (the "Software"), to deal in the Software without
        restriction, including without limitation the rights to use,
        copy, modify, merge, publish, distribute, sublicense, and/or sell
        copies of the Software, and to permit persons to whom the
        Software is furnished to do so, subject to the following
        conditions:

        The above copyright notice and this permission notice shall be
        included in all copies or substantial portions of the Software.

        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
        EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
        OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
        NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
        HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
        WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
        FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
        OTHER DEALINGS IN THE SOFTWARE.
    */

    function make_tree($var) {
        global $tree;
        foreach ($var as $key => $value) {
            if (is_array($value)) {
                // Check if the value is empty, show 'empty' arrow then
                if (empty($value)) {
                    $arrow = "arrow_open";
                    $title = "This node has no children";
                    $class = 'arrow empty';
                } else {
                    $arrow = "arrow";
                    $title = "Click on the arrow to view its children";
                    $class = 'arrow children';
                }

                $tree .= '<li><img src="img/' . $arrow . '.png" class="' . $class .'" ' .
                         'alt="+" title="' . $title .'" />'.$key."\t<ul>";

                make_tree($value);
            } else {
                $tree .= '<li><img src="img/mark.png" alt="-" />'.$key."<br />$value</li>\n";
            }
        }
        $tree .= "</ul></li>";
        return $tree;
    }

    function json_viewer($json) {
        // Some API's deliver invalid JSON, such as Flickr and Google Suggest,
        // usually because they deliver the JSON as a JavaScript variable, e.g.
        // 'JSONResult = ({"foo": "bar"});
        // We can 'fix' this by stripping that out before and after
        // the { and [ characters. Unfortunately that takes a little bit of
        // extra code, and we are essentialy fixing things that Google or Yahoo
        // should fix, but because these feeds are so popular we do it anyway.

        // get first occurence of curly brace and square brace
        $curly  = strpos($json, "{");
        $square = strpos($json, "[");

        // No curly or square bracket means this is not JSON data
        if ( ($curly === false) && ($square === false) ) {
            return "Invalid JSON data (no '{' or '[' found)";
        } else {
            // There is a case when you have a feed with [{
            // so get the first one
            if (($curly !== false) && ($square !== false)) {
                if ($curly < $square) {
                    $square = false;
                } else {
                    $curly  = false;
                }
            }

            // get the last curly or square brace
            if($curly !== false) {
                $firstchar = $curly;
                $lastchar  = strrpos($json, "}");
            } else if ($square !== false) {
                $firstchar = $square;
                $lastchar  = strrpos($json, "]");
            }

            if ($lastchar === false) {
                return "Invalid JSON data (no closing '}' or ']' found)";
            }

            // Give warning if $firstchar is not the first character
            if ($firstchar > 0) {
                $warning  = "---WARNING---\n";
                $warning .= "Invalid JSON data that does not begin with '{' or '[' might give unexpected results\n";
            }
        }
        // get the JSON data between the first and last curly or square brace
        $json = substr($json, $firstchar, ($lastchar - $firstchar) + 1);

        // decode json data
        $data = json_decode($json, true);

        if (!$data) {
            if (isset($_POST['showinvalidjson'])) {
                // Show invalid JSON anyway, do sanitize some stuff
                return "This JSON data is invalid, but we show it anyway: <br />" .
                        htmlentities($json);
            } else {
                return "Invalid JSON data (could not decode JSON data)";
            }
        }

        if (isset($warning)) {
            echo $warning;
        }

        // Check for 'raw output'
        if(isset($_POST['rawoutput'])) {
            die(print_r($data,false));
        }

        // we need to make the first 'root' tree element
        $out  = '<ul id="root"><li><img src="img/arrow.png" class="arrow" alt="+" />ROOT<ul id="first">';
        $out .= make_tree($data);
        $out .= "</ul></li></ul>";

        $tree = '';
        return $out;
    }

    // call function
    if (isset($_POST)) {
        $tree = '';

        // Check if we need to catch an URL or if we can simply pass the data
        // directly
        if ($_POST['entrytype'] == "url") {
            if (empty($_POST['url'])) die("Please enter an url in the field...");
            $url = $_POST['url'];
            $json = @file_get_contents($url);
            if (empty($json)) die("Could not read data from url");
        } else if ($_POST['entrytype'] == "data") {
            if (empty($_POST['data'])) die("Please enter some data in the field...");
            $json = $_POST['data'];
        }

        echo json_viewer($json);
    }
?>
