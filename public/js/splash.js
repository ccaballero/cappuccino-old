jQuery(document).ready(function(){$(document).foundation()})

jQuery(document).ready(function($){
    height=function(){
        if(window.innerHeight){return window.innerHeight}
        else{return document.documentElement.clientHeight}}
    width=function(){
        if(window.innerWidth){return window.innerWidth}
        else{return document.documentElement.clientWidth}}

    prepare=function(){
        section=$('#slider').parent()
        if(height()<=640){section.css('height',height()-45)}
        else{section.css({'height':480,'marginTop':((height()-45)-480)/2})}
        if(width()<=640){section.css('width',width())}
        else{section.css(
            {'width':640,'marginLeft':'auto','marginRight':'auto'})}

        css1={'width':section.width(),'height':section.height()}
        css2={'width':section.width(),'height':section.height()-30}
        $('#slider').css(css1).children('.slides').css(css2)
        return this
    }

    resize=function(){
        jssor.$GoTo(0)
        section=$('#slider')
        css1={'width':section.width(),'height':section.height()}
        css2={'width':section.width(),'height':section.height()-30}
        section.children().css(css1)
               .children().css(css1)
               .children('.slides').css(css2)
        $('.slides div, .slides img').css(css2)
        jssor.$SetSlideWidth(section.width())
             .$SetSlideHeight(section.height()-30)
        $('.slides img').eq(1).parent().css('left',section.width())
        return this
    }

    prepare()
    jssor=new $JssorSlider$('slider',{
        $AutoPlay:true,
        $DragOrientation:1,
        $SlideDuration:900,
        $ArrowKeyNavigation:true,
        $DirectionNavigatorOptions:{
            $Class:$JssorDirectionNavigator$,
            $ChanceToShow:2,
            $Steps:1
        },
        $NavigatorOptions:{
            $Class:$JssorNavigator$,
            $ChanceToShow:2,
            $AutoCenter:1,
            $Steps:1,
            $Lanes:1,
            $SpacingX:10,
            $SpacingY:10,
            $Orientation:1
        }
    })

    jQuery(window).resize(function(){prepare().resize()})
})

