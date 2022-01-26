// Create two flags stores in cookies
// Usefull to test the player context (if the player win, we'll save this game in the database)
document.cookie = "temps=";
document.cookie = "win=";

// Set several variables necessary for chronometer functionnality
var start = 0
var end = 0
var diff = 0
var timer = 0
// Call the function chronoStart when the page is loaded
window.onload = chronoStart;

function chronoStart() {
	start = new Date()
	chrono()
}
function chrono() {
    // Calculate the time
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
	// Put the value of the chronometer in the input
    var time = document.getElementById("chrono")
	time.value = min + ":" + sec
    // Store the chronometer value in the cookie "temps"
    document.cookie = "temps="+time.value;
	timer = setTimeout("chrono()", 10)
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
      
        // Append the progressbar to the main progressbar div
        progressbar.appendChild(progressbarinner);

        // When everything is set up we start the animation
        progressbarinner.style.animationPlayState = 'running';
      }
      
      // Display the game over message (after a certain duration, 120 seconds in this case)
      addEventListener('load', function() {
        createProgressbar('progressbar', '120s', function() {
          alert('Vous avez perduuuu ! :( \n\nNouvelle partie ?');
          // We refresh the page to start a new game
        window.location.replace('./game.php');
        });
      });

    // Manage event on card click  
    $("div.card").click(function() {
        // If the card clicked has a children, we exit the function
        if ($(this).children().length > 0) {
            return;
        }
        // If there is 2 images or if the card cliked has the class 'blank', we exit the function
        if ($("div.card img").length == 2 || $(this).hasClass('blank')) {
            return;
        }

        // We store the index number (retrieve by the attribute 'data-index') in the variable data
        var data = {index: $(this).attr("data-index")};

        // We use AJAX in order to update the game page in real time (without refresh the page)
        $.ajax(
            {
                url:'../public/handler.php',
                data: data,
                success: function(result) {
                    // If an error occur, we exit the function
                    if (result == undefined || result.error == 1) {
                        return;
                    }

                    // If the current image is not empty
                    if (result.currentImage != "") {
                        // We build the image (and its path)
                        var path = 'assets/images/' + result.currentImage;
                        var img = $('<img />', {src: path});
                        // Insert the image into the div card
                        img.appendTo($("div.card[data-index='"+ data.index +"']"));
                    }

                    // Manage the case where the player find a pair
                    if (result.isMatch == true) {
                        setTimeout(function() {
                            // Remove the class card and ad the class blank to the div parent of the image
                            $("div.card img").parent().toggleClass('card blank');
                            // Remove the image
                            $("div.blank img").remove();
                            // If there are not remaining cards, the player win (display an alert message)
                            if (result.remainingCards == 0) {
                                // Set a flag win=true in the cookie to pass it in the index.php
                                // In this way, we know that we'll have to persist the data
                                document.cookie = "win=true";
                                alert('Vous avez gagnéééééé ! :)\n\n Nouvelle partie ?');                                
                                window.location.replace('./game.php');
                            }
                        }, 1000);

                    } else if (result.attempt != 1) {   // If the 2 cards don't match, we hide the image
                        setTimeout(function() {
                            // The card is turned off (we hide the image) after 1 second
                            $("div.card img").remove();
                        }, 1000);
                    }
                }
            }
        );

    });
});
