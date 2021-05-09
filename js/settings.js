system_status = "";
system_id = "";


$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return ((results == null) ? null : results[1] || 0);
}
function getSystemStatus(){
    $.get('../lib/api.php?systems', function (data, textStatus, jqXHR) {
        system_status = JSON.parse(data);
    });
}
$(document).ready(function () {
    window.deleteMode = false;
    getSystemStatus();
    $("button").click(function () {
        var id = $(this).attr("id");
        if ($(this).val() == "edit")
            getData(id, false);
        if($(this).val() == "delete"){
            idToDel = system_status[id]['id'];
            var wantsToDelete = confirm("Are you sure you want to delete zone " + (parseInt(id)+1) + "?");
            if(!wantsToDelete)
                return;
            window.deleteMode = true;
            submitChanges(idToDel,"","","","");
        }
    });
    $("#add").click(function () {
        getData(-1, true);
    });
});

function getData(id, add) {
    system_id = id;
    $("#table").fadeOut(500);
    $("#edit").load("edit.html");
    if (add) {
        window.addMode = true;
    } else {
        setTimeout(function () {
            $("#zone-name").val(system_status[id]["zonename"]);
            $("#zone-gpio").val(system_status[id]["gpio"]);
            $("#zone-runtime").val(system_status[id]["runtime"]);
            $("#system").val(system_status[id]["id"]);
            console.log(system_status[id]["id"]);
            window.addMode = false;
        }, 250);
    }
    $("#edit").delay(250).fadeIn(500);
}

function submitChanges(id, zonename, gpio, runtime) {
    var addMode = window.addMode;
    var deleteMode = window.deleteMode;
    var data;
    if (addMode) {
        data = {
            call: "add",
            gpio: gpio,
            name: zonename,
            runtime: runtime
        };
    } else {
        data = {
            call: "update",
            zone: id,
            name: zonename,
            gpio: gpio,
            runtime: runtime
        };
    }
    if(deleteMode){
        data = {
            call: "delete",
            zone: id
        }
    }
    console.log(data);
    $.post("../lib/api.php", data).done(function (data) {
        console.log("Received data: " + data);
        doCloseWindow();
    });
}

function doCloseWindow(){
    $("#edit").fadeOut(500);
    $("#table").fadeIn(500);
    getSystemStatus();
}
