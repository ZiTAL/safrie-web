const { kMaxLength } = require("buffer");

var MongoClient = require("mongodb").MongoClient;
var MongoUrl = "mongodb://localhost:27017/local";
var collection = 'blockchain-01';

function getPost()
{
    for(var i=0; i<process.argv.length; i++)
    {
        var m = process.argv[i].match(/^POST_DATA=([^$]+)$/) 
        if(m!==null)
        {
            var j = decodeURIComponent(m[1]);
            return j;
        }
    }
    return null;
}

MongoClient.connect(MongoUrl, function(err, db)
{
    var post_data = getPost();
    db.collection(collection).findOne(post_data, function(err, result)
    {
        if (err)
            throw err;
        console.log(JSON.stringify(result));
        db.close();
    });
});