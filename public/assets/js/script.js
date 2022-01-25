document.cookie = "temps=";
document.cookie = "win=";

var startTime = 0
var start = 0
var end = 0
var diff = 0
var timerID = 0
window.onload = chronoStart;

function chrono() {
	end = new Date()
	diff = end - start
	diff = new Date(diff)
	var sec = diff.getSeconds()
	var min = diff.getMinutes()
	if (min < 10) {
		min = "0" + min
	}
	if (sec < 10) {
		sec = "0" + sec
	}
	
    var time = document.getElementById("chrono")
	time.value = min + ":" + sec
    document.cookie = "temps="+time.value;
	timerID = setTimeout("chrono()", 10)
}
function chronoStart() {
	start = new Date()
	chrono()
}

$(function() {
    // Manage the progress bar
    function createProgressbar(id, duration, callback) {
        // We select the div that we want to turn into a progressbar
        var progressbar = document.getElementById(id);
        progressbar.className = 'progressbar';
      
        // We create the div that changes width to show progress
        var progressbarinner = document.createElement('div');
        progressbarinner.className = 'inner';
      
        // Now we set the animation parameters
        progressbarinner.style.animationDuration = duration;
      
        // Eventually couple a callback
        if (typeof(callback) === 'function') {
          progressbarinner.addEventListener('animationend', callback);
        }
      
        // Append the progressbar to the main progressbardiv
        progressbar.appendChild(progressbarinner);

        // When everything is set up we start the animation
        progressbarinner.style.animationPlayState = 'running';
      }
      
      // Display the game over message (after a certain duration)
      addEventListener('load', function() {
        createProgressbar('progressbar', '120s', function() {
          alert('Vous avez perduuuu ! Mais recrutez-moi quand même ;)');
        //   window.location.reload();
        window.location.replace('./game.php');
        });
      });

    // Manage event on card click  
    $("div.card").click(function() {
        if ($(this).children().length > 0) {
            return;
        }

        if ($("div.card img").length == 2 || $(this).hasClass('blank')) {
            return;
        }

        // The data variable contain the index number
        var data = {index: $(this).attr("data-index")};

        $.ajax(
            {
                url:'../public/handler.php',
                data: data,
                success: function(result) {
                    // If an error occur, we exit the function
                    if (result == undefined || result.error == 1) {
                        return;
                    }

                    if (result.currentImage != "") {
                        var path = 'assets/images/' + result.currentImage;
                        var img = $('<img />', {src: path});
                        img.appendTo($("div.card[data-index='"+ data.index +"']"));
                    }

                    // Manage the case where the player find a pair
                    if (result.isMatch == true) {
                        setTimeout(function() {
                            $("div.card img").parent().toggleClass('card blank');
                            $("div.blank img").remove();
                            // If there are not remaining cards, the player win (display an alert message)
                            if (result.remainingCards == 0) {
                                // Set a flag win=true in the cookie for pass it in the index.php
                                // In this way, we know that we'll have to persist the data
                                document.cookie = "win=true";
                                alert('Vous avez gagnéééééé ! Alors, recrutez-moi :)');                                
                                // window.location.reload();
                                window.location.replace('./game.php');
                            }
                        }, 1000);

                    } else if (result.attempt != 1) {
                        setTimeout(function() {
                            $("div.card img").remove();
                        }, 1000);
                    }
                }
            }
        );

    });
});
