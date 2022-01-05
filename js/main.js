$(".slider").slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    centerMode: true,
    variableWidth: true
});

//画像クリックで音を鳴らす
$("#cow").on("click",function(){
//クリック後の処理
    $("#play-btn").get(0).play();
});

//画面回転後クリック
$("#chu").on("click",function(){
//クリック後の処理
    $("#goo")[0].click();
});