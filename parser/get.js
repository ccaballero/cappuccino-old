'use strict';

var request=require('request')
  , fs=require('fs')
  , url='http://www.fcyt.umss.edu.bo/horarios/'
  , join=require('path').join
  , gestion='2014-02'
  , path=join(__dirname,'..','data','FCyT',gestion)
  , regex=/<a href="(.*)">(.*\.pdf)<\/a>/g

// TODO check if directory exists

request(url,function(error,response,body){
    if(error){throw error}

    if(response.statusCode==200){
        var buffer=new Array()
          , result

        while((result=regex.exec(body))!==null){
            buffer.push({
                name:result[2]
              , url:result[1]
            });
        }

        buffer.forEach(function(element){
            console.log('saved: '+element.name);
            request(element.url).pipe(
                fs.createWriteStream(join(path,element.name)));
        });
    }
});


