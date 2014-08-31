'use strict';

var pdf2json=require('./pdf2json').parsePDF
  , join=require('path').join
  , gestion='2013-01'
  , path=join(__dirname,'..','data','FCyT',gestion)
  , file=require('file')
  , fs=require('fs')
  , async=require('async')
  , summary=[]
  , q=async.queue(function(data,callback){
        pdf2json(data.item,function(json){
            fs.writeFile(join(data.path,json.code+'.json'),JSON.stringify(json),function(err){
                if(err){throw err}
                console.log(json.name+' saved');
                summary.push({
                    code:json.code
                  , name:json.name
                });
                callback();
            });
        });
    }, 100)

q.drain=function(){
    fs.writeFile(join(path,'..',gestion+'.json'),JSON.stringify(summary),function(err){
        if(err){throw err}

        console.log('summary saved');
    });
}

file.walk(path,function(err,base,dirs,files){
    if(err){throw err}
    files.forEach(function(element){
        var suffix='.pdf';
        if(element.indexOf(suffix,element.length-suffix.length) !== -1){
            q.push({path:base,item:element});
        }
    });
});



