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
            $("#system-id").val(system_status[id]["id"]);
            $("#system-number").val(id);
            $("#zone-delete").val(id);
            $("#zone-enabled").prop("checked", system_status[id]["enabled"]);
            console.log(system_status[id]["id"]);
            window.addMode = false;
        }, 250);
    }
    $("#edit").fadeIn(500);
}

function submitChanges() {
    let id = $("#system-id").val();
    let runtime = $("#zone-runtime").val();
    let zonename = $("#zone-name").val();
    let gpio = $("#zone-gpio").val();
    let scheduled = $("#zone-enabled").prop('checked');
    if (runtime === "")
        runtime = 10;
    if (zonename === "")
        zonename = "Change me";

    let addMode = window.addMode;
    let deleteMode = window.deleteMode;
    let data;
    if(gpio === "" || gpio > 40) {
        alert("You must set a proper GPIO pin!");
        return;
    }
    data = {
        call: "update",
        id: id,
        name: zonename,
        gpio: gpio,
        runtime: runtime,
        scheduled: scheduled
    };

    if (addMode)
        data = {
            call: "add",
            name: zonename,
            gpio: gpio,
            runtime: runtime,
            scheduled: scheduled
        };

    if (deleteMode) {
        data = {
            call: "delete",
            id: id
        }
    }
    console.log(data);
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
    let enabled = system_status[index]['enabled'] ? "" : "unscheduled";
    tr += "<tr class='"+enabled+"'>";
    tr += "<td id='zone-" + id + "-index'></td>";
    tr += "<td id='zone-" + id + "-name' class='w3-hide-small'></td>";
    tr += "<td id='zone-" + id + "-time'></td>";
    tr += "<td>";
    tr += "<button id ='zone-" + id + "-edit' class='w3-button w3-flat-silver w3-round-xlarge' value='" + index + "'>Edit</button>";
    tr += "</td>";
    tr += "</tr>";
    return tr;
}

function setButtonListener() {
    $("button").unbind().click(function () {
        let editMode = $(this).attr("id").indexOf('edit') > -1;
        let deleteMode = $(this).attr("id").indexOf('delete') > -1;
        let val = $(this).val();
        if (editMode)
            getData(val, false);
        else if (deleteMode) {
            var wantsToDelete = confirm("Are you sure you want to delete zone " + (parseInt(val) + 1) + "?");
            if (!wantsToDelete)
                return;
            window.deleteMode = true;
            submitChanges();
        }
    });
    $("#settings-submit").click(function () {
        submitChanges();
    });
    $("#add").click(function () {
        getData(-1, true);
    });
    $("#back").click(function () {
        fadeEditOut();
    })
}

function buildSystemTable() {
    $("#settings-table").html('<tr><th>Zone</th><th class="w3-hide-small">Name</th><th>Run Time</th><th>Actions</th></tr>');
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
        let zoneExists = $("#zone-" + id + "-index").length !== 0;
        if (!zoneExists)
            $("#settings-table").append(createEditRow(i));
        $("#zone-" + id + "-index").html(currZone);
        $("#zone-" + id + "-name").html(currName);
        $("#zone-" + id + "-time").html(currTime);
    }
}