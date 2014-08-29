'use strict';

var pdftext=require('pdf-text')
  , join=require('path').join
  , path=join(__dirname,'..','data','FCyT','2014-02','419701.pdf')

pdftext(path,function(err,chunks){
    if(err){
        console.log('error:'+err);
        return;
    }

    var regex1=/^([A-Z ]+)\(([0-9]+)\)$/
      , regex2=/^ ?([ABCDEFGHIJ]) ?$/
      , regex3=/^([0-9]{7}) ?([A-Z ]+)/
      , regex4=/^([0-9]{1,2}[A-Z]?)$/

    var flag1=false

    var level=undefined
      , subject=undefined
      , group=undefined
    var i1=-1
      , i2=-1
      , i3=-1

    var result=undefined

    chunks.forEach(function(element){
        var match1=regex1.exec(element)
        if(match1&&!flag1){
            result={
                code:match1[2]
              , name:match1[1]
              , levels:[]
            };
            flag1=true;
        }
 
        var match2=regex2.exec(element)
        if(match2){
            if(!level||level!=match2[1]){
                result.levels.push({
                    code:match2[1]
                  , subjects:[]
                });
                level=match2[1];
                i1++;
                i2=-1;
                subject=undefined;
            }
        }

        var match3=regex3.exec(element)
        if(match3){
            if(!subject||subject!=match3[1]){
                result.levels[i1].subjects.push({
                    code:match3[1]
                  , name:match3[2]
                  , groups:[]
                });
                subject=match3[1];
                i2++;
                i3=-1;
                group=undefined;
            }
        }
        
        var match4=regex4.exec(element)
        if(match4){
            if(!group||group!=match4[1]){
                result.levels[i1].subjects[i2].groups.push({
                    code:match4[1]
                });
                group=match4[1];
                i3++;
            }
        }
    });

    console.log(JSON.stringify(result,undefined,4));
});

