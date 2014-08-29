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
      , regex5=/^(LU|MA|MI|JU|VI|SA) ([0-9]{3,4})-([0-9]{3,4})\((.*)\)$/
      , regex6=/^([A-Z .]{4,})$/
      , regex7=/^\(*\)$/

    var flag1=false
      , flag2=false
      , flag3=false

    var level=undefined
      , subject=undefined
      , group=undefined
    var i1=-1
      , i2=-1
      , i3=-1
      , i4=-1

    var result=undefined
      , parseDuration=function(s,f){
            var _s=(parseInt(parseInt(s)/100)*60)+(parseInt(s)%100)
              , _f=(parseInt(parseInt(f)/100)*60)+(parseInt(f)%100)

            return parseInt((_f-_s)/45)
        }

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
                  , schedule:[]
                });
                group=match4[1];
                i3++;
                i4=-1;
            }
        }

        var match5=regex5.exec(element)
        if(match5){
            result.levels[i1].subjects[i2].groups[i3].schedule.push({
                day:match5[1]
              , start:match5[2]
              , end:match5[3]
              , duration:parseDuration(match5[2],match5[3])
              , room:match5[4]
            });
            i4++;
            flag2=true;
        }

        var match6=regex6.exec(element)
        if(match6&&flag2){
            var groups=result.levels[i1].subjects[i2].groups[i3]

            if(!groups.teacher){
                result.levels[i1].subjects[i2]
                      .groups[i3].teacher=match6[1];
            }else{
                var teacher=groups.teacher

                if(teacher!=match6[1]){
                    result.levels[i1].subjects[i2]
                          .groups[i3].teacher=[teacher,match6[1]];
                }
            }

            result.levels[i1].subjects[i2]
                  .groups[i3].schedule[i4].teacher=match6[1];
            flag2=false;
            flag3=true;
        }

        var match7=regex7.exec(element)
        if(match7&&flag3){
            flag3=false;
        }
    });

    console.log(JSON.stringify(result,undefined,4));
});

