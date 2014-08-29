'use strict';

var pdf2json=require('./pdf2json').parsePDF
  , join=require('path').join
  , path=join(__dirname,'..','data','FCyT','2014-02')
  , file=require('file')
  , summary=[]

file.walk(path,function(err,dirPath,dirs,files){
    console.log(err);
});

//pdf2json(path,function(json){
//    console.log(JSON.stringify(json));
//});

