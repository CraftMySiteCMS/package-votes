$(document).ready(function () {

    listener();

    function listener() {
        $('[name="btnVote"]').click(function () {
            let urlSite = $(this).val();
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
            data: {
                "url": url
            },
            success: function (response) {
                console.log("success, response: " + response);

                if (response === "GOOD"){
                    //Reset vote button
                    $('[name="btnVote"]').text("Voter").attr("disabled", false);
                }

                //Verif -> 3 sec
                if (response === "NOT_CONFIRMED"){
                    setTimeout(function (){
                       verify(url);
                    }, 3000); // 3sec
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("ERROR: " + textStatus, errorThrown);
            }

        })

        //Change the current button
        $('[name="btnVote"]').text("Vérification en cours").attr("disabled", true)
            .append('<i class="fa fa-spinner fa-spin" style="font-size:14px; margin-left: 10px"></i>');
    }
})