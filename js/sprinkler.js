// Copyright 2021 Gavin Pease
var system_status = "";
let system_enable;
let loadTable = true;

function getSprinklerData() {
    $.get('lib/api.php?systemstatus', function (data, textStatus, jqXHR) {
        system_enable = JSON.parse(data)["systemstatus"] == "1";
        if (system_enable) {
            $("#schedule").html("On");
            $("#schedule-btn-txt").html("Off");
            $("#schedule-btn").removeClass("programoff").addClass("programon");
        } else {
            $("#schedule-btn").removeClass("programon").addClass("programoff");
            $("#schedule").html("Off");
            $("#schedule-btn-txt").html("On");
        }
    });
    $.get('lib/api.php?systems', function () {

    }).done(function (data, textStatus, jqXHR) {
        system_status = JSON.parse(data);
        if (loadTable)
            createTable();
        loadTable = false;
    });
}

function getSprinklers() {
    setInterval(function () {
        getSprinklerData();
        let button_id;
        let name_id;
        for (i = 0; i < system_status.length; i++) {
            button_id = system_status[i]["gpio"];
            name_id = system_status[i]["status"] == "off" ? "On" : "Off";
            document.getElementById("status-" + i).innerHTML = ((name_id == "On") ? "Off" : "On");
            document.getElementById("status-button-" + i).innerHTML = name_id;
            if (name_id == "Off") {
                $("#" + button_id).removeClass("systemoff").fadeIn(150);
                $("#" + button_id).addClass("systemon").fadeIn(150);
            } else {
                $("#" + button_id).removeClass("systemon").fadeIn(150);
                $("#" + button_id).addClass("systemoff").fadeIn(150);
            }
        }
    }, 1000);
}

$(document).ready(function () {
    $("#sprinklerData").delay(1750).fadeIn(250);
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

function createTable() {
    let tr ="";
    console.log(system_status.length)
    for (i = 0; i < system_status.length; i++) {
        let sprinklerInfo = system_status[i];
        let name = sprinklerInfo['zonename'];
        let gpio = sprinklerInfo['gpio'];
        tr += "<tr><td><div class='sprinkler-info'><p class='sprinkler-name'>Zone " + (i + 1) + "</p><p> " + name + " </p><p>Status: <span id='status-" + i + "'>Off</span></p> </div></td>"
        tr += "<td><div class='sprinkler-button'><button id='" + gpio + "' name='toggle' onclick='getData(" + i + "); return false' class='w3-button systemoff w3-round-xxlarge mybutton w3-center'>Turn <span id='status-button-" + i + "'>Off</span></button> </div></td></tr>"
    }
    $("#sprinklerData").append(tr);
}

function getData(index) {
    var xhttp = new XMLHttpRequest();
    var toggle = ((system_status[index]["status"] == "on") ? "off" : "on");
    var info = toggle + "=" + system_status[index]["gpio"];
    xhttp.open("GET", "lib/api.php?" + info, true);
    console.log("sending");
    console.log(info);
    xhttp.send();
}


