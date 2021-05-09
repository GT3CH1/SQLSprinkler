var system_status = "";
let system_enable;

function getSprinklers() {
    setInterval(function () {
        $.get('lib/api.php?systemstatus', function (data, textStatus, jqXHR) {
            system_enable = JSON.parse(data)["systemstatus"] == "1";
            if (system_enable) {
                $("#schedule").html("On");
                $("#schedule-btn-txt").html("Off");
                $("#schedule-btn").removeClass("programoff");
                $("#schedule-btn").addClass("programon");
            } else {
                $("#schedule-btn").removeClass("programon");
                $("#schedule-btn").addClass("programoff");
                $("#schedule").html("Off");
                $("#schedule-btn-txt").html("On");
            }
        });
        $.get('lib/api.php?systems', function (data, textStatus, jqXHR) {
            system_status = JSON.parse(data);
        });
        let button_id;
        let name_id;
        for (i = 0; i < system_status.length; i++) {
            button_id = system_status[i]["gpio"];
            // This line is horrid.
            name_id = (system_status[i]["status"].charAt(0).toUpperCase() + system_status[i]["status"].slice(1)) == "Off" ? "On" : "Off";
            document.getElementById(button_id).innerHTML = "Turn " + name_id;
            document.getElementById("status-" + i).innerHTML = ((name_id == "On") ? "Off" : "On");
            if (name_id == "Off") {
                $("#" + button_id).removeClass("systemoff")
                $("#" + button_id).addClass("systemon")
            } else {
                $("#" + button_id).removeClass("systemon")
                $("#" + button_id).addClass("systemoff")
            }
        }
    }, 1000);
    $("body").delay(1750).fadeIn(250);
}

$(document).ready(function () {
    $("#menuopen").click(function () {
        $("#menuopen").fadeOut(250, function () {
            $('#menunav').fadeIn(250);
        });
    });
    $("#menuclose").click(function () {
        $('#menunav').fadeOut(250, function () {
            $("#menuopen").fadeIn(250);
        });
    });
    $("#schedule-btn").click(function () {
        let xhttp = new XMLHttpRequest();
        let enabled = !system_enable;
        var info = "systemenable=" + enabled;
        xhttp.open("GET", "lib/api.php?" + info, true);
        console.log("sending");
        console.log(info);
        xhttp.send();
    });
    $("#update").click(function () {
        console.log("Sent update request...");
        $("button").attr("disabled", "disabled");
        $.get('lib/api.php?update', function (data, textStatus, jqXHR) {
            console.log("Response -> " + data);
            $("#notification-text").html("Done checking for updates. Check log for more information.");
            $("#notification").fadeIn("slow");
            $(".dismiss").click(function () {
                $("#notification").fadeOut("slow");
                $("button").removeAttr("disabled");
            });
        });
    });
});

function getData(index) {
    var xhttp = new XMLHttpRequest();
    var toggle = ((system_status[index]["status"] == "on") ? "off" : "on");
    var info = toggle + "=" + system_status[index]["gpio"];
    xhttp.open("GET", "lib/api.php?" + info, true);
    console.log("sending");
    console.log(info);
    xhttp.send();
}


