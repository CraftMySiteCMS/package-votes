$(document).ready(function (){

    listener();

    function listener(){
        $('[name="btnVote"]').click(function (){
            let urlSite = $(this).val();
            verify(urlSite);

            window.open(urlSite, '_blank');
        })
    }

    function verify(url){
        console.log("Start verification for url " + url);

        //Request
        $.ajax({
            type: "POST",
            url: "vote/verify",
            data: {
                "url": url
            },
            error: false
        }).done(function (data){
            console.log("good");
            console.log(data)

            //Change the current button
            $('[name="btnVote"]').text("Vérification en cours").attr("disabled", true)
                .append('<i class="fa fa-spinner fa-spin" style="font-size:14px; margin-left: 10px"></i>');

        }).fail(function (data){
            console.log("fail");
            console.log(data);

            let reason = data.responseJSON.reason;
        })
    }
})