<!DOCTYPE html>
<html lang="eu">
	<head>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Safrie - Innovathon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
    h1
    {
        align: center;
    }

    textarea
    {
        align: center;
        width: 100%;
        min-height: 150px;
    }

    pre
    {
        height: 100%; 
        min-height: 100%;
    }
    </style>
	</head>
	<body>
    <div class="container">
        <h1 class="display-1">SareBide-Blockchain:</h1>
        <div class="row mb-3">
            <div class="col-md-12 themed-grid-col">
                <br>
                <h2>Insert information:</h2>
                <form id="insert">
                    <textarea id="block">
                    </textarea>
                    <br><br>
                    <label>blockchain node:</label>
                    <select class="browser-default custom-select">
                        <option value="http://zital-pi.no-ip.org:3001/mineBlock">Blockchain 01</value>
                        <option value="http://zital-pi.no-ip.org:3002/mineBlock">Blockchain 02</value>
                    </select>
                    <br><br>
                    <input class="btn btn-sm btn-outline-secondary" type="submit" value="save">
                </form>
                <hr>
            </div>      
        </div>
        <div class="row mb-2">
            <div class="col-md-12 themed-grid-col">
                <h1>Search:</h1>
                <form id="search">
                    <input class="form-control" type="text">
                    <br>
                    <input class="btn btn-sm btn-outline-secondary" type="submit" value="search">
                    <br><br>
                </form>
            </div>
            <div class="col-6 themed-grid-col">
                <h2>blockchain-01</h2>
                <pre id="s-01">
                </pre>
            </div>
            <div class="col-6 themed-grid-col">
                <h2>blockchain-02</h2>
                <pre id="s-02">
                </pre>                
            </div>
        </div>     
        <hr>
        <div class="row mb-2">
            <div class="col-md-12 themed-grid-col">
                <h1>Blockchain cluster:</h1>
            </div>
            <div class="col-6 themed-grid-col">
                <h2>blockchain-01</h2>
                <pre id="bc-01">
                </pre>
            </div>
            <div class="col-6 themed-grid-col">
                <h2>blockchain-02</h2>
                <pre id="bc-02">
                </pre>                
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>    
    <script>
    (function()
    {
        function sendData(textarea, select,  callback)
        {
            console.log("sendData:");
            console.log(textarea);
            console.log(select);
            var url = "blockchain.php?url="+select;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(response)
            {
                // Process our return data
                if (xhr.status >= 200 && xhr.status < 300)
                    callback(response);
            };
            xhr.open("POST", url);
            xhr.send(textarea);            
        }

        function searchData(callback)
        {
            console.log("searchData:");
            var q = document.querySelector('#search input').value;
            var url = "search.php?q="+q;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function()
            {
                // Process our return data
                if (xhr.status >= 200 && xhr.status < 300)
                {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                }
            };
            xhr.open("GET", url);
            xhr.send();            
        }        

        function getBlockchain(url, callback)
        {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function()
            {
                // Process our return data
                if (xhr.status >= 200 && xhr.status < 300)
                {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                }
            };
            xhr.open("GET", url);
            xhr.send();
        }

        function empty(node)
        {
            while(node.hasChildNodes())
                node.removeChild(node.firstChild);
        }

        var insert_form = document.querySelector('#insert');
        insert_form.onsubmit = function(e)
        {
            e.preventDefault();
            var select = insert_form.querySelector('select').value;
            var textarea = insert_form.querySelector('textarea').value;
            sendData(textarea, select,  function(response)
            {
                console.log(response);
            });
        };

        var search_form = document.querySelector('#search');
        search_form.onsubmit = function(e)
        {
            e.preventDefault();
            searchData(function(response)
            {
                var s01 = response['blockchain-01'];
                var s02 = response['blockchain-02'];
                var pre01 = document.querySelector('#s-01');
                var pre02 = document.querySelector('#s-02');
                empty(pre01);
                empty(pre02);
                pre01.appendChild(document.createTextNode(JSON.stringify(s01, null, 5)));
                pre02.appendChild(document.createTextNode(JSON.stringify(s02, null, 5)));
            });
            return false;
        };        
        window.setInterval(function()
        {     
            var url = 'blockchain.php?url=http://zital-pi.no-ip.org:3001/blocks';
            getBlockchain(url, function(response)
            {
                var pre = document.querySelector('#bc-01');
                empty(pre);
                pre.appendChild(document.createTextNode(JSON.stringify(response, null, 5)));
            });

            var url = 'blockchain.php?url=http://zital-pi.no-ip.org:3002/blocks';
            getBlockchain(url, function(response)
            {
                var pre = document.querySelector('#bc-02');
                empty(pre);
                pre.appendChild(document.createTextNode(JSON.stringify(response, null, 5)));
            });
        }, 1 * 5000);
    })();
    </script>
	</body>
</html>

