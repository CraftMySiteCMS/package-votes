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
            data: {
                "url": url
            },
            success: function (response) {
                console.log("success");
                console.log(response)

                //Change the current button
                $('[name="btnVote"]').text("Vérification en cours").attr("disabled", true)
                    .append('<i class="fa fa-spinner fa-spin" style="font-size:14px; margin-left: 10px"></i>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }

        })
    }
})