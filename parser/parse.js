'use strict';

var pdf2json=require('./pdf2json').parsePDF
  , join=require('path').join
  , gestion='2015-01'
  , path=join(__dirname,'..','data','FCyT',gestion)
  , file=require('file')
  , fs=require('fs')
  , async=require('async')
  , summary=[]
  , q=async.queue(function(data,callback){
        pdf2json(data.item,function(json){
            if(json){
                fs.writeFile(join(data.path,json.code+'.json'),JSON.stringify(json),
                function(error){
                    if(error){throw error}
                    console.log(json.name+' saved');
                    summary.push({
                        code:json.code
                      , name:json.name
                    });
                    callback();
                });
            }else{
                console.log(data.item+' was ignored');
                callback();
            }
        });
    }, 100)

q.drain=function(){
    fs.writeFile(join(path,'..',gestion+'.json'),JSON.stringify(summary),
    function(error){
        if(error){throw error}

        console.log('summary saved');
    });
}

file.walk(path,function(error,base,dirs,files){
    if(error){throw error}
    files.forEach(function(element){
        var suffix='.pdf';
        if(element.indexOf(suffix,element.length-suffix.length) !== -1){
            q.push({path:base,item:element});
        }
    });
});

