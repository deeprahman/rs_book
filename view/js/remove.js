$(document).ready(function(){

    var user_id = document.getElementById("user_id").textContent;
    var book_cover = document.getElementById("book_cover").textContent;
    var remove = "yes";
    console.log(user_id);
    console.log(book_cover);

    var url = "../remove.php";

    $("#remove").click(
        function(){
            $.post(
                url,
                {rem:remove, usr_id:user_id, cover: book_cover},
                function(data){
                    alert(data);
                }
            );
        }
    );

}   
);