$(document).ready(function(){

    document.getElementById("searchedWord").addEventListener('keypress', (event) => {
        const nameButton = event.key;
        if (nameButton === "Enter") {
            getWordDescription();
        }
    })
})

function getWordDescription() {
    document.getElementById("ba").innerHTML = "";
    document.getElementById("spinner").style.display = "";
    var word = document.getElementById("searchedWord").value;
    
    $.ajax({
        url: 'http://localhost/e-app/server/index.php',
        type: 'GET',
        data: 'word=' + word,

        success: function(data) {
            document.getElementById("spinner").style.display = "none";
            document.getElementById("searchedWord").value = "";
            $("#ba").html(data)
        },
        error: function(data) {
            alert("erreur : " + data);
        }
    });
}

function writeDataInFile(data) {
    alert("writeDatas");
}