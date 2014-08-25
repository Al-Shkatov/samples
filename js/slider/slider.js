document.write(
        '<div class="simple-slider simple-slider-top-wrapper">' +
            '<div class="simple-slider simple-slider-wrapper">' +
            '<div class="simple-slider-arrow simple-slider-left"></div>'+
                '<div class="simple-slider simple-slider-inner">' +
                    '<div class="simple-slider simple-slider-slides"></div>' +
                '</div>' +
            '<div class="simple-slider-arrow simple-slider-right"></div>'+
            '</div>' +
            '<div class="simple-slider simple-slider-navigator">' +
            '<div class="simple-slider simple-slider-watch"><a href="http://www.livesportsvideo.com/golive/FHLNetwork.asp"></a></div>'+
            '<div class="simple-slider simple-slider-navigate"></div>'+
            '<div class="simple-slider simple-slider-catch"><a href="http://www.livesportsvideo.com/golive/FHLNetwork.asp"></a></div>'+
            '</div>' +
        '</div>'
);

(function ($) {
    $(function () {
        for(var img in images){
            if(images.hasOwnProperty(img)){
                var path = base_url+'images/slides/'+images[img].image;
                $('<img >',{src:path,'class':'cat'}).appendTo($('.simple-slider-slides'));
                $('.simple-slider-navigate').append($('<div />',{'class':'simple-slider-bullet'}).addClass('simple-slider-bullet-inactive'))
            }
        }
        new Superslider($('.simple-slider-slides'));
    });
    function Superslider(elem,params){
        this.defaults ={
            speed:500,
            slideSelector:'.cat',
            navSelector:'.simple-slider-arrow'
        };
        this.slides=[];
        this.current = 0;
        var self=this;

        $('.simple-slider-wrapper').hover(function(){
            $(self.defaults.navSelector).show();
        },function(){
            $(self.defaults.navSelector).hide();
        });
        $(this.defaults.navSelector).click(function(){
            self.click($(this).is('.simple-slider-left')?-1:1);
        });
        $('.simple-slider-bullet').click(function(){
            clearInterval(inter);
            self.moveTo($(this).index());
        });
        elem.find(this.defaults.slideSelector).each(function(){
            self.slides.push($(this));
        });
        this.click=function(direction){
            clearInterval(inter);
            if(this.current == self.slides.length-1 && direction > 0){
                this.current = -1;
            }
            if(this.current == 0 && direction < 0){
                this.current = self.slides.length;
            }
            self.moveTo(this.current+direction);
        };
        this.moveTo=function(index){
            if(elem.queue().length==0){
                this.current = index;
                this.activeBullet(index);
                var to = self.slides[index];
                if(to.next(self.defaults.slideSelector).length==0){
                    elem.append(elem.find(self.defaults.slideSelector).first());
                    elem.css({
                        left:-to.prev(self.defaults.slideSelector).position().left
                    });
                }
                if(to.prev(self.defaults.slideSelector).length==0){
                    elem.prepend(elem.find(self.defaults.slideSelector).last());
                    elem.css({
                        left:-to.next(self.defaults.slideSelector).position().left
                    });
                }

                var left = to.position().left;
                elem.animate({
                    left:-left
                },self.defaults.speed);
            }
        };
        this.activeBullet = function(index){
            $('.simple-slider-bullet-active').removeClass('simple-slider-bullet-active').addClass('simple-slider-bullet-inactive');
            $('.simple-slider-bullet').eq(index).removeClass('simple-slider-bullet-inactive').addClass('simple-slider-bullet-active');
        };
        var inter = setInterval(function(){self.click(1)},7000);
        this.activeBullet(0);
        return this;
    }
})(jQuery);