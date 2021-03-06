$(document).ready(function () {

    listener();

    function listener() {
        $('[name="btnVote"]').click(function () {
            let urlSite = $(this).val();

            /*let token = document.getElementById("token").value;

            if (token === "") {
                console.log("Empty TOKEN, try again")
            } else {
                verify(urlSite, token);
                window.open(urlSite, '_blank');
            }*/

            verify(urlSite);
            window.open(urlSite, '_blank');
        })
    }

    function verify(url) {
        console.log("Start verification for url " + url);

        //Request
        $.ajax({
            type: "POST",
            url: "vote/verify",
            async: true,
            dataType: "html",
            data: {
                "url": url
            },
            success: function (response) {

                console.log(response);

                var jsonData = JSON.parse(response);


                if (jsonData.response === "GOOD" || jsonData.response === "GOOD-NEW_VOTE" || jsonData.response === "ALREADY_VOTE") {
                    //Reset vote button
                    $('[name="btnVote"]').text("Voter").attr("disabled", false);
                } else if (jsonData.response === "NOT_CONFIRMED") {//Verif -> 3 sec
                    setTimeout(function () {
                        verify(url);
                    }, 3000); // 3sec
                } else if (jsonData.response === "ERROR-URL") {
                    console.log("Your URL is empty, try again.")
                } else if (jsonData.response === "ERROR-TOKEN") {
                    console.log("Your TOKEN is empty, try again.")
                } else if (jsonData.response === "ERROR-TOKEN-2") {
                    console.log("Your token is broken, try again.")
                } else{
                    console.log(jsonData.response)
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("ERROR: " + textStatus, errorThrown);
            }

        })

        //Change the current button
        $('[name="btnVote"]').text("V??rification en cours").attr("disabled", true)
            .append('<i class="fa fa-spinner fa-spin" style="font-size:14px; margin-left: 10px"></i>');
    }
})