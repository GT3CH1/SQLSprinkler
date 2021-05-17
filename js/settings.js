// Copyright 2021 Gavin Pease
system_status = "";
loadTable = true;

function getSystemStatus() {
    system_status = "";
    $.get('../lib/api.php?systems', function (data, textStatus, jqXHR) {
    }).done(function (data) {
        system_status = JSON.parse(data);
        buildSystemTable();
        updateSystemTable();
        console.log("Done receiving sprinkler data.");
        $("#settings-table").delay(100).fadeIn(250);
    });
}

$(document).ready(function () {
    window.deleteMode = false;
    getSystemStatus();
    $("#add").click(function () {
        getData(-1, true);
    });
    $("#back").click(function () {
        fadeEditOut();
    })
    $("#settings-submit").click(function () {
        runtime = $("#zone-runtime").val();
        name = $("#zone-name").val();
        gpio = $("#zone-gpio").val();
        if (runtime == "")
            runtime = 10;
        if (name == "")
            name = "Change me";
        var addMode = window.addMode;
        if (addMode) {
            submitChanges("", name, gpio, runtime);
        } else {
            var id = $("#system").val();
            console.log(id);
            submitChanges(id, name, gpio, runtime);
        }
    });
});

function getData(id, add) {
    $("#settings-table").fadeOut(500);
    if (add) {
        window.addMode = true;
        $("#zone-name").val('');
        $("#zone-gpio").val('');
        $("#zone-runtime").val('');
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
    $("#edit").fadeIn(500);
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
            id: id,
            name: zonename,
            gpio: gpio,
            runtime: runtime
        };
    }
    if (deleteMode) {
        data = {
            call: "delete",
            id: id
        }
    }
    $.post("../lib/api.php", data).done(function (data) {
        console.log("Received data: " + data);
        setTimeout(getSystemStatus, 10);
        fadeEditOut();
    });
}

function fadeEditOut() {
    $("#edit").fadeOut(500);
    $("#settings-table").fadeIn(500);
}

function createEditRow(index) {
    let tr = "";
    let id = system_status[index]['id'];
    tr += "<tr>";
    tr += "<td id='zone-" + id + "-index'></td>";
    tr += "<td id='zone-" + id + "-name'></td>";
    tr += "<td id='zone-" + id + "-time'></td>";
    tr += "<td>";
    tr += "<button id ='zone-" + id + "-edit' class='w3-button w3-gray w3-round-xlarge' value='" + index + "'>Edit</button>";
    tr += "&nbsp;&nbsp;"
    tr += "<button id ='zone-" + id + "-delete' class='w3-button w3-red w3-round-xlarge' value='" + index + "'>Delete</button>";
    tr += "</td>";
    tr += "</tr>";
    return tr;
}

function setButtonListener() {
    $("button").click(function () {
        let editMode = $(this).attr("id").indexOf('edit') > -1;
        let deleteMode = $(this).attr("id").indexOf('delete') > -1;
        let val = $(this).val();
        if (editMode)
            getData(val, false);
        else if (deleteMode) {
            idToDel = system_status[val]['id'];
            var wantsToDelete = confirm("Are you sure you want to delete zone " + (parseInt(val) + 1) + "?");
            if (!wantsToDelete)
                return;
            window.deleteMode = true;
            submitChanges(idToDel, "", "", "", "");
        }
    });
}

function buildSystemTable() {
    $("#settings-table").html('<tr><th>Zone</th><th>Name</th><th>Run Time</th><th>Actions</th></tr>');
    updateSystemTable();
    setButtonListener();
}

function updateSystemTable() {
    for (let i = 0; i < system_status.length; i++) {
        let currSprinkler = system_status[i];
        let currName = currSprinkler['zonename'];
        let currTime = currSprinkler['runtime'];
        let currZone = i + 1;
        let id = currSprinkler['id'];
        let zoneExists = $("#zone-" + id + "-index").length != 0;
        if (!zoneExists)
            $("#settings-table").append(createEditRow(i));
        $("#zone-" + id + "-index").html(currZone);
        $("#zone-" + id + "-name").html(currName);
        $("#zone-" + id + "-time").html(currTime);
    }
}