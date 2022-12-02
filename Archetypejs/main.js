let addOrUpdate; // to track whether we're doing an add or an update

window.onload = function () {

    // add event handlers for buttons
    document.querySelector("#GetButton").addEventListener("click", getAllItems);
    document.querySelector("#AddButton").addEventListener("click", addItem);
    document.querySelector("#DeleteButton").addEventListener("click", deleteItem);
    document.querySelector("#UpdateButton").addEventListener("click", updateItem);
    document.querySelector("#DoneButton").addEventListener("click", processForm);
    document.querySelector("#CancelButton").addEventListener("click", hideUpdatePanel);

    // add event handler for selections on the table
    document.querySelector("table").addEventListener("click", handleRowClick);

    hideUpdatePanel();
};
function resetUpdatePanel() {
    document.querySelector("#itemIDInput").value = "";
    document.querySelectorAll("option")[0].selected = true; // select first one
    document.querySelector("#descriptionInput").value = "";
    document.querySelector("#priceInput").value = 0;
    document.querySelector("#vegetarianInput").checked = false;
}

function handleRowClick(e) {
    //add style to parent of clicked cell
    clearSelections();
    e.target.parentElement.classList.add("highlighted");
    // enable Delete and Update buttons
    document.querySelector("#DeleteButton").removeAttribute("disabled");
    document.querySelector("#UpdateButton").removeAttribute("disabled");
    fillUpdatePanel();
    document.querySelector("#itemID").disabled=true;
    addOrUpdate="update";
}

// make AJAX call to PHP to get JSON data
function getAllItems() {
    let url = "ArchetypeService/items"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the GET ...");
            } else {
                buildTable(resp);
                hideUpdatePanel();
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

    // disable Delete and Update buttons
    document.querySelector("#DeleteButton").setAttribute("disabled", "disabled");
    document.querySelector("#UpdateButton").setAttribute("disabled", "disabled");
}

// text is a JSON string containing an array
function buildTable(text) {
   console.log(text);
    let arr = JSON.parse(text); // get JS Objects
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        html += "<tr>";
        html += "<td>" + row.ArchetypeID + "</td>";
        html += "<td>" + row.ArchetypeName + "</td>";
        html += "<td>" + row.Rarity + "</td>";
        html += "<td>" + row.Difficulty + "</td>";
        html += "<td>" + row.Description + "</td>";
        html += "</tr>";
    }
    theTable.innerHTML = html;
}

function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("highlighted");
    }
}

function hideUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.add("hidden");
}

function showUpdatePanel() {
    document.querySelector("#itemID").value = 0;
    document.querySelector("#name").value = "";
    document.querySelector("#Rarity").value = "";
    document.querySelector("#difficulty").value = 0;
    document.querySelector("#description").value = "";
    document.getElementById("AddUpdatePanel").classList.remove("hidden");
}
function fillUpdatePanel(){
    let selected = document.querySelector(".highlighted");
    let items = selected.querySelectorAll("td");
    let id = Number(items[0].innerHTML)
    let Name = items[1].innerHTML;
    let Rarity=items[2].innerHTML;
    let difficulty=Number(items[3].innerHTML);
    let description=items[4].innerHTML;
    document.querySelector("#itemID").value = id;
    document.querySelector("#name").value = Name;
    document.querySelector("#Rarity").value = Rarity;
    document.querySelector("#difficulty").value = difficulty;
    document.querySelector("#description").value = description;
}
// Called when "Done" button is pressed for either Add or Update
function processForm() {

    // Get data from the form and build an object.
    let id = Number(document.querySelector("#itemID").value);
    let name = document.querySelector("#name").value;
    let rar= document.querySelector("#Rarity").value;
    let diff = Number(document.querySelector("#difficulty").value);
    let desc = document.querySelector("#description").value;

    let obj = {
        ArchetypeID: id,
        ArchetypeName: name,
        Rarity: rar,
        Complication: diff,
        Description: desc
    };

    // Make AJAX call to add or update the record in the database.
    let url = "ArchetypeService/items/"+id
    let method = (addOrUpdate === "add") ? "POST" : "PUT";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0 || resp != 1) {
                console.log(resp);
                alert("oh no...");
            } else {
                getAllItems();
            }
            getAllItems();
            hideUpdatePanel();
        }
    };
    xmlhttp.open(method, url, true); // must be POST
    xmlhttp.send(JSON.stringify(obj));
}

function deleteItem() {
    let row = document.querySelector(".highlighted"); // we know there's only one
    let id = Number(row.querySelectorAll("td")[0].innerHTML);

    // AJAX
    let url = "ArchetypeService/items/"+id; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log(resp)
            if (resp.search("ERROR") >= 0 || resp != 1) {
                alert("oh no...");
                console.log(resp);
            } else {
                getAllItems();
            }
        }
    };
    xmlhttp.open("DELETE", url, true); // must be POST
    xmlhttp.send(id);
}

function addItem() {
    addOrUpdate = "add";
    showUpdatePanel();
    document.querySelector("#itemID").disabled=false;
}

function updateItem() {
    addOrUpdate="update";
    showUpdatePanel();
    fillUpdatePanel();
    document.querySelector("#itemID").disabled;
}

function setIDFieldState(val) {
    let idInput = document.querySelector("#itemID");
    if (val) {
        idInput.removeAttribute("disabled");
    } else {
        idInput.setAttribute("disabled", "disabled");
    }
}