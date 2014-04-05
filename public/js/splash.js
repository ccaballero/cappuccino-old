jQuery(document).ready(function(){$(document).foundation()})

jQuery(document).ready(function($){
    height=function(){
        if(window.innerHeight){return window.innerHeight}
        else{return document.documentElement.clientHeight}}
    width=function(){
        if(window.innerWidth){return window.innerWidth}
        else{return document.documentElement.clientWidth}}

    // initial setting of base properties
    prepare=function(){
        section=$('#slider').parent()

        if(height()<=420){section.css(
            {'height':height()-45,'marginTop':0})}
        else{section.css(
            {'height':420,'marginTop':((height()-45)-420)/2})}

        if(width()<=640){section.css('width',width())}
        else{section.css(
            {'width':640,'marginLeft':'auto','marginRight':'auto'})}

        css1={'width':section.width(),'height':section.height()}
        css2={'width':section.width(),'height':section.height()-30}
        $('#slider').css(css1).children('.slides').css(css2)
        return this
    }

    resize=function(){
        jssor.$Pause()
        section=$('#slider')

        if(section.width()<=640){
            css1={'width':section.width()}
            css2={'left':((width()-46)/2)}
            section.children().css(css1)
                   .children().css(css1)
                   .children('.slides').css(css1)
                   .children('div').css(css1)
            $('.navigator').css(css2)
            $('.slides').children(':last').css({'left':section.width()})
        }

        jssor.$SetSlideHeight(section.height()-30)
        jssor.$SetSlideWidth(section.width())
        jssor.$GoTo(0)
        jssor.$Play()

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

