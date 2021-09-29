$(function(){
    var good_postid;
    var state;
    var allowflag=1;
    
    $(".good").click(function(){
        if(allowflag==1){
            allowflag=0;
            //連打防止
            setTimeout(function(){
                allowflag=1;
            },500)

            
            var target=$(this);
            state=target.hasClass("active")
            good_postid=target.parent(".evaluation-button").data("idea_id");
            $.ajax({
            type:'POST',
            url:"good_ref.php",
            data:{
                idea_id:good_postid,
                nowstate:state
            }
        }).done(function(good_num){
            target.html(good_num);
            target.toggleClass("active");
        }).fail(function(msg){
            console.log("ajax error");
        })
    }
    })

})