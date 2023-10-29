jQuery(function($){
    $('#takePicture').on('click', doCount);
});

function doCount(){
    document.getElementById('fotobox-card').style.display = "none";
    document.getElementById('countdown-box').style.cssText += ';display:block !important;';
    var counter = 5;
    setInterval(function() {
        counter--;
        if (counter >= 0) {
            span = document.getElementById("count");
            span.innerHTML = counter;
        }
        if (counter === 0) {
            clearInterval(counter);
            location.href = '/photo';
        }
    }, 1000);
}