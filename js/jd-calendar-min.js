$.fn.jd_calendar=function(){var n=0;function o(n){return $.ajax({type:"POST",url:"includes/generate_view.php",dataType:"html",data:{calendar_position:n},success:function(n){$(".jd-calendar").html(""),$(".jd-calendar").html(n)},error:function(n,o,c){console.log("Something went wrong")}}),!1}o(n),$("body").on("click",".prev",function(){o(n-=1)}),$("body").on("click",".today",function(){o(n=0)}),$("body").on("click",".next",function(){o(n+=1)})};