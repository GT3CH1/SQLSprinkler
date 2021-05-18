// Copyright 2021 Gavin Pease
function getZoneData() {
    zoneStatus = "";
    $.get('../lib/api.php?systems').done(function (data) {
        zoneStatus = JSON.parse(data);
        buildZoneTable();
        updateZoneTable();
        console.log("Done receiving sprinkler data.");
        $("#settings-table").delay(100).fadeIn(250);
    });
}

$(document).ready(function () {
    loadTable = true;
    window.deleteMode = false;
    getZoneData();
});

function getData(id, add) {
    $("#settings-table").fadeOut(500);
    if (add) {
        window.addMode = true;
        $("#zone-name").val('');
        $("#zone-gpio").val('');
        $("#zone-runtime").val('');
        $("#zone-enabled").attr('checked', true);
        $("#zone-autooff").attr('checked', true);
    } else {
        setTimeout(function () {
            $("#zone-name").val(zoneStatus[id]["name"]);
            $("#zone-gpio").val(zoneStatus[id]["gpio"]);
            $("#zone-runtime").val(zoneStatus[id]["runtime"]);
            $("#zone-id").val(zoneStatus[id]["id"]);
            $("#zone-number").val(id);
            $("#zone-delete").val(id);
            $("#zone-enabled").prop("checked", zoneStatus[id]["enabled"]);
            $("#zone-autooff").prop("checked", zoneStatus[id]["autooff"]);
            console.log(zoneStatus[id]["id"]);
            window.addMode = false;
        }, 250);
    }
    $("#edit").fadeIn(500);
}

function submitChanges() {
    let id = $("#zone-id").val();
    let runtime = $("#zone-runtime").val();
    let zonename = $("#zone-name").val();
    let gpio = $("#zone-gpio").val();
    let scheduled = $("#zone-enabled").prop('checked');
    let autooff = $("#zone-autooff").prop('checked');
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
        contentType: 'application/json',
        dataType: 'json',
        call: "update",
        id: id,
        name: zonename,
        gpio: gpio,
        runtime: runtime,
        scheduled: scheduled,
        autooff: autooff
    };

    if (addMode)
        data = {
            contentType: 'application/json',
            dataType: 'json',
            call: "add",
            name: zonename,
            gpio: gpio,
            runtime: runtime,
            scheduled: scheduled,
            autooff: autooff
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
        setTimeout(getZoneData, 10);
        fadeEditOut();
    });
}

function fadeEditOut() {
    $("#edit").fadeOut(500);
    $("#settings-table").fadeIn(500);
}

function createEditRow(index) {
    let tr = "";
    let id = zoneStatus[index]['id'];
    let enabled = zoneStatus[index]['enabled'] ? "" : "unscheduled";
    let autooff = zoneStatus[index]['autooff'] ? "" : "italic"

    tr += "<tr class='"+enabled+" "+autooff+"'>";
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

function buildZoneTable() {
    $("#settings-table").html('<tr><th>Zone</th><th class="w3-hide-small">Name</th><th>Run Time</th><th>Actions</th></tr>');
    updateZoneTable();
    setButtonListener();
}

function updateZoneTable() {
    for (let i = 0; i < zoneStatus.length; i++) {
        let currSprinkler = zoneStatus[i];
        let currName = currSprinkler['name'];
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